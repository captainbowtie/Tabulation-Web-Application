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
    if ($teamNumber == $ballot['pTeamNumber']) {
        return $plaintiffPD;
    } else {
        return -1 * $plaintiffPD;
    }
}

function getWins($teamNumber) {
    $connection = new mysqli(dbhost, dbuser, dbpass, dbname);
    $query = "SELECT id FROM ballots WHERE pTeamNumber='" . $teamNumber . "' || dTeamNumber='" . $teamNumber . "'";
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
    $query = "SELECT id FROM ballots WHERE pTeamNumber='" . $teamNumber . "' || dTeamNumber='" . $teamNumber . "'";
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
    $query = "SELECT id FROM ballots WHERE pTeamNumber='" . $teamNumber . "' || dTeamNumber='" . $teamNumber . "'";
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
    $query = "SELECT id FROM ballots WHERE pTeamNumber='" . $teamNumber . "' || dTeamNumber='" . $teamNumber . "'";
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

function getCS($teamNumber){
    
}
