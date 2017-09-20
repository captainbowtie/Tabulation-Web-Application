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
require_once "setSessionPrivileges.php";
require_once "functions.php";

if (!isset($_SESSION['id'])) {
    echo "You must be logged in to view this page";
//Web page if logged in as student
} else if (!$isTab && !$isCoach) {
    echo "You do not have permission to access this page";
//Code if logged in as tabulation director or coach
} else if ($isTab || $isCoach) {
    //Check if a team number was sent
    if (!isset($_GET["teamNumber"])) {
        echo "Error, no team number specified";
    } else {
        //Get team name and number
        $teamNumber = $_GET["teamNumber"];
        $teamQuery = "SELECT name FROM teams WHERE number = $teamNumber";
        $connection = new mysqli(dbhost, dbuser, dbpass, dbname);
        $nameResult = $connection->query($teamQuery);
        $nameResult->data_seek(0);
        $name = $nameResult->fetch_array(MYSQLI_NUM);
        $teamName = $name[0];
        $schoolConflicts = getSchoolConflicts($teamNumber);
        $competitorQuery = "SELECT * FROM competitors WHERE team=$teamNumber";
        $competitorResult = $connection->query($competitorQuery);
        $competitors = array();
        for ($a = 0; $a < $competitorResult->num_rows; $a++) {
            $competitorResult->data_seek($a);
            $competitor = $competitorResult->fetch_array(MYSQLI_ASSOC);
            $competitors[$a]["id"] = $competitor["id"];
            $competitors[$a]["name"] = $competitor["name"];
            $competitors[$a]["pAtty"] = $competitor["pAtty"];
            $competitors[$a]["pWit"] = $competitor["pWit"];
            $competitors[$a]["dAtty"] = $competitor["dAtty"];
            $competitors[$a]["dWit"] = $competitor["dWit"]; 
        }
        
        $arr = array('teamNumber' => $teamNumber,
            'teamName' => $teamName,
            'schoolConflicts' => $schoolConflicts,
            'competitors' => $competitors
        );
        //Return json data
        header('Content-Type: application/json');
        echo json_encode($arr);
    }
}
