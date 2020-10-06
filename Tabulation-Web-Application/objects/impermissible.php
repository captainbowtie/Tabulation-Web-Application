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

/**
 * Description of impermissible
 *
 * @author allen
 */
require_once __DIR__ . "/../config.php";
require_once SITE_ROOT . "/database.php";

class Impermissible {
    
}

function createImpermissible($team0, $team1) {
    $db = new Database();
    $conn = $db->getConnection();

    //convert team numbers to ids
    $teamQuery = "SELECT id FROM teams WHERE number = $team0 || number = $team1";
    $teamResult = $conn->query($teamQuery);
    $teamRow = $teamResult->fetch_assoc();
    $id0 = intval($teamRow["id"]);
    $teamRow = $teamResult->fetch_assoc();
    $id1 = intval($teamRow["id"]);
    $teamResult->close();

    //insert impermissible into database
    $stmt = $conn->prepare("INSERT INTO impermissibles (team0, team1) VALUES (?, ?)");
    $stmt->bind_param('ii', $id0, $id1);
    $stmt->execute();
    $stmt->close();
    $conn->close();
    return true;
}

function deleteImpermissible($team0, $team1) {
    $impermissibleDeleted = false;

    $db = new Database();
    $conn = $db->getConnection();

    //convert team numbers to ids
    $teamQuery = "SELECT id FROM teams WHERE number = $team0 || number = $team1";
    $teamResult = $conn->query($teamQuery);
    $teamRow = $teamResult->fetch_assoc();
    $id0 = intval($teamRow["id"]);
    $teamRow = $teamResult->fetch_assoc();
    $id1 = intval($teamRow["id"]);
    $teamResult->close();

    $query = "DELETE FROM `impermissibles` WHERE (team0 = $id0 && team1 = $id1) || (team0 = $id1 && team1 = $id0)";
    $conn->query($query);
    if ($conn->affected_rows == 1) {
        $impermissibleDeleted = true;
    }
    $conn->close();
    return $impermissibleDeleted;
}

function getAllImpermissibles() {
    $query = "SELECT * FROM impermissibles";
    $db = new Database();
    $conn = $db->getConnection();
    if ($result = $conn->query($query)) {
        $a = 0;
        global $impermissibles;
        while ($row = $result->fetch_assoc()) {
            $impermissibles[$a]["team0"] = intval($row["team0"]);
            $impermissibles[$a]["team1"] = intval($row["team1"]);
            $a++;
        }
        /* free result set */
        $result->close();
    }

    //convert ids to team numbers
    $teamQuery = "SELECT id,number,name FROM teams";
    if ($teamResult = $conn->query($teamQuery)) {
        while ($teamRow = $teamResult->fetch_assoc()) {
            for ($a = 0; $a < sizeOf($impermissibles); $a++) {
                if ($impermissibles[$a]["team0"] == intval($teamRow["id"])) {
                    $impermissibles[$a]["team0"] = intval($teamRow["number"]);
                } else if ($impermissibles[$a]["team1"] == intval($teamRow["id"])) {
                    $impermissibles[$a]["team1"] = intval($teamRow["number"]);
                }
            }
        }
    }
    $teamResult->close();
    $conn->close();

    return $impermissibles;
}
