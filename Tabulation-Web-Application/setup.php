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
</head>
<body>
_END;
require_once 'header.php';
//Connect to database
//TODO: Do better error handling
$connection = new mysqli(dbhost, dbuser, dbpass, dbname);
if ($connection->connect_error) {
    die($connection->connect_error);
}

//Check if tables needed already exist in database
$query = "SHOW TABLES LIKE 'teams'";
$result = $connection->query($query);
$teamsTableExists;
$teamsTableExists = $result->num_rows > 0;
$query = "SHOW TABLES LIKE 'users'";
$result = $connection->query($query);
$usersTableExists;
$usersTableExists = $result->num_rows > 0;
$query = "SHOW TABLES LIKE 'rooms'";
$result = $connection->query($query);
$roomsTableExists;
$roomsTableExists = $result->num_rows > 0;
$query = "SHOW TABLES LIKE 'ballots'";
$result = $connection->query($query);
$ballotsTableExists;
$ballotsTableExists = $result->num_rows > 0;
$query = "SHOW TABLES LIKE 'competitors'";
$result = $connection->query($query);
$competitorsTableExists;
$competitorsTableExists = $result->num_rows > 0;

//If any table needed already exists, require Tabulation director login to clear data
if ($teamsTableExists || $usersTableExists || $roomsTableExists || $competitorsTableExists || $ballotsTableExists) {
    if (isset($_SESSION['id'])) {
        if ($usersTableExists) {
            $query = 'SELECT isTab FROM users WHERE id="' . $_SESSION['id'] . '"';
            $result = $connection->query($query);
            //IF user is a tabulation director, confirm deletion
            if ($result == 1) {
                echo<<<_END
                <div>Are you sure you want to clear the database?
                    (This will delete all tournament data. A new admin user with 
                        default email: example@example.com and password: 
                            password will be created.)</div>
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
echo "\n</body>\n</html>";

function createTables() {
    global $connection;
    //Reset current round to 0 (pre-round 1)
    $fh = fopen("currentRound.txt", 'w') or die("Failed to create file");
    flock($fh, LOCK_EX);
    fwrite($fh, 0) or die("Could not write to file");
    flock($fh, LOCK_UN);
    fclose($fh);
    // Drop any tables that already exist
    //TODO: Add drops for other tables added to database since this funcgtion was written
    //TODO: Split rooms table into a building and a room table
    global $teamsTableExists;
    if ($teamsTableExists) {
        $query = "DROP TABLE teams";
        $connection->query($query);
    }
    global $usersTableExists;
    if ($usersTableExists) {
        $query = "DROP TABLE users";
        $connection->query($query);
    }
    global $roomsTableExists;
    if ($roomsTableExists) {
        $query = "DROP TABLE rooms";
        $connection->query($query);
    }
    global $competitorsTableExists;
    if ($competitorsTableExists) {
        $query = "DROP TABLE competitors";
        $connection->query($query);
    }
    global $ballotsTableExists;
    if ($ballotsTableExists) {
        $query = "DROP TABLE ballots";
        $connection->query($query);
    }
    /* Query to create the tables needed for the application
     * This string creates five tables: a teams, competitors, rooms, users, and ballots
     * table.
     */
    $teamsTable = "CREATE TABLE teams(number SMALLINT UNSIGNED, " //Teams Table
            . "name VARCHAR(64) NOT NULL, PRIMARY KEY (number)) ENGINE InnoDB";
    $connection->query($teamsTable);
    $competitorsTable = "CREATE TABLE competitors(id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT KEY, " //Competitors Table
            . "team SMALLINT UNSIGNED NOT NULL, name VARCHAR(64) NOT NULL, INDEX(team)) "
            . "ENGINE InnoDB";
    $connection->query($competitorsTable);
    $roomsTable = "CREATE TABLE rooms(id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT KEY, " //Rooms Table
            . "building VARCHAR(64) NOT NULL, number VARCHAR(64) NOT NULL, "
            . "availableRound1 BINARY(1) NOT NULL, availableRound2 BINARY(1) NOT NULL, "
            . "availableRound3 BINARY(1) NOT NULL, availableRound4 BINARY (4) NOT NULL) ENGINE InnoDB";
    $connection->query($roomsTable);
    $usersTable = "CREATE TABLE users(id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT KEY, " //Users Table
            . "name VARCHAR(64) NOT NULL DEFAULT 'Missing Name', email VARCHAR(64) NOT NULL DEFAULT 'example@example.com', password CHAR(128) NOT NULL DEFAULT '74dfc2b27acfa364da55f93a5caee29ccad3557247eda238831b3e9bd931b01d77fe994e4f12b9d4cfa92a124461d2065197d8cf7f33fc88566da2db2a4d6eae', "
            . "isJudge BINARY(1) NOT NULL DEFAULT '0', isCoach BINARY(1) NOT NULL DEFAULT '0', isTab BINARY(1) NOT NULL DEFAULT '0', "
            . "canJudgeRound1 BINARY(1) NOT NULL DEFAULT '0', canJudgeRound2 BINARY(1) NOT NULL DEFAULT '0', "
            . "canJudgeRound3 BINARY(1) NOT NULL DEFAULT '0', canJudgeRound4 BINARY(1) NOT NULL DEFAULT '0') ENGINE InnoDB";
    $connection->query($usersTable);
    $ballotsTable = "CREATE TABLE ballots(id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT KEY, " //Ballots Table
            . "pTeam SMALLINT UNSIGNED NOT NULL DEFAULT '0', dTeam SMALLINT UNSIGNED NOT NULL DEFAULT '0', "
            . "round TINYINT UNSIGNED NOT NULL DEFAULT '1', judgeId SMALLINT UNSIGNED NOT NULL DEFAULT '0', roomId SMALLINT UNSIGNED NOT NULL DEFAULT '0', "
            . "pOpen TINYINT UNSIGNED NOT NULL DEFAULT '0', pDirect1 TINYINT UNSIGNED NOT NULL DEFAULT '0', "
            . "pWitDirect1 TINYINT UNSIGNED NOT NULL DEFAULT '0', pWitCross1 TINYINT UNSIGNED NOT NULL DEFAULT '0', "
            . "pDirect2 TINYINT UNSIGNED NOT NULL DEFAULT '0', pWitDirect2 TINYINT UNSIGNED NOT NULL DEFAULT '0', "
            . "pWitCross2 TINYINT UNSIGNED NOT NULL DEFAULT '0', pDirect3 TINYINT UNSIGNED NOT NULL DEFAULT '0', "
            . "pWitDirect3 TINYINT UNSIGNED NOT NULL DEFAULT '0', pWitCross3 TINYINT UNSIGNED NOT NULL DEFAULT '0', "
            . "pCross1 TINYINT UNSIGNED NOT NULL DEFAULT '0', pCross2 TINYINT UNSIGNED NOT NULL DEFAULT '0', pCross3 TINYINT UNSIGNED NOT NULL DEFAULT '0', "
            . "pClose TINYINT UNSIGNED NOT NULL DEFAULT '0', "
            . "dOpen TINYINT UNSIGNED NOT NULL DEFAULT '0', dDirect1 TINYINT UNSIGNED NOT NULL DEFAULT '0', "
            . "dWitDirect1 TINYINT UNSIGNED NOT NULL DEFAULT '0', dWitCross1 TINYINT UNSIGNED NOT NULL DEFAULT '0', "
            . "dDirect2 TINYINT UNSIGNED NOT NULL DEFAULT '0', dWitDirect2 TINYINT UNSIGNED NOT NULL DEFAULT '0', "
            . "dWitCross2 TINYINT UNSIGNED NOT NULL DEFAULT '0', dDirect3 TINYINT UNSIGNED NOT NULL DEFAULT '0', "
            . "dWitDirect3 TINYINT UNSIGNED NOT NULL DEFAULT '0', dWitCross3 TINYINT UNSIGNED NOT NULL DEFAULT '0', "
            . "dCross1 TINYINT UNSIGNED NOT NULL DEFAULT '0', dCross2 TINYINT UNSIGNED NOT NULL DEFAULT '0', dCross3 TINYINT UNSIGNED NOT NULL DEFAULT '0', "
            . "dClose TINYINT UNSIGNED NOT NULL DEFAULT '0', "
            . "attyRank1 SMALLINT UNSIGNED NOT NULL DEFAULT '0', attyRank2 SMALLINT UNSIGNED NOT NULL DEFAULT '0', "
            . "attyRank3 SMALLINT UNSIGNED NOT NULL DEFAULT '0', attyRank4 SMALLINT UNSIGNED NOT NULL DEFAULT '0', "
            . "attyRank5 SMALLINT UNSIGNED NOT NULL DEFAULT '0', attyRank6 SMALLINT UNSIGNED NOT NULL DEFAULT '0', "
            . "witRank1 SMALLINT UNSIGNED NOT NULL DEFAULT '0', witRank2 SMALLINT UNSIGNED NOT NULL DEFAULT '0', "
            . "witRank3 SMALLINT UNSIGNED NOT NULL DEFAULT '0', witRank4 SMALLINT UNSIGNED NOT NULL DEFAULT '0', "
            . "witRank5 SMALLINT UNSIGNED NOT NULL DEFAULT '0', witRank6 SMALLINT UNSIGNED NOT NULL DEFAULT '0', "
            . "finalized BINARY(1) NOT NULL DEFAULT '0', "
            . "INDEX(pTeam), INDEX(dTeam), "
            . "INDEX(round), INDEX(judgeId), INDEX(attyRank1), "
            . "INDEX(attyRank2), INDEX(attyRank3), INDEX(attyRank4), INDEX(attyRank5), "
            . "INDEX(attyRank6), INDEX(witRank1), INDEX(witRank2), INDEX(witRank3), "
            . "INDEX(witRank4), INDEX(witRank5), INDEX(witRank6)) ENGINE InnoDB";
    $connection->query($ballotsTable);
    $teamConflictsTable = "CREATE TABLE teamConflicts(id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT KEY, "
            . "team1 SMALLINT UNSIGNED NOT NULL DEFAULT '0', team2 SMALLINT UNSIGNED NOT NULL DEFAULT '0', "
            . "INDEX(team1), INDEX(team2)) ENGINE InnoDB";
    $connection->query($teamConflictsTable);
    $judgeConflictsTable = "CREATE TABLE judgeConflicts(id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT KEY, "
            . "judge SMALLINT UNSIGNED NOT NULL DEFAULT '0', team SMALLINT UNSIGNED NOT NULL DEFAULT '0', "
            . "INDEX(judge), INDEX(team)) ENGINE InnoDB";
    $connection->query($judgeConflictsTable);
    $alertsTable = "CREATE TABLE alerts(id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT KEY, "
            . "user SMALLINT UNSIGNED NOT NULL DEFAULT '0', team SMALL INT UNSIGNED NOT NULL DEFAULT '0', "
            . "INDEX(user)) ENGINE InnoDB";
    $connection->query($alertsTable);

    //TODO: And code to specific a default tabulation director's email and password
    //TODO: Generate a new password salt and save it on the server
    $generateAdmin = "INSERT INTO users(name, email, password, isJudge, isCoach, "
            . "isTab, canJudgeRound1, canJudgeRound2, canJudgeRound3, canJudgeRound4) "
            . "VALUES('Tabulation Director', 'example@example.com', "
            . "'74dfc2b27acfa364da55f93a5caee29ccad3557247eda238831b3e9bd931b01d77fe994e4f12b9d4cfa92a124461d2065197d8cf7f33fc88566da2db2a4d6eae', " //Whirlpool hash for 'password'
            . "'0','0','1','0','0','0','0')";
    $connection->query($generateAdmin);
    $_SESSION = array();
    session_destroy();
    echo '<meta http-equiv="refresh" content="0;url=/index.php">';
}
