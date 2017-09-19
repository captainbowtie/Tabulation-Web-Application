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
    $teamNumber = sanitize_string($_POST['teamNumber']);
    $teamName = sanitize_string($_POST['teamName']);
    $coach = sanitize_string($_POST['coach']);
    $teamQuery = "INSERT INTO teams (number,name,coachId) "
            . "VALUES($teamNumber,'$teamName',$coach)";
    $connection = new mysqli(dbhost, dbuser, dbpass, dbname);
    $result = $connection->query($teamQuery);
    $connection->close();
    echo "$teamQuery";
}else if(!isset($_SESSION[id])){
    echo "You must be logged in to view this page";
}else{
    echo "You do not have permission to access this page";
}