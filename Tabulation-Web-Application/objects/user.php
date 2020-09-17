<?php

/*
 * Copyright (C) 2019 allen
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

require_once __DIR__ . "/../config.php";
require_once SITE_ROOT . "/database.php";

class User {

    public $email;
    private $password;
    public $isAdmin;

    public function __construct($email, $password,$isAdmin) {
        $this->email = $email;
        $this->password = $password;
        $this->isAdmin = $isAdmin;
    }
}

function createUser($email, $password, $isAdmin) {
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT, ['cost' => 11,]);
    $userCreated = false;
    $db = new Database();
    $conn = $db->getConnection();
    $query = "INSERT INTO users (email, password, isAdmin) VALUES ("
            . "'$email',"
            . "'$hashedPassword',"
            . "$isAdmin)";
    $conn->query($query);
    if($conn->affected_rows == 1){
        $userCreated = true;
    }
    $conn->close();
    return $userCreated;
}

function getUser($email){
    $db = new Database();
    $conn = $db->getConnection();
    $query = "SELECT * FROM users WHERE email = '$email'";
    if ($result = $conn->query($query)) {
        $row = $result->fetch_assoc();
        $user["email"] = $email;
        $user["password"] = $row["password"];
        $user["isAdmin"] = $row["isAdmin"];
        $user["isCoach"] = $row["isCoach"];
        $result->close();
    }
    $conn->close();
    return $user;
}

function updateUser($existingEmail, $newE, $name) {
    $teamUpdated = false;
    $db = new Database();
    $conn = $db->getConnection();
    $query = "UPDATE teams SET number = $newNumber, name = '$name' WHERE number = $existingNumber";
    $conn->query($query);
    if($conn->affected_rows == 1){
        $teamUpdated = true;
    }
    $conn->close();
    return $teamUpdated;
}

function getAllUsers() {
    global $teams;

    //connect to database
    $db = new Database();
    $conn = $db->getConnection();

    //get basic team data (number and name)
    $teamsQuery = "SELECT * FROM teams ORDER BY number";
    if ($result = $conn->query($teamsQuery)) {
        $i = 0;
        while ($row = $result->fetch_assoc()) {
            $teams[$i]["number"] = intval($row["number"]);
            $teams[$i]["name"] = $row["name"];
            $i++;
        }
        /* free result set */
        $result->close();
    }
    $conn->close();
    return $teams;
}

function getUserPassword($number){
    //connect to database
    $db = new Database();
    $conn = $db->getConnection();
    
    //query database for name
    $nameQuery = "SELECT name FROM teams WHERE number = $number";
    if ($result = $conn->query($nameQuery)) {
        $row = $result->fetch_assoc();
        return $row["name"];
    }else{
        return false;
    }
    
}
