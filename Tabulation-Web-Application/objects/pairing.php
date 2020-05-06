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

class Pairing {

    public $id;
    public $round;
    public $plaintiff;
    public $defense;

    public function __construct($round, $plaintiff, $defense) {
        $this->round = $round;
        $this->plaintiff = $plaintiff;
        $this->defense = $defense;
    }

}

function createPairing($round, $plaintiff, $defense) {
    //create database connection
    $db = new Database();
    $conn = $db->getConnection();
    
    
    $stmt = $conn->prepare("INSERT INTO pairings (round, plaintiff, defense) VALUES (?, ?, ?)");
    echo($stmt->error_list);
    $stmt->bind_param('iii', $round, $plaintiff, $defense);
    $stmt->execute();
    $stmt->close();
    $conn->close();
    return true;
}

function createPairings($round, $pairings) {
    $db = new Database();
    $conn = $db->getConnection();
    
    //delete any existing pairings for the round in question
    $deleteQuery = "DELETE FROM `pairings` WHERE round = $round";
    $conn->query($deleteQuery);

    //insert the new pairings
    $stmt = $conn->prepare("INSERT INTO pairings (round, plaintiff, defense) VALUES (?, ?, ?)");
    for ($a = 0; $a < sizeOf($pairings); $a++) {
        $stmt->bind_param('iii', $round, $pairings[$a]["plaintiff"], $pairings[$a]["defense"]);
        $stmt->execute();
    }
    $stmt->close();
    $conn->close();
    return true;
}

function getAllPairings() {
    global $pairings;

    //connect to database
    $db = new Database();
    $conn = $db->getConnection();

    //get basic team data (number and name)
    $pairingsQuery = "SELECT * FROM pairings";
    if ($result = $conn->query($pairingsQuery)) {
        $i = 0;
        while ($row = $result->fetch_assoc()) {
            $pairings[$i]["id"] = intval($row["id"]);
            $pairings[$i]["round"] = intval($row["round"]);
            $pairings[$i]["plaintiff"] = intval($row["plaintiff"]);
            $pairings[$i]["defense"] = intval($row["defense"]);
            $i++;
        }
        /* free result set */
        $result->close();
    }
    return $pairings;
}

function getRoundPairings($round) {
    global $pairings;

    //connect to database
    $db = new Database();
    $conn = $db->getConnection();

    //get basic team data (number and name)
    $pairingssQuery = "SELECT * FROM pairings WHERE round = $round";
    if ($result = $conn->query($pairingssQuery)) {
        $i = 0;
        while ($row = $result->fetch_assoc()) {
            $pairings[$i]["round"] = $row["round"];
            $pairings[$i]["plaintiff"] = $row["plaintiff"];
            $pairings[$i]["defense"] = $row["defense"];
            $i++;
        }
        /* free result set */
        $result->close();
        $conn->close();
        return $pairings;
    } else {
        return false;
    }
}

function getTeamPairings($number) {
    //connect to database
    $db = new Database();
    $conn = $db->getConnection();

    $pairingssQuery = "SELECT * FROM pairings WHERE plaintiff = $number || defense = $number";
    if ($result = $conn->query($pairingssQuery)) {
        $pairings = [];
        while ($row = $result->fetch_assoc()) {
            $pairings[$row["round"]]["id"] = $row["id"];
            $pairings[$row["round"]]["plaintiff"] = $row["plaintiff"];
            $pairings[$row["round"]]["defense"] = $row["defense"];
        }
        /* free result set */
        $result->close();
        $conn->close();
        return $pairings;
    } else {
        return false;
    }
}
