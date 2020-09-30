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
    
}

function createPairing($round, $plaintiff, $defense) {
    //create database connection
    $db = new Database();
    $conn = $db->getConnection();

    //convert team numbers to ids
    $teamQuery = "SELECT id,number FROM teams WHERE number = $plaintiff || number = $defense";
    $teamResult = $conn->query($teamQuery);
    $teamRow = $teamResult->fetch_assoc();
    if ($plaintiff == intval($teamRow["number"])) {
        $plaintiffID = intval($teamRow["id"]);
        $teamRow = $teamResult->fetch_assoc();
        $defenseID = intval($teamRow["id"]);
    } else {
        $defenseID = intval($teamRow["id"]);
        $teamRow = $teamResult->fetch_assoc();
        $plaintiffID = intval($teamRow["id"]);
    }
    $teamResult->close();


    $stmt = $conn->prepare("INSERT INTO pairings (round, plaintiff, defense) VALUES (?, ?, ?)");
    echo($stmt->error_list);
    $stmt->bind_param('iii', $round, $plaintiffID, $defenseID);
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

    //convert team numberrs into ids
    $teamQuery = "SELECT id,number FROM teams";
    $teamResult = $conn->query($teamQuery);
    while ($teamRow = $teamResult->fetch_assoc()) {
        for ($a = 0; $a < sizeOf($pairings); $a++) {
            if ($pairings[$a]["plaintiff"] === intval($teamRow["number"])) {
                $pairings[$a]["plaintiff"] = intval($teamRow["id"]);
            } else if ($pairings[$a]["defense"] === intval($teamRow["number"])) {
                $pairings[$a]["defense"] = intval($teamRow["id"]);
            }
        }
    }

    //insert the new pairings
    $stmt = $conn->prepare("INSERT INTO pairings (round, plaintiff, defense) VALUES (?, ?, ?)");
    for ($a = 0; $a < sizeOf($pairings); $a++) {
        //send to database
        $stmt->bind_param('iii', $round, $pairings[$a]["plaintiff"], $pairings[$a]["defense"]);
        $stmt->execute();
    }
    $stmt->close();

    //get id of new pairings
    $idQuery = "SELECT id FROM pairings WHERE round = $round";
    $ids = [];
    if ($result = $conn->query($idQuery)) {
        $i = 0;
        while ($row = $result->fetch_assoc()) {
            $ids[$i] = intval($row["id"]);
        }
        $result->close();
    }

    //close connection, return ids of new pairings
    $conn->close();
    return $ids;
}

function getAllPairings() {
    $pairings = [];

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

    //convert ids to team numbers
    $teamQuery = "SELECT id,number FROM teams";
    $teamResult = $conn->query($teamQuery);
    while ($teamRow = $teamResult->fetch_assoc()) {
        for ($a = 0; $a < sizeOf($pairings); $a++) {
            if ($pairings[$a]["plaintiff"] === intval($teamRow["id"])) {
                $pairings[$a]["plaintiff"] = intval($teamRow["number"]);
            } else if ($pairings[$a]["defense"] === intval($teamRow["id"])) {
                $pairings[$a]["defense"] = intval($teamRow["number"]);
            }
        }
    }
    $teamResult->close();
    $conn->close();
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


        //convert ids to team numbers
        $teamQuery = "SELECT id,number FROM teams";
        $teamResult = $conn->query($teamQuery);
        while ($teamRow = $teamResult->fetch_assoc()) {
            for ($a = 0; $a < sizeOf($pairings); $a++) {
                if ($pairings[$a]["plaintiff"] === intval($teamRow["id"])) {
                    $pairings[$a]["plaintiff"] = intval($teamRow["number"]);
                } else if ($pairings[$a]["defense"] === intval($teamRow["id"])) {
                    $pairings[$a]["defense"] = intval($teamRow["number"]);
                }
            }
        }
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

    //convert team number to id
    $teamQuery = "SELECT id FROM teams WHERE number = $number";
    $teamResult = $conn->query($teamQuery);
    $teamRow = $teamResult->fetch_assoc();
    $id = intval($teamRow["id"]);
    $teamResult->close();

    $pairingssQuery = "SELECT * FROM pairings WHERE plaintiff = $id || defense = $id";
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
