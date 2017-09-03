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

//TODO: add admin for coach and judge

session_start();

require_once "dblogin.php";
require_once 'setSessionPrivileges.php';


//HTML Header information
echo<<<_END
<!DOCTYPE HTML>
<html>
        <head>
            <title>Ballot</title>
                </head>
        <body>
_END;

require_once "header.php";

if (!isset($_SESSION['id'])) {
    echo "You must be logged in to view this page";
//Web page if logged in, but not as a coach or tabulation director
} else if (!$isTab && !$isJudge && !$isCoach) {
    echo "You do not have permission to access this page";
//Code if logged in as tabulation director
} else if ($isTab) {
    
} else if ($isCoach && $isJudge) {
    
} else if ($isJudge) {
    //TODO: Submit name with the index of rankings, to verify no funny business on client side
    //Alternatively, send the dropdown index, and then on the server reconstruct the dropdown to see who that index must point to
    //TODO: Check if ballot is finalized
    $fh = fopen("currentRound.txt", 'r');
    $currentRound = fgets($fh);
    fclose($fh);
    $judgeId = $_SESSION['id'];
    $connection = new mysqli(dbhost, dbuser, dbpass, dbname);
    $query = "SELECT * FROM ballots WHERE judgeId=$judgeId && round=$currentRound";
    $ballotResult = $connection->query($query);
    $ballotResult->data_seek(0);
    $ballot = $ballotResult->fetch_array(MYSQLI_ASSOC);
    $connection->close();
    if ($ballot['finalized'] == 0) {
        createJudgeBallot($ballot);
    } else {
        echo "Your ballot has already been sumbitted for the current round";
    }
} else if ($isCoach) {
    
}


echo "<script src='https://code.jquery.com/jquery-3.2.1.min.js'></script>";
echo "<script src='/ballot.js'></script>";
echo "</body>\n";
echo "</html>\n";

function createJudgeBallot($ballot) {
    //Pull data from ballot necessary to fetch remaining data from db
    $pTeamNumber = $ballot['pTeamNumber'];
    $dTeamNumber = $ballot['dTeamNumber'];
    $roomId = $ballot['roomId'];
    $judgeId = $ballot['judgeId'];

    //Fetch data from db
    $connection = new mysqli(dbhost, dbuser, dbpass, dbname);

    //Get the names of team members and create HTML string for combo boxes
    $comboBoxOptionHTML = "<option value='0'>N/A</option>\n";
    $pTeamQuery = "SELECT id,name FROM competitors WHERE team=$pTeamNumber";
    $pResult = $connection->query($pTeamQuery);
    for ($a = 0; $a < $pResult->num_rows; $a++) {
        $pResult->data_seek($a);
        $pCompetitors = $pResult->fetch_array(MYSQLI_ASSOC);
        $value = $pCompetitors['id'];
        $name = $pCompetitors['name'];
        $comboBoxOptionHTML .= "<option value='$value'>$name</option>\n";
    }
    //Get the names of the defense team members
    $dTeamQuery = "SELECT id,name FROM competitors WHERE team=$dTeamNumber";
    $dResult = $connection->query($dTeamQuery);
    for ($a = 0; $a < $dResult->num_rows; $a++) {
        $dResult->data_seek($a);
        $dCompetitors = $dResult->fetch_array(MYSQLI_ASSOC);
        $value = $dCompetitors['id'];
        $name = $dCompetitors['name'];
        $comboBoxOptionHTML .= "<option value='$value'>$name</option>\n";
    }

    //Get the building name and room number
    $roomQuery = "SELECT building,number FROM rooms WHERE id=$roomId";
    $roomResult = $connection->query($roomQuery);
    $roomResult->data_seek(0);
    $room = $roomResult->fetch_array(MYSQLI_ASSOC);

    //Get the judge's name
    $judgeQuery = "SELECT name FROM users WHERE id=$judgeId";
    $judgeResult = $connection->query($judgeQuery);
    $judgeResult->data_seek(0);
    $judge = $judgeResult->fetch_array(MYSQLI_ASSOC);
    $connection->close();

    //Assign initial values to variables
    $round = $ballot['round'];
    $judgeName = $judge['name'];
    $roomNumber = $room['building'] . " " . $room['number'];
    $pOpen = $ballot['pOpen'];
    $pDirect1 = $ballot['pDirect1'];
    $pWitDirect1 = $ballot['pWitDirect1'];
    $pWitCross1 = $ballot['pWitCross1'];
    $pDirect2 = $ballot['pDirect2'];
    $pWitDirect2 = $ballot['pWitDirect2'];
    $pWitCross2 = $ballot['pWitCross2'];
    $pDirect3 = $ballot['pDirect3'];
    $pWitDirect3 = $ballot['pWitDirect3'];
    $pWitCross3 = $ballot['pWitCross3'];
    $pCross1 = $ballot['pCross1'];
    $pCross2 = $ballot['pCross2'];
    $pCross3 = $ballot['pCross3'];
    $pClose = $ballot['pClose'];
    $dOpen = $ballot['dOpen'];
    $dDirect1 = $ballot['dDirect1'];
    $dWitDirect1 = $ballot['dWitDirect1'];
    $dWitCross1 = $ballot['dWitCross1'];
    $dDirect2 = $ballot['dDirect2'];
    $dWitDirect2 = $ballot['dWitDirect2'];
    $dWitCross2 = $ballot['dWitCross2'];
    $dDirect3 = $ballot['dDirect3'];
    $dWitDirect3 = $ballot['dWitDirect3'];
    $dWitCross3 = $ballot['dWitCross3'];
    $dCross1 = $ballot['dCross1'];
    $dCross2 = $ballot['dCross2'];
    $dCross3 = $ballot['dCross3'];
    $dClose = $ballot['dClose'];

    //Set the attorney and witness combo boxes to the correct individual
    $attyRank1Id = $ballot['attyRank1'];
    $attyRank1Search = "'$attyRank1Id'";
    $attyRank1Replace = "'$attyRank1Id' selected";
    $attyRank1 = str_replace($attyRank1Search, $attyRank1Replace, $comboBoxOptionHTML);
    $attyRank2Id = $ballot['attyRank2'];
    $attyRank2Search = "'$attyRank2Id'";
    $attyRank2Replace = "'$attyRank2Id' selected";
    $attyRank2 = str_replace($attyRank2Search, $attyRank2Replace, $comboBoxOptionHTML);
    $attyRank3Id = $ballot['attyRank3'];
    $attyRank3Search = "'$attyRank3Id'";
    $attyRank3Replace = "'$attyRank3Id' selected";
    $attyRank3 = str_replace($attyRank3Search, $attyRank3Replace, $comboBoxOptionHTML);
    $attyRank4Id = $ballot['attyRank4'];
    $attyRank4Search = "'$attyRank4Id'";
    $attyRank4Replace = "'$attyRank4Id' selected";
    $attyRank4 = str_replace($attyRank4Search, $attyRank4Replace, $comboBoxOptionHTML);
    $attyRank5Id = $ballot['attyRank5'];
    $attyRank5Search = "'$attyRank5Id'";
    $attyRank5Replace = "'$attyRank5Id' selected";
    $attyRank5 = str_replace($attyRank5Search, $attyRank5Replace, $comboBoxOptionHTML);
    $attyRank6Id = $ballot['attyRank6'];
    $attyRank6Search = "'$attyRank6Id'";
    $attyRank6Replace = "'$attyRank6Id' selected";
    $attyRank6 = str_replace($attyRank6Search, $attyRank6Replace, $comboBoxOptionHTML);
    $witRank1Id = $ballot['witRank1'];
    $witRank1Search = "'$witRank1Id'";
    $witRank1Replace = "'$witRank1Id' selected";
    $witRank1 = str_replace($witRank1Search, $witRank1Replace, $comboBoxOptionHTML);
    $witRank2Id = $ballot['witRank2'];
    $witRank2Search = "'$witRank2Id'";
    $witRank2Replace = "'$witRank2Id' selected";
    $witRank2 = str_replace($witRank2Search, $witRank2Replace, $comboBoxOptionHTML);
    $witRank3Id = $ballot['witRank3'];
    $witRank3Search = "'$witRank3Id'";
    $witRank3Replace = "'$witRank3Id' selected";
    $witRank3 = str_replace($witRank3Search, $witRank3Replace, $comboBoxOptionHTML);
    $witRank4Id = $ballot['witRank4'];
    $witRank4Search = "'$witRank4Id'";
    $witRank4Replace = "'$witRank4Id' selected";
    $witRank4 = str_replace($witRank4Search, $witRank4Replace, $comboBoxOptionHTML);
    $witRank5Id = $ballot['witRank5'];
    $witRank5Search = "'$witRank5Id'";
    $witRank5Replace = "'$witRank5Id' selected";
    $witRank5 = str_replace($witRank5Search, $witRank5Replace, $comboBoxOptionHTML);
    $witRank6Id = $ballot['witRank6'];
    $witRank6Search = "'$witRank6Id'";
    $witRank6Replace = "'$witRank6Id' selected";
    $witRank6 = str_replace($witRank6Search, $witRank6Replace, $comboBoxOptionHTML);
    
    //Create ballot header HTML
    echo "<span id='pTeam'>$pTeamNumber</span> vs. <span id='dTeam'>$dTeamNumber</span><br>\n";
    echo "$roomNumber, Round <span id='round'>$round</span>: <span id='judge' judgeId='$judgeId'>$judgeName</span><br>\n";

    //Create scores section of ballot
    echo "<form name='form' id='form'>\n";
    echo "<table>\n";
    echo "<tr><td>PLAINTIFF</td><td>DEFENSE</td></tr>\n";
    //TODO: make all inputs numbers, limit to 2 digits, set default values
    echo "<tr>";
    echo "<td><label>Opening statement: <input type=number min='0' step='1' max='10' size='2' id='pOpen' name='pOpen' value='$pOpen'></label></td>";
    echo "<td><label>Opening statement: <input type=number min='0' step='1' max='10' size='2' id='dOpen' name='dOpen' value='$dOpen'></label></td>";
    echo "</tr>\n";
    echo "<tr><td>PL. CASE-IN-CHIEF</td><td></td></tr>\n";
    echo "<tr>";
    echo "<td><label>Direct exam of π #1: <input type=number min='0' step='1' max='10' size='2' id='pDirect1' name='pDirect1' value='$pDirect1'></label></td>";
    echo "<td><label>Cross exam of π #1: <input type=number min='0' step='1' max='10' size='2' id='dCross1' name='dCross1' value='$dCross1'></label></td>";
    echo "</tr>\n";
    echo "<tr>";
    echo "<td><label>Witness #1 direct: <input type=number min='0' step='1' max='10' size='2' id='pWitDirect1' name='pWitDirect1' value='$pWitDirect1'></label></td><td></td>";
    echo "</tr>\n";
    echo "<tr>";
    echo "<td><label>Witness #1 cross: <input type=number min='0' step='1' max='10' size='2' id='pWitCross1' name='pWitCross1' value='$pWitCross1'></label></td><td></td>";
    echo "</tr>\n";
    echo "<tr>";
    echo "<td><label>Direct exam of π #2: <input type=number min='0' step='1' max='10' size='2' id='pDirect2' name='pDirect2' value='$pDirect2'></label></td>";
    echo "<td><label>Cross exam of π #2: <input type=number min='0' step='1' max='10' size='2' id='dCross2' name='dCross2' value='$dCross2'></label></td>";
    echo "</tr>\n";
    echo "<tr>";
    echo "<td><label>Witness #2 direct: <input type=number min='0' step='1' max='10' size='2' id='pWitDirect2' name='pWitDirect2' value='$pWitDirect2'></label></td><td></td>";
    echo "</tr>\n";
    echo "<tr>";
    echo "<td><label>Witness #2 cross: <input type=number min='0' step='1' max='10' size='2' id='pWitCross2' name='pWitCross2' value='$pWitCross2'></label></td><td></td>";
    echo "</tr>\n";
    echo "<tr>";
    echo "<td><label>Direct exam of π #3: <input type=number min='0' step='1' max='10' size='2' id='pDirect3' name='pDirect3' value='$pDirect3'></label></td>";
    echo "<td><label>Cross exam of π #3: <input type=number min='0' step='1' max='10' size='2' id='dCross=3' name='dCross3' value='$dCross3'></label></td>";
    echo "</tr>\n";
    echo "<tr>";
    echo "<td><label>Witness #3 direct: <input type=number min='0' step='1' max='10' size='2' id='pWitDirect3' name='pWitDirect3' value='$pWitDirect3'></label></td><td></td>";
    echo "</tr>\n";
    echo "<tr>";
    echo "<td><label>Witness #3 cross: <input type=number min='0' step='1' max='10' size='2' id='pWitCross3' name='pWitCross3' value='$pWitCross3'></label></td><td></td>";
    echo "</tr>\n";
    echo "<tr><td></td><td>DEF. CASE-IN-CHIEF</td></tr>\n";
    echo "<tr>";
    echo "<td><label>Cross exam of ∆ #1: <input type=number min='0' step='1' max='10' size='2' id='pCross1' name='pCross1' value='$pCross1'></label></td>";
    echo "<td><label>Direct exam of ∆ #1: <input type=number min='0' step='1' max='10' size='2' id='dDirect1' name='dDirect1' value='$dDirect1'></label></td>";
    echo "</tr>\n";
    echo "<tr>";
    echo "<td></td><td><label>Witness #1 direct: <input type=number min='0' step='1' max='10' size='2' id='dWitDirect1' name='dWitDirect1' value='$dWitDirect1'></label></td>";
    echo "</tr\n>";
    echo "<tr>";
    echo "<td></td><td><label>Witness #1 cross: <input type=number min='0' step='1' max='10' size='2' id='dWitCross1' name='dWitCross1' value='$dWitCross1'></label></td>";
    echo "</tr>\n";
    echo "<tr>";
    echo "<td><label>Cross exam of ∆ #2: <input type=number min='0' step='1' max='10' size='2' id='pCross2' name='pCross2' value='$pCross2'></label></td>";
    echo "<td><label>Direct exam of ∆ #2: <input type=number min='0' step='1' max='10' size='2' id='dDirect2' name='dDirect2' value='$dDirect2'></label></td>";
    echo "</tr>\n";
    echo "<tr>";
    echo "<td></td><td><label>Witness #2 direct: <input type=number min='0' step='1' max='10' size='2' id='dWitDirect2' name='dWitDirect2' value='$dWitDirect2'></label></td>";
    echo "</tr\n>";
    echo "<tr>";
    echo "<td></td><td><label>Witness #2 cross: <input type=number min='0' step='1' max='10' size='2' id='dWitCross2' name='dWitCross2' value='$dWitCross2'></label></td>";
    echo "</tr>\n";
    echo "<tr>";
    echo "<td><label>Cross exam of ∆ #3: <input type=number min='0' step='1' max='10' size='2' id='pCross3' name='pCross3' value='$pCross3'></label></td>";
    echo "<td><label>Direct exam of ∆ #3: <input type=number min='0' step='1' max='10' size='2' id='dDirect3' name='dDirect3' value='$dDirect3'></label></td>";
    echo "</tr>\n";
    echo "<tr>";
    echo "<td></td><td><label>Witness #3 direct: <input type=number min='0' step='1' max='10' size='2' id='dWitDirect3' name='dWitDirect3' value='$dWitDirect3'></label></td>";
    echo "</tr\n>";
    echo "<tr>";
    echo "<td></td><td><label>Witness #3 cross: <input type=number min='0' step='1' max='10' size='2' id='dWitCross3' name='dWitCross3' value='$dWitCross3'></label></td>";
    echo "</tr>\n";
    echo "<tr>";
    echo "<td><label>Closing argument: <input type=number min='0' step='1' max='10' size='2' id='pClose' name='pClose' value='$pClose'></label></td>";
    echo "<td><label>Closing argument: <input type=number min='0' step='1' max='10' size='2' id='dClose' name='dClose' value='$dClose'></label></td>";
    echo "</tr>\n";
    echo "</table>\n";
    echo "OUTSTANDING ATTORNEYS AND WITNESSES\n";
    echo "<table>";
    echo "<tr><td>ATTORNEYS</td><td>WITNESSES</td></tr>\n";
    echo "<tr>\n";
    echo "<td><label>1: <select id='attyRank1'>\n$attyRank1</select></label>\n</td>\n";
    echo "<td><label>1: <select id='witRank1'>\n$witRank1</select></label>\n</td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo "<td><label>2: <select id='attyRank2'>\n$attyRank2</select></label>\n</td>\n";
    echo "<td><label>2: <select id='witRank2'>\n$witRank2</select></label>\n</td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo "<td><label>3: <select id='attyRank3'>\n$attyRank3</select></label>\n</td>\n";
    echo "<td><label>3: <select id='witRank3'>\n$witRank3</select></label>\n</td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo "<td><label>4: <select id='attyRank4'>\n$attyRank4</select></label>\n</td>\n";
    echo "<td><label>4: <select  id='witRank4'>\n$witRank4</select></label>\n</td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo "<td><label>5: <select id='attyRank5'>\n$attyRank5</select></label>\n</td>\n";
    echo "<td><label>5: <select  id='witRank5'>\n$witRank5</select></label>\n</td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo "<td><label>6: <select id='attyRank6'>\n$attyRank6</select></label>\n</td>\n";
    echo "<td><label>6: <select  id='witRank6'>\n$witRank6</select></label>\n</td>\n";
    echo "</tr>\n";
    echo "</table>";
    echo "</form>\n";
}
