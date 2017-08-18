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

//Require database login credentials
require_once 'login.php';

//Connect to database
//TODO: Do better error handling
$connection = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
if ($connection->connect_error) {
    die($connection->connect_error);
}

//Check if tables needed already exist in database
$query = "SHOW TABLES LIKE 'teams'";
$result = mysql_query($query);
$teamsTableExists = mysql_num_rows($result) > 0;
$query = "SHOW TABLES LIKE 'users'";
$result = mysql_query($query);
$usersTableExists = mysql_num_rows($result) > 0;
$query = "SHOW TABLES LIKE 'rooms'";
$result = mysql_query($query);
$roomsTableExists = mysql_num_rows($result) > 0;
$query = "SHOW TABLES LIKE 'ballots'";
$result = mysql_query($query);
$ballotsTableExists = mysql_num_rows($result) > 0;
$query = "SHOW TABLES LIKE 'competitors'";
$result = mysql_query($query);
$competitorsTableExists = mysql_num_rows($result) > 0;

//If any table needed already exists, require Tabulation director login to clear data
if (teamsTableExists || usersTableExists || roomsTableExists || competitorsTableExists || ballotsTableExists) {
    session_start();
    if (isset($_SESSION['userID'])) {
        if(usersTableExists){
            $query = "SELECT isJudge FROM USERS WHERE id=".$SESSION['userID']."\"";
            $result = mysql_query($query);
            //IF user is a tabulation director, confirm deletion
            if($result == 1){
                //TODO: add confirmation code (requires AJAX)
            }
        }
    } else {
        echo<<<_END
        <html>
        <head><title>Database Setup</title></head>
        <body>
        You must be logged in as a tabulation director to access this page.
        </body>
        </html>
_END;
    }
} else {
    if (teamsTableExists) {
        $query = "DROP TABLE teams";
        mysql_query($query);
    }
    if (usersTableExists) {
        $query = "DROP TABLE users";
        mysql_query($query);
    }
    if (roomsTableExists) {
        $query = "DROP TABLE rooms";
        mysql_query($query);
    }
    if (competitorsTableExists) {
        $query = "DROP TABLE competitors";
        mysql_query($query);
    }
    if (ballotsTableExists) {
        $query = "DROP TABLE ballots";
        mysql_query($query);
    }
    createTables();
}

//Query to drop any pre-existing tables with the same names as will be used
$initialDrop = "DROP TABLE teams; DROP TABLE competitors; DROP TABLE users;"
        . "DROP TABLE ballots; DROP TABLE rooms;";

function createTables() {
    /* Query to create the tables needed for the application
     * This string creates five tables: a teams, competitors, rooms, users, and ballots
     * table.
     */
    $createTables = "CREATE TABLE teams(number SMALLINT UNSIGNED, " //Teams Table
            . "teamName VARCHAR(64), PRIMARY KEY (number)) ENGINE InnoDB;"
            . "CREATE TABLE competitors(id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT KEY," //Competitors Table
            . " teamNumber SMALLINT UNSIGNED, name VARCHAR(64), INDEX(teamNumber)) "
            . "ENGINE InnoDB;"
            . "CREATE TABLE rooms(id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT KEY, " //Rooms Table
            . "building VARCHAR(64), number SMALL INT UNSIGNED, "
            . "availableRound1 BINARY(1), availableRound2 BINARY(1), "
            . "availableRound3 BINARY(1), availableRound4 BINARY (4)) ENGINE InnoDB;"
            . "CREATE TABLE users(id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT KEY, " //Users Table
            . "name VARCHAR(64), email VARCHAR(64), password CHAR(128), "
            . "isJudge BINARY(1), isCoach BINARY(1), isTab BINARY(1), "
            . "canJudgeRound1 BINARY(1), canJudgeRound2 BINARY(1), "
            . "canJudgeRound3 BINARY(1), canJudgeRound4 BINARY(1)) ENGINE InnoDB;"
            . "CREATE TABLE ballots(id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT KEY, " //Ballots Table
            . "plaintiffTeamNumber SMALLINT UNSIGNED, defenseTeamNumber SMALLINT UNSIGNED, "
            . "roundNumber TINYINT UNSIGNED, judgeID SMALLINT UNSIGNED, roomID SMALLINT UNSIGNED, "
            . "pOpen TINYINT UNSIGNED, pDirect1 TINYINT UNSIGNED, "
            . "pWitDirect1 TINYINT UNSIGNED, pWitCross1 TINYINT UNSIGNED, "
            . "pDirect2 TINYINT UNSIGNED, pWitDirect2 TINYINT UNSIGNED, "
            . "pWitCross2 TINYINT UNSIGNED, pDirect3 TINYINT UNSIGNED, "
            . "pWitDirect3 TINYINT UNSIGNED, pWitCross3 TINYINT UNSIGNED, "
            . "pCross1 TINYINT UNSIGNED, pCross2 TINYINT UNSIGNED, pCross3 TINYINT UNSINGED, "
            . "pClosing TINYINT UNSIGNED"
            . "dOpen TINYINT UNSIGNED, dDirect1 TINYINT UNSIGNED, "
            . "dWitDirect1 TINYINT UNSIGNED, dWitCross1 TINYINT UNSIGNED, "
            . "dDirect2 TINYINT UNSIGNED, dWitDirect2 TINYINT UNSIGNED, "
            . "dWitCross2 TINYINT UNSIGNED, dDirect3 TINYINT UNSIGNED, "
            . "dWitDirect3 TINYINT UNSIGNED, dWitCross3 TINYINT UNSIGNED, "
            . "dCross1 TINYINT UNSIGNED, dCross2 TINYINT UNSIGNED, dCross3 TINYINT UNSINGED, "
            . "dClosing TINYINT UNSIGNED, "
            . "attyRank1 SMALLINT UNSIGNED, attyRank2 SMALLINT UNSIGNED, "
            . "attyRank3 SMALLINT UNSIGNED, attyRank4 SMALLINT UNSIGNED, "
            . "attyRank5 SMALLINT UNSIGNED, attyRank6 SMALLINT UNSIGNED, "
            . "witRank1 SMALLINT UNSIGNED, witRank2 SMALLINT UNSIGNED, "
            . "witRank3 SMALLINT UNSIGNED, witRank4 SMALLINT UNSIGNED, "
            . "witRank5 SMALLINT UNSIGNED, witRank6 SMALLINT UNSIGNED, "
            . "INDEX(plaintiffTeamNumber), INDEX(defenseTeamNumber), "
            . "INDEX(roundNumber), INDEX(judgeNumber), INDEX(attyRank1), "
            . "INDEX(attyRank2), INDEX(attyRank3), INDEX(attyRank4), INDEX(attyRank5), "
            . "INDEX(attyRank6), INDEX(witRank1), INDEX(witRank2), INDEX(witRank3), "
            . "INDEX(witRank4), INDEX(witRank5), INDEX(witRank6)) ENGINE InnoDB;";

    mysql_query($createTables);
}

?>