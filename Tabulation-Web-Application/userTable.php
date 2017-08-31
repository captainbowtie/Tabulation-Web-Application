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

if (!isset($_SESSION['id'])) {
    echo "You must be logged in to view this page";
//Web page if logged in, but not as a coach or tabulation director
} else if (!$isTab) {
    echo "You do not have permission to access this page";
//Code if logged in as tabulation director
} else {
    $connection = new mysqli(dbhost, dbuser, dbpass, dbname);
    $userQuery = "SELECT id,name,email,isJudge,isCoach,isTab FROM users";
    $userResult = $connection->query($userQuery);
    $connection->close();

    echo "\n<table id='userTable'>";
    echo "\n<tr>"
    . "<td>id</td><td>name</td><td>email</td><td>Judge</td><td>Coach</td><td>Tab</td>"
    . "</tr>\n";
    for ($a = 0; $a < $userResult->num_rows; $a++) {
        $userResult->data_seek($a);
        $user = $userResult->fetch_array(MYSQLI_ASSOC);
        $userId = $user['id'];
        $userName = $user['name'];
        $userEmail = $user['email'];
        $userIsJudge = $user['isJudge'];
        $userIsCoach = $user['isCoach'];
        $userIsTab = $user['isTab'];
        echo "<tr>";
        echo "<td>$userId</td><td>$userName</td><td>$userEmail</td>"
        . "<td>$userIsJudge</td><td>$userIsCoach</td><td>$userIsTab</td>";
        echo "</tr>\n";
    }
    echo "</table>\n";
    $userResult->close();
}
