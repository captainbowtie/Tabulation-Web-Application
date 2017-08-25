<?php

/*
  Copyright (C) 2017 allen

  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU Affero General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU Affero General Public License for more details.

  You should have received a copy of the GNU Affero General Public License
  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

//TODO: Check session privileges
session_start();
require_once "dblogin.php";
require_once "header.php";

$connection = new mysqli(dbhost, dbuser, dbpass, dbname);
$numberTeamsQuery = "SELECT number FROM teams";
$result = $connection->query($numberTeamsQuery);
$numberRows = $result->num_rows;
if ($numberRows == 0) {
    echo "There are no teams";
} else {
    for ($a = 0; $a < $numberRows; $a++) {
        $result->data_seek($a);
        $team = $result->fetch_array(MYSQLI_ASSOC);
        createTeamRow($team['number'], $team['name']);    
    }
}
echo "</html>";

function createTeamRow($teamNumber, $teamName) {
    global $connection;
    $resultArray = [];
    //Extract all the teams ballots into a array of MySQL results, each result
    //containing all of the ballots from a particular round
    for ($a = 0; $a < 4; $a++) {
        $ballotQuery = "SELECT * FROM ballots WHERE roundNumber='" . ($a + 1) . "' && "
                . "(pTeamNumber='" . $teamNumber . "' || dTeamNumber='" . $teamNumber . "') "
                . "ORDER BY id;";
        $resultArray[$a] = $connection->query($ballotQuery);
    }
    $connection->close();
    //Initial all variables that will be printed
    $round1IsPlaintiff = TRUE;
    $round3IsPlaintiff = TRUE;
    $round1Opponent = "0000";
    $round2Opponent = "0000";
    $round3Opponent = "0000";
    $round4Opponent = "0000";
    $round1Ballot1PD = 0;
    $round1Ballot2PD = 0;
    $round2Ballot1PD = 0;
    $round2Ballot2PD = 0;
    $round3Ballot1PD = 0;
    $round3Ballot2PD = 0;
    $round4Ballot1PD = 0;
    $round4Ballot2PD = 0;
    $record = "0-0-0";
    $PD = 0;
    $CS = 0;

    //Get out Round 1 and Round 2 data, if it exists
    if ($resultArray[0]->num_rows != 0) { //Check if Round 1 data exists
        $resultArray[0]->data_seek(0);
        $round1Ballot1 = $resultArray[0]->fetch_array(MYSQLI_ASSOC);
        $resultArray[0]->data_seek(1);
        $round1Ballot2 = $resultArray[0]->fetch_array(MYSQLI_ASSOC);
        //Check team side for round 1
        if ($round1Ballot1['dTeamNumber'] == $teamNumber) {
            $round1IsPlaintiff = FALSE;
            //Get opposing team number
            $round1Opponent = $round1Ballot1['pTeamNumber'];
        } else {
            $round1Opponent = $round1Ballot1['dTeamNumber'];
        }
        //Get point differential on each ballot
        $round1Ballot1PD = getBallotPD($round1Ballot1['id'], $teamNumber);
        $round1Ballot2PD = getBallotPD($round1Ballot2['id'], $teamNumber);
        //Round 2 Data
        if ($resultArray[1]->num_rows != 0) { //check if round 2 ballots exist
            $resultArray[1]->data_seek(0);
            $round2Ballot1 = $resultArray[1]->fetch_array(MYSQLI_ASSOC);
            $resultArray[1]->data_seek(1);
            $round2Ballot2 = $resultArray[1]->fetch_array(MYSQLI_ASSOC);
            if ($round1IsPlaintiff) {
                $round2Opponent = $ound2Ballot1['pTeamNumber'];
            } else {
                $round2Opponent = $ound2Ballot1['dTeamNumber'];
            }
            $round2Ballot1PD = getBallotPD($round2Ballot1['id'], $teamNumber);
            $round2Ballot2PD = getBallotPD($round2Ballot2['id'], $teamNumber);
        }
    }
    //Get Round 3 and 4 data, if it exists
    if ($resultArray[2]->num_rows != 0) {
        $resultArray[2]->data_seek(0);
        $round3Ballot1 = $resultArray[2]->fetch_array(MYSQLI_ASSOC);
        $resultArray[2]->data_seek(1);
        $round3Ballot2 = $resultArray[2]->fetch_array(MYSQLI_ASSOC);
        //Check team side for round 3
        if ($round3Ballot1['dTeamNumber'] == $teamNumber) {
            $round3IsPlaintiff = FALSE;
            //Get opposing team number
            $round3Opponent = $round3Ballot1['pTeamNumber'];
        } else {
            $round3Opponent = $round3Ballot1['dTeamNumber'];
        }
        //Get point differential on each ballot
        $round3Ballot1PD = getBallotPD($round3Ballot1['id'], $teamNumber);
        $round3Ballot2PD = getBallotPD($round3Ballot2['id'], $teamNumber);
        //Round 4 Data
        if ($resultArray[3]->num_rows != 0) { //check if round 2 ballots exist
            $resultArray[3]->data_seek(0);
            $round4Ballot1 = $resultArray[3]->fetch_array(MYSQLI_ASSOC);
            $resultArray[3]->data_seek(1);
            $round4Ballot2 = $resultArray[1]->fetch_array(MYSQLI_ASSOC);
            if ($round3IsPlaintiff) {
                $round4Opponent = $ound4Ballot1['pTeamNumber'];
            } else {
                $round4Opponent = $ound4Ballot1['dTeamNumber'];
            }
            $round4Ballot1PD = getBallotPD($round4Ballot1['id'], $teamNumber);
            $round4Ballot2PD = getBallotPD($round4Ballot2['id'], $teamNumber);
        }
    }
    echo "<table>";
    //Team Number
    echo "<tr><td>" . $teamNumber . "</td>";
    //Round 1 side, opponent, and round 2 side
    if($round1IsPlaintiff){
        echo "<td>π</td><td>vs.</td>";
        echo "<td>".$round1Opponent."</td>";
        echo "<td>∆</td><td>vs.</td>";
    }else{
        echo "<td>∆</td><td>vs.</td>";
        echo "<td>".$round1Opponent."</td>";
        echo "<td>π</td><td>vs.</td>";
    }
    //Round 2 opponent
    echo "<td>".$round2Opponent."</td>";
    //ROund 3 side, opponent, and round 4 side
    if($round3IsPlaintiff){
        echo "<td>π</td><td>vs.</td>";
        echo "<td>".$round3Opponent."</td>";
        echo "<td>∆</td><td>vs.</td>";
    }else{
        echo "<td>∆</td><td>vs.</td>";
        echo "<td>".$round3Opponent."</td>";
        echo "<td>π</td><td>vs.</td>";
    }
    //Round 4 opponent
    echo "<td>".$round4Opponent."</td>";
    //Record
    echo "<td>".getWins($teamNumber)."-".getLoses($teamNumber)."-".getTies($teamNumber)."</td></tr>";
    //Team Name
    echo "<tr><td>".$teamName."</td>";
    //Round 1 Ballots
    echo "<td>".$round1Ballot1."</td><td></td>".$round1Ballot2."</td>";
    //Round 2 Ballots
    echo "<td>".$round2Ballot1."</td><td></td>".$round2Ballot2."</td>";
    //Round 3 Ballots
    echo "<td>".$round3Ballot1."</td><td></td>".$round3Ballot2."</td>";
    //Round 4 Ballots
    echo "<td>".$round4Ballot1."</td><td></td>".$round4Ballot2."</td>";
    //CS, OCS, PD
    echo "<td>CS: ".getCS($teamNumber)." OCS: ".getOCS($teamNumber)." PD: ".getPD($teamNumber)."</td></tr>";
    echo "</table>";
}

?>