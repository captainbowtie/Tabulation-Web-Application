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
    public $isCoach;

    public function __construct($email, $password, $isAdmin) {
        $this->email = $email;
        $this->password = $password;
        $this->isAdmin = $isAdmin;
        $this->isCoach = $isCoach;
    }

}

function createUser($email, $password, $isAdmin, $isCoach) {
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT, ['cost' => 11,]);
    $url = bin2hex(random_bytes(8));
    $userCreated = false;
    $db = new Database();
    $conn = $db->getConnection();
    $query = "INSERT INTO users (email, password, isAdmin, isCoach, url) VALUES ("
            . "'$email',"
            . "'$hashedPassword',"
            . "$isAdmin,"
            . "$isCoach,"
            . "'$url')";
    $conn->query($query);
    echo $query;
    if ($conn->affected_rows == 1) {
        $userCreated = true;
    }
    $conn->close();
    return $userCreated;
}

function getUserByEmail($email) {
    $db = new Database();
    $conn = $db->getConnection();
    $query = "SELECT * FROM users WHERE email = '$email'";
    if ($result = $conn->query($query)) {
        $row = $result->fetch_assoc();
        $user["id"] = intval($row["id"]);
        $user["email"] = $email;
        $user["password"] = $row["password"];
        $user["isAdmin"] = $row["isAdmin"];
        $user["isCoach"] = $row["isCoach"];
        $user["url"] = $row["url"];
        $result->close();
    }
    $conn->close();
    return $user;
}

function getUserByURL($url) {
    $db = new Database();
    $conn = $db->getConnection();
    $query = "SELECT * FROM users WHERE url = '$url'";
    if ($result = $conn->query($query)) {
        $row = $result->fetch_assoc();
        $user["id"] = intval($row["id"]);
        $user["email"] = $row["email"];
        $user["password"] = $row["password"];
        $user["isAdmin"] = $row["isAdmin"];
        $user["isCoach"] = $row["isCoach"];
        $user["url"] = $row["url"];
        $result->close();
    }
    $conn->close();
    return $user;
}

function getAllUsers() {
    global $users;

    //connect to database
    $db = new Database();
    $conn = $db->getConnection();

    //get user data
    $userQuery = "SELECT id,email,isAdmin,isCoach,url FROM users ORDER BY email";
    if ($result = $conn->query($userQuery)) {
        $i = 0;
        while ($row = $result->fetch_assoc()) {
            $users[$i]["id"] = $row["id"];
            $users[$i]["email"] = $row["email"];
            $users[$i]["isAdmin"] = $row["isAdmin"];
            $users[$i]["isCoach"] = $row["isCoach"];
            $users[$i]["url"] = $row["url"];
            $i++;
        }
        /* free result set */
        $result->close();
    }
    $conn->close();
    return $users;
}

function updateUser($id, $field, $value) {
    $userUpdated = false;
    $db = new Database();
    $conn = $db->getConnection();
    switch ($field) {
        case "email":
            $query = "UPDATE users SET email = '$value' WHERE id = $id";
            break;
        case "password":
            $hashed = password_hash($value, PASSWORD_BCRYPT, ['cost' => 11,]);
            $query = "UPDATE users SET password = '$hashed' WHERE id = $id";
            break;
        case "isAdmin":
            $query = "UPDATE users SET isAdmin = $value WHERE id = $id";
            break;
        case "isCoach":
            $query = "UPDATE users SET isCoach = $value WHERE id = $id";
            break;
        default:
            break;
    }
    $conn->query($query);
    if ($conn->affected_rows == 1) {
        $userUpdated = true;
    }
    $conn->close();
    return $userUpdated;
}

function resetURL($id) {
    $url = bin2hex(random_bytes(8));
    $urlReset = false;
    $db = new Database();
    $conn = $db->getConnection();
    $query = "UPDATE users SET url = '$url' WHERE id = $id";
    $conn->query($query);
    if ($conn->affected_rows == 1) {
        $urlReset = true;
    }
    $conn->close();
    return $urlReset;
}
