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

if($isTab){
    $building = sanitize_string($_POST['building']);
    $number = sanitize_string($_POST['number']);
    $round1 = sanitize_string($_POST['round1']);
    $round2 = sanitize_string($_POST['round2']);
    $round3 = sanitize_string($_POST['round3']);
    $round4 = sanitize_string($_POST['round4']);
    $roomQuality = sanitize_string($_POST['roomQuality']);
    $roomQuery = "INSERT INTO rooms (building,number,availableRound1,"
            . "availableRound2,availableRound3,availableRound4,roomQuality) "
            . "VALUES('$building','$number',$round1,$round2,$round3,$round4,"
            . "$roomQuality)";
    $connection = new mysqli(dbhost, dbuser, dbpass, dbname);
    $result = $connection->query($roomQuery);
    $connection->close();
}else if(!isset($_SESSION[id])){
    echo "You must be logged in to view this page";
}else{
    echo "You do not have permission to access this page";
}