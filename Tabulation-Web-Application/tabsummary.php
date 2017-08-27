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
/*TODO: when data is manually entered by tabulation director, confirm that it is
sane before writing it to the database */
//TODO: revert to ability not to edit directly, as all editing should be done in ballot screen

session_start();

require_once "dblogin.php";

//HTML Header information
echo<<<_END
<!DOCTYPE HTML>
<html>
        <head>
            <title>Tab Summary</title>
<script src='https://code.jquery.com/jquery-3.2.1.min.js'></script>
                </head>
        <body>
_END;

require_once "header.php";

//Web page if not logged in
if (!isset($_SESSION['id'])) {
    echo "You must be logged in to view this page";
//Web page if logged in, but not as a coach or tabulation director
} else if (!$isTab && !$isCoach) {
    echo "You do not have permission to access this page";
//Code common to coach and tabulation director pages
} else {
    $connection = new mysqli(dbhost, dbuser, dbpass, dbname);
    $numberTeamsQuery = "SELECT * FROM teams";
    $result = $connection->query($numberTeamsQuery);
    $numberRows = $result->num_rows;
    //Check if there are any teams
    if ($numberRows == 0) {
        echo "There are no teams";
    } else { //if there are teams, create tab summary table
        echo "\n<table>";
        for ($a = 0; $a < $numberRows; $a++) {
            $result->data_seek($a);
            $team = $result->fetch_array(MYSQLI_ASSOC);
            //Web page if logged in as tabulation director (r+w scores)
            if ($isTab) {
                echo "\n<form>\n";
                createTabTeamRow($team['number'], $team['name']);
                echo "</form>";
                //Web page if logged in as a coach (r scores)
            } else if ($isCoach) {
                createCoachTeamRow($team['number'], $team['name']);
            }
        }
        echo "\n</table>";
    }
}
echo "\n</html>";

function createCoachTeamRow($teamNumber, $teamName) {
    $connection = new mysqli(dbhost, dbuser, dbpass, dbname);
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
    $record = getWins($teamNumber) . "-" . getLoses($teamNumber) . "-" . getTies($teamNumber);
    $PD = getPD($teamNumber);
    $CS = getCS($teamNumber);
    $OCS = getOCS($teamNumber);

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

//Print all the data to a table row
    //Team Number
    echo "<tr><td>" . $teamNumber . "</td>";
    //Round 1 side, opponent, and round 2 side
    if ($round1IsPlaintiff) {
        echo "<td>π</td><td>vs.</td>";
        echo "<td>" . $round1Opponent . "</td>";
        echo "<td>∆</td><td>vs.</td>";
    } else {
        echo "<td>∆</td><td>vs.</td>";
        echo "<td>" . $round1Opponent . "</td>";
        echo "<td>π</td><td>vs.</td>";
    }
    //Round 2 opponent
    echo "<td>" . $round2Opponent . "</td>";
    //ROund 3 side, opponent, and round 4 side
    if ($round3IsPlaintiff) {
        echo "<td>π</td><td>vs.</td>";
        echo "<td>" . $round3Opponent . "</td>";
        echo "<td>∆</td><td>vs.</td>";
    } else {
        echo "<td>∆</td><td>vs.</td>";
        echo "<td>" . $round3Opponent . "</td>";
        echo "<td>π</td><td>vs.</td>";
    }
    //Round 4 opponent
    echo "<td>" . $round4Opponent . "</td>";
    //Record
    echo "<td>" . $record . "</td></tr>";
    //Team Name
    echo "<tr><td>" . $teamName . "</td>";
    //Round 1 Ballots
    echo "<td>" . $round1Ballot1PD . "</td><td></td><td>" . $round1Ballot2PD . "</td>";
    //Round 2 Ballots
    echo "<td>" . $round2Ballot1PD . "</td><td></td><td>" . $round2Ballot2PD . "</td>";
    //Round 3 Ballots
    echo "<td>" . $round3Ballot1PD . "</td><td></td><td>" . $round3Ballot2PD . "</td>";
    //Round 4 Ballots
    echo "<td>" . $round4Ballot1PD . "</td><td></td><td>" . $round4Ballot2PD . "</td>";
    //CS, OCS, PD
    echo "<td>CS: " . $CS . " OCS: " . $OCS . " PD: " . $PD . "</td></tr>";
    echo "\n";
}

//TODO: make this able to write data to the database
function createTabTeamRow($teamNumber, $teamName) {
    $connection = new mysqli(dbhost, dbuser, dbpass, dbname);
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
    $record = getWins($teamNumber) . "-" . getLoses($teamNumber) . "-" . getTies($teamNumber);
    $PD = getPD($teamNumber);
    $CS = getCS($teamNumber);
    $OCS = getOCS($teamNumber);

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
    //Team Number
    echo "<tr><td>" .
    "<input type=text pattern='[0-9]{4}' value=$teamNumber size='4' maxlength='4'>" .
    "</td>";
    //Round 1 side, opponent, and round 2 side
    if ($round1IsPlaintiff) {
        echo "<td>".
                "<select><option selected>π</option><option>∆</option></select>".
                "</td><td>vs.</td>";
        echo "<td>" .
        "<input type=text pattern='[0-9]{4}' value=$round1Opponent size='4' maxlength='4'>" .
        "</td>";
        echo "<td>".
                "<select><option>π</option><option selected>∆</option></select>".
                "</td><td>vs.</td>";
    } else {
        echo "<td>".
                "<select><option>π</option><option selected>∆</option></select>".
                "</td><td>vs.</td>";
        echo "<td>" .
        "<input type=text pattern='[0-9]{4}' value=$round1Opponent size='4' maxlength='4'>" .
        "</td>";
        echo "<td>".
                "<select><option selected>π</option><option>∆</option></select>".
                "</td><td>vs.</td>";
    }
    //Round 2 opponent
    echo "<td>" .
    "<input type=text pattern='[0-9]{4}' value=$round2Opponent size='4' maxlength='4'>" .
    "</td>";
    //ROund 3 side, opponent, and round 4 side
    if ($round3IsPlaintiff) {
        echo "<td>π</td><td>vs.</td>";
        echo "<td>" .
        "<input type=text pattern='[0-9]{4}' value=$round3Opponent size='4' maxlength='4'>" .
        "</td>";
        echo "<td>∆</td><td>vs.</td>";
    } else {
        echo "<td>∆</td><td>vs.</td>";
        echo "<td>" .
        "<input type=text pattern='[0-9]{4}' value=$round3Opponent size='4' maxlength='4'>" .
        "</td>";
        echo "<td>π</td><td>vs.</td>";
    }
    //Round 4 opponent
    echo "<td>" .
    "<input type=text pattern='[0-9]{4}' value=$round4Opponent size='4' maxlength='4'>" .
    "</td>";
    //Record
    echo "<td>" . $record . "</td></tr>";
    //Team Name
    echo "<tr><td>" . $teamName . "</td>";
    //Round 1 Ballots
    echo "<td>" .
    "<input type=text pattern='[0-9]{2}' value=$round1Ballot1PD size='2' maxlength='2'>" .
    "</td><td></td><td>" .
    "<input type=text pattern='[0-9]{2}' value=$round1Ballot2PD size='2' maxlength='2'>" .
    "</td>";
    //Round 2 Ballots
    echo "<td>" .
    "<input type=text pattern='[0-9]{2}' value=$round2Ballot1PD size='2' maxlength='2'>" .
    "</td><td></td><td>" .
    "<input type=text pattern='[0-9]{2}' value=$round2Ballot2PD size='2' maxlength='2'>" .
    "</td>";
    //Round 3 Ballots
    echo "<td>" .
    "<input type=text pattern='[0-9]{2}' value=$round3Ballot1PD size='2' maxlength='2'>" .
    "</td><td></td><td>" .
    "<input type=text pattern='[0-9]{2}' value=$round3Ballot2PD size='2' maxlength='2'>" .
    "</td>";
    //Round 4 Ballots
    echo "<td>" .
    "<input type=text pattern='[0-9]{2}' value=$round4Ballot1PD size='2' maxlength='2'>" .
    "</td><td></td><td>" .
    "<input type=text pattern='[0-9]{2}' value=$round4Ballot2PD size='2' maxlength='2'>" .
    "</td>";
    //CS, OCS, PD
    echo "<td>CS: " . $CS . " OCS: " . $OCS . " PD: " . $PD . "</td></tr>";
    echo "\n";
}

echo "\n</body>\n</html>";
?>