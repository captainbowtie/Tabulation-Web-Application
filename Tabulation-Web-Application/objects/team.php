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

class Team {

    public $number;
    public $name;

    public function __construct($number, $name) {
        $this->number = $number;
        $this->name = $name;
    }
}

function createTeam($number, $name) {
    $teamCreated = false;
    $db = new Database();
    $conn = $db->getConnection();
    $query = "INSERT INTO teams (number, name) VALUES ($number,'$name')";
    $conn->query($query);
    if($conn->affected_rows == 1){
        $teamCreated = true;
    }
    $conn->close();
    return $teamCreated;
}

function updateTeam($existingNumber, $newNumber, $name) {
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

function deleteTeam($number){
    $teamDeleted = false;
    $query = "DELETE FROM teams WHERE number = $number";
    $db = new Database();
    $conn = $db->getConnection();
    $conn->query($query);
    if($conn->affected_rows == 1){
        $teamDeleted = true;
    }
    $conn->close();
    return $teamDeleted;
}

function getAllTeams() {
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

function getTeamName($number){
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
