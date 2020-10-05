<?php

/*
 * Copyright (C) 2020 allen
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

class Judge {

    public $name;
    public $category;
    public $round1;
    public $round2;
    public $round3;
    public $round4;


}

function createJudge($name, $category, $round1, $round2, $round3, $round4) {
    $judgeCreated = false;
    $db = new Database();
    $conn = $db->getConnection();
    $query = "INSERT INTO judges (name, category, round1, round2, round3, round4) VALUES ("
            . "'$name',"
            . "$category,"
            . "$round1,"
            . "$round2,"
            . "$round3,"
            . "$round4)";
    $conn->query($query);
    if ($conn->affected_rows == 1) {
        $judgeCreated = true;
    }
    $conn->close();
    return $judgeCreated;
}

function getAllJudges() {
    global $judges;

    //connect to database
    $db = new Database();
    $conn = $db->getConnection();

    //get user data
    $userQuery = "SELECT id,name,category,round1,round2,round3,round4 FROM judges ORDER BY id";
    if ($result = $conn->query($userQuery)) {
        $i = 0;
        while ($row = $result->fetch_assoc()) {
            $judges[$i]["id"] = intval($row["id"]);
            $judges[$i]["name"] = $row["name"];
            $judges[$i]["category"] = intval($row["category"]);
            $judges[$i]["round1"] = $row["round1"];
            $judges[$i]["round2"] = $row["round2"];
            $judges[$i]["round3"] = $row["round3"];
            $judges[$i]["round4"] = $row["round4"];
            $i++;
        }
        /* free result set */
        $result->close();
    }
    $conn->close();
    return $judges;
}

function updateJudge($id, $field, $value) {
    $judgeUpdated = false;
    $db = new Database();
    $conn = $db->getConnection();
    switch ($field) {
        case "name":
            $query = "UPDATE judges SET name = '$value' WHERE id = $id";
            break;
        case "category":
            $query = "UPDATE judges SET category = $value WHERE id = $id";
            break;
        case "round1":
            $query = "UPDATE judges SET round1 = $value WHERE id = $id";
            break;
        case "round2":
            $query = "UPDATE judges SET round2 = $value WHERE id = $id";
            break;
        case "round3":
            $query = "UPDATE judges SET round3 = $value WHERE id = $id";
            break;
        case "round4":
            $query = "UPDATE judges SET round4 = $value WHERE id = $id";
            break;
        default:
            break;
    }
    $conn->query($query);
    if ($conn->affected_rows == 1) {
        $judgeUpdated = true;
    }
    $conn->close();
    return $judgeUpdated;
}
