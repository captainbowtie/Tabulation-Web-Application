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
} else if (!$isTab && !$isCoach) {
    echo "You do not have permission to access this page";
//Code if logged in as tabulation director
} else {
    //TODO: various validation checks as well as heightened permission check
    $connection = new mysqli(dbhost, dbuser, dbpass, dbname);
    //check if submission for new user (name is posted) or existing user
    if (isset($_POST['name'])) {
        $pAtty = 0;
        $pWit = 0;
        $dAtty = 0;
        $dWit = 0;
        $team = sanitize_string(($_POST['team']));
        $name = sanitize_string(($_POST['name']));
        if (isset($_POST['pAtty'])) {
            $pAtty = 1;
        }
        if (isset($_POST['pWit'])) {
            $pWit = 1;
        }
        if (isset($_POST['dAtty'])) {
            $dAtty = 1;
        }
        if (isset($_POST['dWit'])) {
            $dWit = 1;
        }
        $query = "INSERT INTO competitors (team,name,pAtty,pWit,dAtty,dWit) "
                . "VALUES($team,'$name',$pAtty,$pWit,$dAtty,$dWit)";
        $connection->query($query);
        $connection->close();
        echo "0";
    } else { //Code to run if changing single field
        $id = sanitize_string($_POST['id']);
        $field = sanitize_string($_POST['field']);
        $value = sanitize_string($_POST['value']);
        $query = "UPDATE competitors SET $field='$value' WHERE id=$id";
        $connection->query($query);
        $connection->close();
        echo "0";
    }
}
