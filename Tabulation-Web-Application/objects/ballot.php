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

class Ballot {

    // object properties
    public $id;
    public $pairing;
    public $plaintiffPD;

}

function createBallot($pairing) {
    $db = new Database();
    $conn = $db->getConnection();
    $stmt = $conn->prepare("INSERT INTO ballots (pairing) VALUES (?)");
    $stmt->bind_param('i', $pairing);
    $stmt->execute();
    $stmt->close();
    $conn->close();
    return true;
}

function createBallots($pairings) {
    $db = new Database();
    $conn = $db->getConnection();

    //determine number of ballots per round and create that many ballots for each pairing
    $judgeCountQuery = "SELECT judgesPerRound FROM settings";
    $judgeCountResult = $conn->query($judgeCountQuery);
    $judgeCountRow = $judgeCountResult->fetch_assoc();
    $judgeCount = $judgeCountRow["judgesPerRound"];

    //create ballots
    $stmt = $conn->prepare("INSERT INTO ballots (pairing) VALUES (?)");
    for ($a = 0; $a < sizeOf($pairings); $a++) {
        $stmt->bind_param('i', $pairings[$a]);
        //execute statement as many times as there are judges per round
        for ($b = 0; b < $judgeCount; $b++) {
            $stmt->execute();
        }
    }
    $stmt->close();
    return true;
}

function getAllBallots() {
    $ballots = [];

    //connect to database
    $db = new Database();
    $conn = $db->getConnection();

    //get basic ballot data
    $ballotsQuery = "SELECT * FROM ballots";
    if ($result = $conn->query($ballotsQuery)) {
        $i = 0;
        while ($row = $result->fetch_assoc()) {
            $ballots[$i]["id"] = intval($row["id"]);
            $ballots[$i]["pairing"] = intval($row["pairing"]);
            $ballots[$i]["pOpen"] = intval($row["pOpen"]);
            $ballots[$i]["dOpen"] = intval($row["dOpen"]);
            $ballots[$i]["pDx1"] = intval($row["pDx1"]);
            $ballots[$i]["pWDx1"] = intval($row["pWDx1"]);
            $ballots[$i]["pWCx1"] = intval($row["pWCx1"]);
            $ballots[$i]["dCx1"] = intval($row["dCx1"]);
            $ballots[$i]["pDx2"] = intval($row["pDx2"]);
            $ballots[$i]["pWDx2"] = intval($row["pWDx2"]);
            $ballots[$i]["pWCx2"] = intval($row["pWCx2"]);
            $ballots[$i]["dCx2"] = intval($row["dCx2"]);
            $ballots[$i]["pDx3"] = intval($row["pDx3"]);
            $ballots[$i]["pWDx3"] = intval($row["pWDx3"]);
            $ballots[$i]["pWCx3"] = intval($row["pWCx3"]);
            $ballots[$i]["dCx3"] = intval($row["dCx3"]);
            $ballots[$i]["dDx1"] = intval($row["dDx1"]);
            $ballots[$i]["dWDx1"] = intval($row["dWDx1"]);
            $ballots[$i]["dWCx1"] = intval($row["dWCx1"]);
            $ballots[$i]["pCx1"] = intval($row["pCx1"]);
            $ballots[$i]["dDx2"] = intval($row["dDx2"]);
            $ballots[$i]["dWDx2"] = intval($row["dWDx2"]);
            $ballots[$i]["dWCx2"] = intval($row["dWCx2"]);
            $ballots[$i]["pCx2"] = intval($row["pCx2"]);
            $ballots[$i]["dDx3"] = intval($row["dDx3"]);
            $ballots[$i]["dWDx3"] = intval($row["dWDx3"]);
            $ballots[$i]["dWCx3"] = intval($row["dWCx3"]);
            $ballots[$i]["pCx3"] = intval($row["pCx3"]);
            $ballots[$i]["pClose"] = intval($row["pClose"]);
            $ballots[$i]["dClose"] = intval($row["dClose"]);
            $ballots[$i]["aty1"] = intval($row["aty1"]);
            $ballots[$i]["aty2"] = intval($row["aty2"]);
            $ballots[$i]["aty3"] = intval($row["aty3"]);
            $ballots[$i]["aty4"] = intval($row["aty4"]);
            $ballots[$i]["wit1"] = intval($row["wit1"]);
            $ballots[$i]["wit2"] = intval($row["wit2"]);
            $ballots[$i]["wit3"] = intval($row["wit3"]);
            $ballots[$i]["wit4"] = intval($row["wit4"]);
            $i++;
        }
        /* free result set */
        $result->close();
        $conn->close();
        return $ballots;
    } else {
        return false;
    }
}

function getAllPDs() {
    $ballots = [];

    //connect to database
    $db = new Database();
    $conn = $db->getConnection();

    //get basic ballot data
    $ballotsQuery = "SELECT id, pairing, "
            . "(CAST(pOpen AS SIGNED)+"
            . "CAST(pDx1 AS SIGNED)+CAST(pDx2 AS SIGNED)+CAST(pDx3 AS SIGNED)+"
            . "CAST(pWDx1 AS SIGNED)+CAST(pWDx2 AS SIGNED)+CAST(pWDx3 AS SIGNED)+"
            . "CAST(pWCx1 AS SIGNED)+CAST(pWDx2 AS SIGNED)+CAST(pWDx3 AS SIGNED)+"
            . "CAST(pCx1 AS SIGNED)+CAST(pCx2 AS SIGNED)+CAST(pCx3 AS SIGNED)+"
            . "CAST(pClose AS SIGNED))-"
            . "(CAST(dOpen AS SIGNED)+"
            . "CAST(dDx1 AS SIGNED)+CAST(dDx2 AS SIGNED)+CAST(dDx3 AS SIGNED)+"
            . "CAST(dWDx1 AS SIGNED)+CAST(dWDx2 AS SIGNED)+CAST(dWDx3 AS SIGNED)+"
            . "CAST(dWCx1 AS SIGNED)+CAST(dWCx2 AS SIGNED)+CAST(dWCx3 AS SIGNED)+"
            . "CAST(dCx1 AS SIGNED)+CAST(dCx2 AS SIGNED)+CAST(dCx3 AS SIGNED)+"
            . "CAST(dClose AS SIGNED)) "
            . "AS 'plaintiffPD'"
            . " FROM ballots";
    if ($result = $conn->query($ballotsQuery)) {
        $i = 0;
        while ($row = $result->fetch_assoc()) {
            $ballots[$i]["id"] = intval($row["id"]);
            $ballots[$i]["pairing"] = intval($row["pairing"]);
            $ballots[$i]["plaintiffPD"] = intval($row["plaintiffPD"]);
            $i++;
        }
        /* free result set */
        $result->close();
        $conn->close();
        return $ballots;
    } else {
        return false;
    }
}

function getPairingBallots($pairing) {
    global $ballots;

    //connect to database
    $db = new Database();
    $conn = $db->getConnection();

    //get basic ballot data
    $ballotsQuery = "SELECT * FROM ballots WHERE pairing = $pairing";
    if ($result = $conn->query($ballotsQuery)) {
        $i = 0;
        while ($row = $result->fetch_assoc()) {
            $ballots[$i]["id"] = intval($row["id"]);
            $ballots[$i]["pairing"] = intval($row["pairing"]);
            $ballots[$i]["pOpen"] = intval($row["pOpen"]);
            $ballots[$i]["dOpen"] = intval($row["dOpen"]);
            $ballots[$i]["pDx1"] = intval($row["pDx1"]);
            $ballots[$i]["pWDx1"] = intval($row["pWDx1"]);
            $ballots[$i]["pWCx1"] = intval($row["pWCx1"]);
            $ballots[$i]["dCx1"] = intval($row["dCx1"]);
            $ballots[$i]["pDx2"] = intval($row["pDx2"]);
            $ballots[$i]["pWDx2"] = intval($row["pWDx2"]);
            $ballots[$i]["pWCx2"] = intval($row["pWCx2"]);
            $ballots[$i]["dCx2"] = intval($row["dCx2"]);
            $ballots[$i]["pDx3"] = intval($row["pDx3"]);
            $ballots[$i]["pWDx3"] = intval($row["pWDx3"]);
            $ballots[$i]["pWCx3"] = intval($row["pWCx3"]);
            $ballots[$i]["dCx3"] = intval($row["dCx3"]);
            $ballots[$i]["dDx1"] = intval($row["dDx1"]);
            $ballots[$i]["dWDx1"] = intval($row["dWDx1"]);
            $ballots[$i]["dWCx1"] = intval($row["dWCx1"]);
            $ballots[$i]["pCx1"] = intval($row["pCx1"]);
            $ballots[$i]["dDx2"] = intval($row["dDx2"]);
            $ballots[$i]["dWDx2"] = intval($row["dWDx2"]);
            $ballots[$i]["dWCx2"] = intval($row["dWCx2"]);
            $ballots[$i]["pCx2"] = intval($row["pCx2"]);
            $ballots[$i]["dDx3"] = intval($row["dDx3"]);
            $ballots[$i]["dWDx3"] = intval($row["dWDx3"]);
            $ballots[$i]["dWCx3"] = intval($row["dWCx3"]);
            $ballots[$i]["pCx3"] = intval($row["pCx3"]);
            $ballots[$i]["pClose"] = intval($row["pClose"]);
            $ballots[$i]["dClose"] = intval($row["dClose"]);
            $ballots[$i]["aty1"] = intval($row["aty1"]);
            $ballots[$i]["aty2"] = intval($row["aty2"]);
            $ballots[$i]["aty3"] = intval($row["aty3"]);
            $ballots[$i]["aty4"] = intval($row["aty4"]);
            $ballots[$i]["wit1"] = intval($row["wit1"]);
            $ballots[$i]["wit2"] = intval($row["wit2"]);
            $ballots[$i]["wit3"] = intval($row["wit3"]);
            $ballots[$i]["wit4"] = intval($row["wit4"]);
            $i++;
        }
        /* free result set */
        $result->close();
        $conn->close();
        return $ballots;
    } else {
        return false;
    }
}

function updateBallot($ballot) {
    $stmt = "UPDATE ballots SET "
            . "pOpen = " . $ballot["pOpen"] . ", "
            . "dOpen = " . $ballot["dOpen"] . ", "
            . "pDx1 = " . $ballot["pDx1"] . ", "
            . "pDx2 = " . $ballot["pDx2"] . ", "
            . "pDx3 = " . $ballot["pDx3"] . ", "
            . "pWDx1 = " . $ballot["pWDx1"] . ", "
            . "pWDx2 = " . $ballot["pWDx2"] . ", "
            . "pWDx3 = " . $ballot["pWDx3"] . ", "
            . "pWCx1 = " . $ballot["pWCx1"] . ", "
            . "pWCx2 = " . $ballot["pWCx2"] . ", "
            . "pWCx3 = " . $ballot["pWCx3"] . ", "
            . "pCx1 = " . $ballot["pCx1"] . ", "
            . "pCx2 = " . $ballot["pCx2"] . ", "
            . "pCx3 = " . $ballot["pCx3"] . ", "
            . "dDx1 = " . $ballot["dDx1"] . ", "
            . "dDx2 = " . $ballot["dDx2"] . ", "
            . "dDx3 = " . $ballot["dDx3"] . ", "
            . "dWDx1 = " . $ballot["dWDx1"] . ", "
            . "dWDx2 = " . $ballot["dWDx2"] . ", "
            . "dWDx3 = " . $ballot["dWDx3"] . ", "
            . "dWCx1 = " . $ballot["dWCx1"] . ", "
            . "dWCx2 = " . $ballot["dWCx2"] . ", "
            . "dWCx3 = " . $ballot["dWCx3"] . ", "
            . "dCx1 = " . $ballot["dCx1"] . ", "
            . "dCx2 = " . $ballot["dCx2"] . ", "
            . "dCx3 = " . $ballot["dCx3"] . ", "
            . "pClose = " . $ballot["pClose"] . ", "
            . "dClose = " . $ballot["dClose"] . " "
            . "WHERE id = " . $ballot["id"];

    $db = new Database();
    $conn = $db->getConnection();
    $conn->query($stmt);
    $conn->close();
    echo $stmt;
    return true;
}
