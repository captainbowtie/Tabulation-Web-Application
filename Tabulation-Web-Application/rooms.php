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

//HTML Header information
echo<<<_END
<!DOCTYPE HTML>
<html>
        <head>
            <title>Rooms</title>
                </head>
        <body>
_END;
require_once "header.php";

if (!isset($_SESSION['id'])) {
    echo "You must be logged in to view this page";
//Web page if logged in, but not as a judge or tabulation director
} else if (!$isTab) {
    echo "You do not have permission to access this page";
//Code if logged in as tabulation director
} else {
    $connection = new mysqli(dbhost, dbuser, dbpass, dbname);
    if ($connection->connect_error) {
        die($connection->connect_error);
    }
    $roomQuery = "SELECT * FROM rooms";
    $roomResult = $connection->query($roomQuery);
    echo "<form id='roomForm' name='roomForm'>\n";
    echo "<table id='roomTable'>\n";
    echo "<tr><td>Building</td><td>Room Number</td><td>Round 1</td><td>Round 2</td><td>Round 3</td><td>Round 4</td></tr>\n";
    for ($a = 0; $a < $roomResult->num_rows; $a++) {
        $roomResult->data_seek($a);
        $room = $roomResult->fetch_array(MYSQLI_ASSOC);
        $id = $room["id"];
        $building = $room["building"];
        $number = $room["number"];
        $round1 = $room["availableRound1"];
        $round2 = $room["availableRound2"];
        $round3 = $room["availableRound3"];
        $round4 = $room["availableRound4"];
        echo "<tr>";
        echo "<td><input room='$id' class='existingBuilding' value='$building'></td>";
        echo "<td><input room='$id' class='existingRoom' value = '$number'></td>";
        echo "<td><input type=checkbox room='$id' round=1";
        if ($round1 == 1) {
            echo " checked";
        }
        echo "></td>";
        echo "<td><input type=checkbox room='$id' round=2";
        if ($round2 == 1) {
            echo " checked";
        }
        echo "></td>";
        echo "<td><input type=checkbox room='$id' round=3";
        if ($round3 == 1) {
            echo " checked";
        }
        echo "></td>";
        echo "<td><input type=checkbox room='$id' round=4";
        if ($round4 == 1) {
            echo " checked";
        }
        echo "></td></tr>\n";
    }
    echo "<tr>"
    . "<td><input id='newBuilding'></td>"
    . "<td><input id='newNumber'></td>"
    . "<td><input type=checkbox id='round1'></td>"
            . "<td><input type=checkbox id='round2'></td>"
            . "<td><input type=checkbox id='round3'></td>"
            . "<td><input type=checkbox id='round4'></td>"
            . "<td><input type='submit' value='Add Room'></td></tr>\n";
    echo "</table>\n";
    echo "</form>\n";
}

echo "<script src='https://code.jquery.com/jquery-3.2.1.min.js'></script>";
echo "<script src='/rooms.js'></script>";
echo "</body>\n";
echo "</html>\n";