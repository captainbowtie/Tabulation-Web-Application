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
session_start();
if ($_SESSION["isAdmin"]) {
    require_once __DIR__ . '/../../config.php';
    require_once SITE_ROOT . '/objects/ballot.php';

    $data = json_decode(file_get_contents("php://input"));
    for ($a = 0; $a < sizeOf($data); $a++) {
        $pdjData[$a] = json_decode(json_encode($data[$a]), true);
    }

    if (
            validateBallotData($pdjData)
    ) {
        for ($a = 0; $a < sizeOf($pdjData); $a++) {
            $pdjData[$a]["plaintiff"] = htmlspecialchars(strip_tags($pdjData[$a]["plaintiff"]));
            $pdjData[$a]["defense"] = htmlspecialchars(strip_tags($pdjData[$a]["defense"]));
            $pdjData[$a]["judge"] = htmlspecialchars(strip_tags($pdjData[$a]["judge"]));
        }

        $pdjData = teamNumbersToIDs($pdjData);
        
        $ballotData = findPairingID($pdjData);

        if (createBallots($ballotData)) {
            // set response code - 201 created
            http_response_code(201);

            // tell the user
            echo json_encode(array("message" => "Ballots created."));
        } else {

            // set response code - 503 service unavailable
            http_response_code(503);

            // tell the user
            echo json_encode(array("message" => "Unable to create ballots."));
        }
    } else {

        // set response code - 400 bad request
        http_response_code(400);

        // tell the user
        echo json_encode(array("message" => "Unable to create ballots. Data is incomplete."));
    }
} else {
    http_response_code(401);
}

function validateBallotData($ballotData) {
    for ($a = 0; $a < sizeOf($ballotData); $a++) {
        if (!isset($ballotData[$a]["plaintiff"])) {
            return false;
        } else if (!isset($ballotData[$a]["defense"])) {
            return false;
        } else if (!isset($ballotData[$a]["judge"])) {
            return false;
        }
    }
    return true;
}

function findPairingID($ballotData) {
    //create database connection
    $db = new Database();
    $conn = $db->getConnection();

    //prepare query statement
    $stmt = $conn->prepare("SELECT id FROM pairings WHERE plaintiff = ? AND defense = ?");
    //for each pairing, determine the corresponding pairing ID
    for ($a = 0; $a < sizeOf($ballotData); $a++) {
        //echo "p:".$ballotData[$a]["plaintiff"]." d:".$ballotData[$a]["defense"]."\n";
        $stmt->bind_param('ii', $ballotData[$a]["plaintiff"], $ballotData[$a]["defense"]);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        $ballotData[$a]["pairing"] = $row["id"];
    }

    //close db connection
    $stmt->close();
    $conn->close();

    return $ballotData;
}

function teamNumbersToIDs($pdjData) {
    //create database connection
    $db = new Database();
    $conn = $db->getConnection();

    //convert team numberrs into ids
    $teamQuery = "SELECT id,number FROM teams";
    $teamResult = $conn->query($teamQuery);
    while ($teamRow = $teamResult->fetch_assoc()) {
        for ($a = 0; $a < sizeOf($pdjData); $a++) {
            if (intval($pdjData[$a]["plaintiff"]) === intval($teamRow["number"])) {
                $pdjData[$a]["plaintiff"] = intval($teamRow["id"]);
            } else if (intval($pdjData[$a]["defense"]) === intval($teamRow["number"])) {
                $pdjData[$a]["defense"] = intval($teamRow["id"]);
            }
        }
    }
    $teamResult->close();
    $conn->close();

    return $pdjData;
}
