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
//Web page if logged in, but not as a coach or tabulation director
} else if (!$isTab) {
    echo "You do not have permission to access this page";
//Code if logged in as tabulation director
} else {
    $connection = new mysqli(dbhost, dbuser, dbpass, dbname);
    if (isset($_POST['id'])) {
        //Code if updating existing user
        //Set variables for query
        $id = sanitize_string($_POST['id']);
        $field = sanitize_string($_POST['field']);
        $value = sanitize_string($_POST['value']);

        //Query to update table
        $query = "UPDATE users SET $field='$value' WHERE id=$id";

        //Write query to database
        $connection->query($query);
        $connection->close();
        echo "0";
    } else {
        //Code if adding new user
        //TODO: validate data entry
        //Set variables for query
        $name = sanitize_string($_POST['name']);
        $email = sanitize_string($_POST['email']);
        $clearPass = sanitize_string($_POST['password']);
        $hashedPass = hash('whirlpool', $clearPass);
        $tab = sanitize_string($_POST['isTab']);
        $coach = sanitize_string($_POST['isCoach']);
        $judge = sanitize_string($_POST['isJudge']);
        $round1 = sanitize_string($_POST['round1']);
        $round2 = sanitize_string($_POST['round2']);
        $round3 = sanitize_string($_POST['round3']);
        $round4 = sanitize_string($_POST['round4']);
        $judgeQuality = sanitize_string($_POST['judgeQuality']);

        //Create query
        $userQuery = "INSERT INTO users(name, email, password, isJudge, isCoach, "
                . "isTab, canJudgeRound1, canJudgeRound2, canJudgeRound3, canJudgeRound4, judgeQuality) "
                . "VALUES('$name', '$email', '$hashedPass', '$judge', '$coach', "
                . "'$tab', '$round1', '$round2', '$round3', '$round4', '$judgeQuality')";
        
        //Send query to database
        $connection->query($userQuery);
        $connection->close();
        echo "0";
    }
}