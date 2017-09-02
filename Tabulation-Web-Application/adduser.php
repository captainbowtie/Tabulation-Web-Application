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
    $name = sanitize_string($_POST['name']);
    $email = sanitize_string($_POST['email']);
    $pass = hash('whirlpool', sanitize_string($_POST['pass']));
    if (sanitize_string($_POST['judge']) == 'on') {
        $judge = "1";
    } else {
        $judge = "0";
    }
    if (sanitize_string($_POST['coach']) == 'on') {
        $coach = "1";
    } else {
        $coach = "0";
    }
    if (sanitize_string($_POST['tab']) == 'on') {
        $tab = "1";
    } else {
        $tab = "0";
    }
    if (sanitize_string($_POST['round1']) == 'on') {
        $round1 = "1";
    } else {
        $round1 = "0";
    }
    if (sanitize_string($_POST['round2']) == 'on') {
        $round2 = "1";
    } else {
        $round2 = "0";
    }
    if (sanitize_string($_POST['round3']) == 'on') {
        $round3 = "1";
    } else {
        $round3 = "0";
    }
    if (sanitize_string($_POST['round4']) == 'on') {
        $round4 = "1";
    } else {
        $round4 = "0";
    }
    //TODO: validate values of name, email, and password
    //TODO: error handling
    $query = "INSERT INTO users(name,email,password,isJudge,isCoach,isTab,"
            . "canJudgeRound1, canJudgeRound2, canJudgeRound3, canJudgeRound4) "
            . "VALUES ('$name','$email','$pass','$judge','$coach',"
            . "'$tab','$round1','$round2','$round3','$round4')";
    
    $connection = new mysqli(dbhost, dbuser, dbpass, dbname);
    $connection->query($query);
    $connection->close();
    echo "0";
    
}