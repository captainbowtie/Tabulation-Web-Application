<?php

/*
 * Copyright (C) 2017 allen
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

require_once 'dblogin.php';

function sanitize_string($string) {
    if (get_magic_quotes_gpc()) {
        $string = stripslashes($string);
    }
    return htmlentities($string);
}

function getBallotPD($ballotId, $teamNumber) {
    $connection = new mysqli(dbhost, dbuser, dbpass, dbname);
    $query = "SELECT * FROM ballots WHERE id='" . $ballotId . "'";
    $result = $connection->query($query);
    $ballot = $result->fetch_array(MYSQLI_ASSOC);
    $result->close();
    $pPoints = $ballot['pOpen'] + $ballot['pDirect1'] + $ballot['pWitDirect1'] +
            $ballot['pWitCross1'] + $ballot['pDirect2'] + $ballot['pWitDirect2'] +
            $ballot['pWitCross2'] + $ballot['pDirect3'] + $ballot['pWitDirect3'] +
            $ballot['pWitCross3'] + $ballot['pCross1'] + $ballot['pCross2'] +
            $ballot['pCross3'] + $ballot['pClose'];
    $dPoints = $ballot['dOpen'] + $ballot['dDirect1'] + $ballot['dWitDirect1'] +
            $ballot['dWitCross1'] + $ballot['dDirect2'] + $ballot['dWitDirect2'] +
            $ballot['dWitCross2'] + $ballot['dDirect3'] + $ballot['dWitDirect3'] +
            $ballot['dWitCross3'] + $ballot['dCross1'] + $ballot['dCross2'] +
            $ballot['dCross3'] + $ballot['dClose'];
    $plaintiffPD = $pPoints - $dPoints;
    if ($teamNumber == $ballot['pTeam']) {
        return $plaintiffPD;
    } else {
        return -1 * $plaintiffPD;
    }
}

function getWins($teamNumber) {
    $connection = new mysqli(dbhost, dbuser, dbpass, dbname);
    $query = "SELECT id FROM ballots WHERE pTeam='" . $teamNumber . "' || dTeam='" . $teamNumber . "'";
    $result = $connection->query($query);
    $wins = 0;
    $connection->close();
    for ($a = 0; $a < $result->num_rows; $a++) {
        $result->data_seek($a);
        $ballot = $result->fetch_array(MYSQLI_NUM);
        $ballotId = $ballot[0];
        $PD = getBallotPD($ballotId, $teamNumber);
        if ($PD > 0) {
            $wins++;
        }
    }
    return $wins;
}

function getLoses($teamNumber) {
    $connection = new mysqli(dbhost, dbuser, dbpass, dbname);
    $query = "SELECT id FROM ballots WHERE pTeam='" . $teamNumber . "' || dTeam='" . $teamNumber . "'";
    $result = $connection->query($query);
    $loses = 0;
    $connection->close();
    for ($a = 0; $a < $result->num_rows; $a++) {
        $result->data_seek($a);
        $ballot = $result->fetch_array(MYSQLI_NUM);
        $ballotId = $ballot[0];
        $PD = getBallotPD($ballotId, $teamNumber);
        if ($PD < 0) {
            $loses++;
        }
    }
    return $loses;
}

function getTies($teamNumber) {
    $connection = new mysqli(dbhost, dbuser, dbpass, dbname);
    $query = "SELECT id FROM ballots WHERE pTeam='" . $teamNumber . "' || dTeam='" . $teamNumber . "'";
    $result = $connection->query($query);
    $ties = 0;
    $connection->close();
    for ($a = 0; $a < $result->num_rows; $a++) {
        $result->data_seek($a);
        $ballot = $result->fetch_array(MYSQLI_NUM);
        $ballotId = $ballot[0];
        $PD = getBallotPD($ballotId, $teamNumber);
        if ($PD == 0) {
            $ties++;
        }
    }
    return $ties;
}

function getStatisticalWins($teamNumber) {
    $connection = new mysqli(dbhost, dbuser, dbpass, dbname);
    $query = "SELECT id FROM ballots WHERE pTeam='" . $teamNumber . "' || dTeam='" . $teamNumber . "'";
    $result = $connection->query($query);
    $wins = 0;
    $connection->close();
    for ($a = 0; $a < $result->num_rows; $a++) {
        $result->data_seek($a);
        $ballot = $result->fetch_array(MYSQLI_NUM);
        $ballotId = $ballot[0];
        $PD = getBallotPD($ballotId, $teamNumber);
        if ($PD > 0) {
            $wins++;
        } else if ($PD == 0) {
            $wins += 0.5;
        }
    }
    return $wins;
}

function getCS($teamNumber) {
    $connection = new mysqli(dbhost, dbuser, dbpass, dbname);
    $pTeamQuery = "SELECT DISTINCT pTeam FROM ballots WHERE dTeam=" . $teamNumber;
    $pResult = $connection->query($pTeamQuery);
    $dTeamQuery = "SELECT DISTINCT dTeam FROM ballots WHERE pTeam=" . $teamNumber;
    $dResult = $connection->query($dTeamQuery);
    $connection->close();
    $CS = 0.0;
    for ($a = 0; $a < $pResult->num_rows; $a++) {
        $pResult->data_seek($a);
        $resultRow = $pResult->fetch_array(MYSQLI_NUM);
        $opposingTeamNumber = $resultRow[0];
        $CS += getStatisticalWins($opposingTeamNumber);
    }
    for ($a = 0; $a < $dResult->num_rows; $a++) {
        $dResult->data_seek($a);
        $resultRow = $dResult->fetch_array(MYSQLI_NUM);
        $opposingTeamNumber = $resultRow[0];
        $CS += getStatisticalWins($opposingTeamNumber);
    }
    $pResult->close();
    $dResult->close();
    return $CS;
}

function getOCS($teamNumber) {
    $connection = new mysqli(dbhost, dbuser, dbpass, dbname);
    $pTeamQuery = "SELECT DISTINCT pTeam FROM ballots WHERE dTeam=" . $teamNumber;
    $pResult = $connection->query($pTeamQuery);
    $dTeamQuery = "SELECT DISTINCT dTeam FROM ballots WHERE pTeam=" . $teamNumber;
    $dResult = $connection->query($dTeamQuery);
    $connection->close();
    $OCS = 0.0;
    for ($a = 0; $a < $pResult->num_rows; $a++) {
        $pResult->data_seek($a);
        $resultRow = $pResult->fetch_array(MYSQLI_NUM);
        $opposingTeamNumber = $resultRow[0];
        $OCS += getCS($opposingTeamNumber);
    }
    for ($a = 0; $a < $dResult->num_rows; $a++) {
        $dResult->data_seek($a);
        $resultRow = $dResult->fetch_array(MYSQLI_NUM);
        $opposingTeamNumber = $resultRow[0];
        $OCS += getCS($opposingTeamNumber);
    }
    $pResult->close();
    $dResult->close();
    return $OCS;
}

function getPD($teamNumber) {
    $query = "SELECT id FROM ballots WHERE pTeam='" . $teamNumber . "' || dTeam='" . $teamNumber . "'";
    $connection = new mysqli(dbhost, dbuser, dbpass, dbname);
    $result = $connection->query($query);
    $connection->close();
    $PD = 0;
    for ($a = 0; $a < $result->num_rows; $a++) {
        $result->data_seek($a);
        $resultRow = $result->fetch_array(MYSQLI_NUM);
        $PD += getBallotPD($resultRow[0], $teamNumber);
    }
    return $PD;
}

function getSchoolConflicts($teamNumber) {
    $conflictQuery = "SELECT team1,team2 FROM teamConflicts "
            . "WHERE sameSchool=1 && (team1=$teamNumber || team2=$teamNumber)";
    $connection = new mysqli(dbhost, dbuser, dbpass, dbname);
    $result = $connection->query($conflictQuery);
    $connection->close();
    $conflictList = array();
    for ($a = 0; $a < $result->num_rows; $a++) {

        $result->data_seek($a);
        $conflict = $result->fetch_array(MYSQLI_ASSOC);
        $team1 = $conflict['team1'];
        $team2 = $conflict['team2'];
        if ($team1 != $teamNumber) {
            array_push($conflictList, $team1);
        } else {
            array_push($conflictList, $team2);
        }
    }
    return array_unique($conflictList);
}

function getAllConflicts($teamNumber) {
    $conflictQuery = "SELECT team1,team2 FROM teamConflicts "
            . "WHERE team1=$teamNumber || team2=$teamNumber";
    $connection = new mysqli(dbhost, dbuser, dbpass, dbname);
    $result = $connection->query($conflictQuery);
    $connection->close();
    $conflictList = array();
    for ($a = 0; $a < $result->num_rows; $a++) {
        $result->data_seek($a);
        $conflict = $result->fetch_array(MYSQLI_ASSOC);
        $team1 = $conflict['team1'];
        $team2 = $conflict['team2'];
        if ($team1 != $teamNumber) {
            array_push($conflictList, $team1);
        } else {
            array_push($conflictList, $team2);
        }
    }
    return array_unique($conflictList);
}

function getAllTeamNumbers() {
    $connection = new mysqli(dbhost, dbuser, dbpass, dbname);
    if ($connection->connect_error) {
        die($connection->connect_error);
    }
    $teamQuery = "SELECT number FROM teams ORDER BY number";
    $teamResult = $connection->query($teamQuery);
    $return = array();
    for($a=0;$a<$teamResult->num_rows;$a++){
        $teamResult->data_seek($a);
        $teamNumber = $teamResult->fetch_array(MYSQLI_ASSOC);
        $return[$a] = $teamNumber['number'];
    }
    return $return;
}
