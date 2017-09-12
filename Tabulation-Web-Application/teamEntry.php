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

session_start();

require_once "dblogin.php";
require_once 'setSessionPrivileges.php';

//HTML Header information
echo<<<_END
<!DOCTYPE HTML>
<html>
        <head>
            <title>Team Entry</title>
                </head>
        <body>
_END;
require_once "header.php";

if ($isTab || $isCoach) {
    //Get list of teams
    if ($isTab) {
        //Add team controls here
        $teamQuery = "SELECT * FROM teams";
    } else {
        $teamQuery = "SELECT * FROM teams WHERE coachId=$id";
    }
    $connection = new mysqli(dbhost, dbuser, dbpass, dbname);
    $teamResult = $connection->query($teamQuery);
    //Combine teams in to HTML string of select options
    $options = "";
    for ($a = 0; $a < $teamResult->num_rows; $a++) {
        $teamResult->data_seek($a);
        $team = $teamResult->fetch_array(MYSQLI_ASSOC);
        $teamNumber = $team['number'];
        $teamName = $team['name'];
        $options .= "<option id='$teamNumber'";
        if ($a == 0) {
            $options .= " selected";
            $firstTeamNumber = $teamNumber;
            $firstTeamName = $teamName;
        }
        $options .= ">$teamNumber: $teamName</option>\n";
    }
    //Print the select and a header about the currently selected team
    echo "<select id='teamSelect'>\n$options</select><br>\n";
    echo "<span id='teamNumber'>$firstTeamNumber</span>\n";
    echo "<span id='teamName'>$firstTeamName</span>";
    //Get data on selected team's competitors
    $competitorQuery = "SELECT * FROM competitors WHERE team=$firstTeamNumber";
    $competitorResult = $connection->query($competitorQuery);
    $competitorRows = "";
    for ($a = 0; $a < $competitorResult->num_rows; $a++) {
        $competitorResult->data_seek($a);
        $competitor = $competitorResult->fetch_array(MYSQLI_ASSOC);
        $id = $competitor['id'];
        $name = $competitor['name'];
        $pAtty = $competitor['pAtty'];
        $pWit = $competitor['pWit'];
        $dAtty = $competitor['dAtty'];
        $dWit = $competitor['dWit'];
        echo "<script>console.log('$name $pAtty $pWit $dAtty $dWit')</script>";
        //Create competitor row, checking for each role if that box should be checked
        $competitorRows .= "<tr><td><input role='name' competitor='$id' value='$name' class='existingUserName'></td>"
                . "<td><label><input type=checkbox role='pAtty' competitor='$id' class='existingUserRole'";
        if($pAtty==1){
            $competitorRows .= " checked";
        }
        $competitorRows .= ">Plaintiff Attorney</label></td>"
                . "<td><label><input type=checkbox role='pWit' competitor='$id' class='existingUserRole'";
        if($pWit==1){
            $competitorRows .= " checked";
        }
        $competitorRows .= ">Plaintiff Witness</label></td>"
                . "<td><label><input type=checkbox role='dAtty' competitor='$id' class='existingUserRole'";
        if($dAtty==1){
            $competitorRows .= " checked";
        }
        $competitorRows .= ">Defense Attorney</label></td>"
                . "<td><label><input type=checkbox role='dWit' competitor='$id' class='existingUserRole'";
        if($dWit==1){
            $competitorRows .= " checked";
        }
        $competitorRows .= ">Defense Witness</label></td></tr>\n";
    }
    //Create list of existing competitors
    echo "<form id='competitorForm'>\n";
    echo "<table id='competitorTable'>\n";
    echo $competitorRows;
    echo "</table>";
    echo "</form>\n";
    //Form to add more competitors to team
    echo "<form id='addCompetitor'>\n";
    echo "<input id='name' name='name'>"
    . "<label><input type=checkbox name ='pAtty' id='pAtty'>Plaintiff Attorney</label>"
    . "<label><input type=checkbox name='pWit' id='pWit'>Plaintiff Witness</label>"
    . "<label><input type=checkbox name='dAtty' id='dAtty'>Defense Attorney</label>"
    . "<label><input type=checkbox name='dWit' id='dWit'>Defense Witness</label>"
    . "<input type=submit id='addCompetitorButton' name='addCompetitorButton' value='Add Competitor'>";
    echo "</form>\n";
} else if (isset($_POST['id'])) {
    echo "You do not have permission to access this page";
} else {
    echo "You must be logged in to view this page";
}


echo "<script src='https://code.jquery.com/jquery-3.2.1.min.js'></script>";
echo "<script src='/teamEntry.js'></script>";
echo "</body>\n";
echo "</html>\n";

function createBody($id) {
    if ($id == 0) {  //code if logged in as tab
        //Create comboBox to select team for data entry
        $selectQuery = "SELECT * FROM teams";
        $connection = new mysqli(dbhost, dbuser, dbpass, dbname);
        $selectResult = $connection->query($selectQuery);

        $connection->close();
        echo "<option id=teamSelector>\n";
        for ($a = 0; $a < $selectResult->num_rows; $a++) {
            $selectResult->data_seek($a);
            $teamRow = $selectResult->fetch_array(MYSQLI_ASSOC);
            $teamNumber = $teamRow['number'];
            $teamName = $teamRow['name'];
            echo "<select value='$teamNumber'";
            if ($a == 0) {
                echo "selected";
            }
            echo ">$teamNumber: $teamName</select>\n";
        }
        echo "</option>";
    } else {  //code if logged in as coach
        //echo $id;
    }
}
