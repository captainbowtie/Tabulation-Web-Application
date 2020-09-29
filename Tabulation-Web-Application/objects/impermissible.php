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
    $team0query = "SELECT id FROM teams WHERE number = $team0";
    $team0result = $conn->query($team0query);
    $team0row = $team0result->fetch_assoc();
    $id0 = intval($team0row["id"]);
    $team0result->close();
    $team1query = "SELECT id FROM teams WHERE number = $team1";
    $team1result = $conn->query($team1query);
    $team1row = $team1result->fetch_assoc();
    $id1 = intval($team1row["id"]);
    $team1result->close();

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

    //convert team numbers to ids
    $team0query = "SELECT id FROM teams WHERE number = $team0";
    $team0result = $conn->query($team0query);
    $team0row = $team0result->fetch_assoc();
    $id0 = intval($team0row["id"]);
    $team0result->close();
    $team1query = "SELECT id FROM teams WHERE number = $team1";
    $team1result = $conn->query($team1query);
    $team1row = $team1result->fetch_assoc();
    $id1 = intval($team1row["id"]);
    $team1result->close();

    $query = "DELETE FROM `impermissibles` WHERE (team0 = $id0 && team1 = $id1) || (team0 = $id1 && team1 = $id0)";
    $db = new Database();
    $conn = $db->getConnection();
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
    $teamQuery = "SELECT id,number FROM teams";
    if ($teamResult = $conn->query($teamQuery)) {
        $a = 0;
        while ($teamRow = $teamResult->fetch_assoc()) {
            for ($b = 0; $b < sizeOf($impermissibles); $b++) {
                if ($impermissibles[$b]["team0"] == $teamRow[$a]["id"]) {
                    $impermissibles[$b]["team0"] = $teamRow[$a]["number"];
                } else if ($impermissibles[$b]["team1"] == $teamRow[$a]["id"]) {
                    $impermissibles[$b]["team1"] = $teamRow[$a]["number"];
                }
            }
        }
    }
    $teamResult->close();
    $conn->close();

    return $impermissibles;
}
