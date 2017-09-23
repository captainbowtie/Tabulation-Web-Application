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
require_once "dblogin.php";
require_once "setSessionPrivileges.php";
require_once "functions.php";

$connection = new mysqli(dbhost, dbuser, dbpass, dbname);
if ($connection->connect_error) {
    die($connection->connect_error);
}

if (isset($_POST["email"]) && isset($_POST["pass"])) {
    $email = sanitize_string($_POST["email"]);
    $pass = sanitize_string($_POST["pass"]);
    $hashedPass = hash('whirlpool', $pass);
    $query = "SELECT * FROM users WHERE email='$email' && password='$hashedPass'";
    //TODO: Stuff if the password is incorrect
    $result = $connection->query($query);
    $result->data_seek(0);
    $row = $result->fetch_array(MYSQLI_ASSOC);
    $id = $row['id'];
    $_SESSION['id'] = $id;
    $result->close();
}

require_once 'setSessionPrivileges.php';

echo "<div>";
if ($isTab) {
    echo '<a href="/tabsummary.php">Tab Summary</a>';
    echo '<a href="/teamEntry.php">Teams</a>';
    echo '<a href="/manageUsers.php">Manage Users</a>';
}
if ($isJudge) {
    echo '<a href="/ballot.php">Ballot</a>';
}
if ($isCoach) {
    echo '<a href="/tabsummary.php">Tab Summary</a>';
    echo '<a href="/tabcards.php">Tab Cards</a>';
    echo '<a href="/teamEntry.php">Team Members</a>';
}
echo <<<_END
<a href="/pairings.php">Round Pairings</a>
<a href="/locations.php">Locations</a>
<a href="/map.php">Map</a>
<a href="/contact.php">Contact</a>
_END;
if (isset($_SESSION['id'])) {
    echo '<a href="/alerts.php">Alerts</a>';
}
require_once "login.php";
echo "</div>\n";
$connection->close();
