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

function createConflict($judgeID, $team) {
    $db = new Database();
    $conn = $db->getConnection();

    //convert team number to id
    $teamQuery = "SELECT id FROM teams WHERE number = $team";
    $teamResult = $conn->query($teamQuery);
    $teamRow = $teamResult->fetch_assoc();
    $teamID = intval($teamRow["id"]);
    $teamResult->close();

    //insert conflict into database
    $stmt = $conn->prepare("INSERT INTO judgeConflicts (judge, team) VALUES (?, ?)");
    $stmt->bind_param('ii', $judgeID, $teamID);
    $stmt->execute();
    $stmt->close();
    $conn->close();
    return true;
}

function deleteConflict($judgeID, $team) {
    $conflictDeleted = false;

    $db = new Database();
    $conn = $db->getConnection();

    //convert team number to id
    $teamQuery = "SELECT id FROM teams WHERE number = $team";
    $teamResult = $conn->query($teamQuery);
    $teamRow = $teamResult->fetch_assoc();
    $teamID = intval($teamRow["id"]);
    $teamResult->close();

    $query = "DELETE FROM judgeConflicts WHERE judge = $judgeID && team = $teamID";
    $conn->query($query);
    if ($conn->affected_rows == 1) {
        $impermissibleDeleted = true;
    }
    $conn->close();
    return $impermissibleDeleted;
}

function getAllConflicts() {
    $db = new Database();
    $conn = $db->getConnection();
    
    $query = "SELECT * FROM judgeConflicts";
    if ($result = $conn->query($query)) {
        $a = 0;
        while ($conflictRow = $result->fetch_assoc()) {
            $conflicts[$a]["judge"] = intval($conflictRow["judge"]);
            $conflicts[$a]["team"] = intval($conflictRow["team"]);
            $a++;
        }
        /* free result set */
        $result->close();
    }

    //convert ids to team numbers
    $teamQuery = "SELECT id,number FROM teams";
    if ($teamResult = $conn->query($teamQuery)) {
        while ($teamRow = $teamResult->fetch_assoc()) {
            for ($a = 0; $a < sizeOf($conflicts); $a++) {
                if ($conflicts[$a]["team"] === intval($teamRow["id"])) {
                    $conflicts[$a]["team"] = intval($teamRow["number"]);
                }
            }
        }
    }
    $teamResult->close();
    $conn->close();

    return $conflicts;
}
