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

if ($isTab) {
    $roomQuery = "SELECT * FROM rooms";
    $roomResult = $connection->query($roomQuery);
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
        $roomQuality = $room["roomQuality"];
        echo "<tr>";
        echo "<td><input room='$id' class='existing' field='building' value='$building'></td>";
        echo "<td><input room='$id' class='existing' field='number' value = '$number'></td>";
        echo "<td><input type=checkbox room='$id' class='existing' field='round1'";
        if ($round1 == 1) {
            echo " checked";
        }
        echo "></td>";
        echo "<td><input type=checkbox room='$id' class='existing' field='round2'";
        if ($round2 == 1) {
            echo " checked";
        }
        echo "></td>";
        echo "<td><input type=checkbox room='$id' class='existing' field='round3'";
        if ($round3 == 1) {
            echo " checked";
        }
        echo "></td>";
        echo "<td><input type=checkbox room='$id' class='existing' field='round4'";
        if ($round4 == 1) {
            echo " checked";
        }
        echo "></td>";
        echo "<td><select room='$id' class='existing' field='quality'>\n";
        switch ($roomQuality){
            case 1:
                echo "<option selected>1</option>\n<option>2</option>\n<option>3</option>\n";
                break;
            case 2:
                echo "<option>1</option>\n<option selected>2</option>\n<option>3</option>\n";
                break;
            case 3:
                echo "<option>1</option>\n<option>2</option>\n<option selected>3</option>\n";
                break;
            default:
                echo "<option selected>1</option>\n<option>2</option>\n<option>3</option>\n";
                break;
        }
        echo "</select>\n";
        echo "</tr>\n";
    }
    echo "</table>";
} else if (!isset($_SESSION[id])) {
    echo "You must be logged in to view this page";
} else {
    echo "You do not have permission to access this page";
}