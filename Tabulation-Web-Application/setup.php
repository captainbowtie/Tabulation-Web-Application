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
require_once 'dblogin.php';
//Require common PHP functions
require_once 'functions.php';

session_start();
echo<<<_END
<!DOCTYPE html>
<head>
<title>Database Setup</title>
<script src='functions.js'></script>
</head>
<body>
_END;
require_once 'headerMenu.php';

//Connect to database
//TODO: Do better error handling
$connection = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
if ($connection->connect_error) {
    die($connection->connect_error);
}

//Check if tables needed already exist in database
$query = "SHOW TABLES LIKE 'teams'";
$result = $connection->query($query);
global $teamsTableExists;
$teamsTableExists = $result->num_rows > 0;
$query = "SHOW TABLES LIKE 'users'";
$result = $connection->query($query);
global $usersTableExists;
$usersTableExists = $result->num_rows > 0;
$query = "SHOW TABLES LIKE 'rooms'";
$result = $connection->query($query);
global $roomsTableExists;
$roomsTableExists = $result->num_rows > 0;
$query = "SHOW TABLES LIKE 'ballots'";
$result = $connection->query($query);
global $ballotsTableExists;
$ballotsTableExists = $result->num_rows > 0;
$query = "SHOW TABLES LIKE 'competitors'";
$result = $connection->query($query);
global $competitorsTableExists;
$competitorsTableExists = $result->num_rows > 0;

//If any table needed already exists, require Tabulation director login to clear data
if ($teamsTableExists || $usersTableExists || $roomsTableExists || $competitorsTableExists || $ballotsTableExists) {
    if (isset($_SESSION['userID'])) {
        if ($usersTableExists) {
            $query = "SELECT isJudge FROM USERS WHERE id=" . $SESSION['userID'] . "\"";
            $result = $connection->query($query);
            //IF user is a tabulation director, confirm deletion
            if ($result == 1) {
                //TODO: add confirmation code (requires AJAX)
                echo<<<_END
                <div>Are you sure you want to clear the database?</div>
                <form method="post" action="index.php">
                <input type="submit" value="No"></form>
                <form method="post" action="setup.php">
                <input type="hidden" name="deleteTables" value="yes">
                <input type="submit" value="Yes"></form>
_END;
                if (isset($_POST['deleteTables']) && sanitize_string($_POST['deleteTables']) == 'yes') {
                    echo "Deleting tables and creating new admin user with "
                    . "email: example@example.com and password: password";
                    createTables();
                }
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
    createTables();
}

function createTables() {
    global $connection;
    // Drop any tables that already exist
    if ($teamsTableExists) {
        $query = "DROP TABLE teams";
        $connection->query($query);
    }
    if ($usersTableExists) {
        $query = "DROP TABLE users";
        $connection->query($query);
    }
    if ($roomsTableExists) {
        $query = "DROP TABLE rooms";
        $connection->query($query);
    }
    if ($competitorsTableExists) {
        $query = "DROP TABLE competitors";
        $connection->query($query);
    }
    if ($ballotsTableExists) {
        $query = "DROP TABLE ballots";
        $connection->query($query);
    }
    /* Query to create the tables needed for the application
     * This string creates five tables: a teams, competitors, rooms, users, and ballots
     * table.
     */
    $teamsTable = "CREATE TABLE teams(number SMALLINT UNSIGNED, " //Teams Table
            . "teamName VARCHAR(64), PRIMARY KEY (number)) ENGINE InnoDB";
    $result = $connection->query($teamsTable);
    $competitorsTable = "CREATE TABLE competitors(id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT KEY, " //Competitors Table
            . " teamNumber SMALLINT UNSIGNED, name VARCHAR(64), INDEX(teamNumber)) "
            . "ENGINE InnoDB";
    $result = $connection->query($competitorsTable);
    $roomsTable = "CREATE TABLE rooms(id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT KEY, " //Rooms Table
            . "building VARCHAR(64), number SMALLINT UNSIGNED, "
            . "availableRound1 BINARY(1), availableRound2 BINARY(1), "
            . "availableRound3 BINARY(1), availableRound4 BINARY (4)) ENGINE InnoDB";
    $result = $connection->query($roomsTable);
    $usersTable = "CREATE TABLE users(id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT KEY, " //Users Table
            . "name VARCHAR(64), email VARCHAR(64), password CHAR(128), "
            . "isJudge BINARY(1), isCoach BINARY(1), isTab BINARY(1), "
            . "canJudgeRound1 BINARY(1), canJudgeRound2 BINARY(1), "
            . "canJudgeRound3 BINARY(1), canJudgeRound4 BINARY(1)) ENGINE InnoDB";
    $result = $connection->query($usersTable);
    $ballotsTable = "CREATE TABLE ballots(id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT KEY, " //Ballots Table
            . "pTeamNumber SMALLINT UNSIGNED, dTeamNumber SMALLINT UNSIGNED, "
            . "roundNumber TINYINT UNSIGNED, judgeId SMALLINT UNSIGNED, roomID SMALLINT UNSIGNED, "
            . "pOpen TINYINT UNSIGNED, pDirect1 TINYINT UNSIGNED, "
            . "pWitDirect1 TINYINT UNSIGNED, pWitCross1 TINYINT UNSIGNED, "
            . "pDirect2 TINYINT UNSIGNED, pWitDirect2 TINYINT UNSIGNED, "
            . "pWitCross2 TINYINT UNSIGNED, pDirect3 TINYINT UNSIGNED, "
            . "pWitDirect3 TINYINT UNSIGNED, pWitCross3 TINYINT UNSIGNED, "
            . "pCross1 TINYINT UNSIGNED, pCross2 TINYINT UNSIGNED, pCross3 TINYINT UNSIGNED, "
            . "pClosing TINYINT UNSIGNED, "
            . "dOpen TINYINT UNSIGNED, dDirect1 TINYINT UNSIGNED, "
            . "dWitDirect1 TINYINT UNSIGNED, dWitCross1 TINYINT UNSIGNED, "
            . "dDirect2 TINYINT UNSIGNED, dWitDirect2 TINYINT UNSIGNED, "
            . "dWitCross2 TINYINT UNSIGNED, dDirect3 TINYINT UNSIGNED, "
            . "dWitDirect3 TINYINT UNSIGNED, dWitCross3 TINYINT UNSIGNED, "
            . "dCross1 TINYINT UNSIGNED, dCross2 TINYINT UNSIGNED, dCross3 TINYINT UNSIGNED, "
            . "dClosing TINYINT UNSIGNED, "
            . "attyRank1 SMALLINT UNSIGNED, attyRank2 SMALLINT UNSIGNED, "
            . "attyRank3 SMALLINT UNSIGNED, attyRank4 SMALLINT UNSIGNED, "
            . "attyRank5 SMALLINT UNSIGNED, attyRank6 SMALLINT UNSIGNED, "
            . "witRank1 SMALLINT UNSIGNED, witRank2 SMALLINT UNSIGNED, "
            . "witRank3 SMALLINT UNSIGNED, witRank4 SMALLINT UNSIGNED, "
            . "witRank5 SMALLINT UNSIGNED, witRank6 SMALLINT UNSIGNED, "
            . "INDEX(pTeamNumber), INDEX(dTeamNumber), "
            . "INDEX(roundNumber), INDEX(judgeId), INDEX(attyRank1), "
            . "INDEX(attyRank2), INDEX(attyRank3), INDEX(attyRank4), INDEX(attyRank5), "
            . "INDEX(attyRank6), INDEX(witRank1), INDEX(witRank2), INDEX(witRank3), "
            . "INDEX(witRank4), INDEX(witRank5), INDEX(witRank6)) ENGINE InnoDB";
    $result = $connection->query($ballotsTable);
    $teamConflictsTable = "CREATE TABLE teamConflicts(id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT KEY, "
            . "team1 SMALLINT UNSIGNED, team2 SMALLINT UNSIGNED, "
            . "INDEX(team1), INDEX(team2)) ENGINE InnoDB";
    $result = $connnection->query($teamConflictsTable);
    $judgeConflictsTable = "CREATE TABLE judgeConflicts(id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT KEY, "
            . "judgeID SMALLINT UNSIGNED, team SMALLINT UNSIGNED, "
            . "INDEX(judgeID), INDEX(team)) ENGINE InnoDB";
    $result = $connnection->query($judgeConflictsTable);

    //TODO: And code to specific a default tabulation director's email and password
    //TODO: Generate a new password salt and save it on the server
    $generateAdmin = "INSERT INTO users(name, email, password, isTab) "
            . "VALUES('Tabulation Director', 'example@example.com', "
            . "'74dfc2b27acfa364da55f93a5caee29ccad3557247eda238831b3e9bd931b01d77fe994e4f12b9d4cfa92a124461d2065197d8cf7f33fc88566da2db2a4d6eae', " //Whirlpool hash for 'password'
            . "'1')";
    $result = $connection->query($generateAdmin);
}

echo "</body></html>"
?>