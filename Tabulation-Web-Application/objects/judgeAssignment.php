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

function createAssignments($assignments) {
    $db = new Database();
    $conn = $db->getConnection();

    //insert conflict into database
    $stmt = $conn->prepare("INSERT INTO judgeAssignments (round, pairing, judge) VALUES (?, ?, ?)");
    for ($a = 0; $a < sizeOf($assignments); $a++) {
        $stmt->bind_param('iii', $assignments["round"], $assignments["pairing"], $assignments["judge"]);
        $stmt->execute();
    }
    $stmt->close();
    $conn->close();
    return true;
}

function deleteAssignments($round) {
    $assignmentsDeleted = false;

    $db = new Database();
    $conn = $db->getConnection();
    
    $query = "DELETE FROM judgeAssignments WHERE round = $round";
    $conn->query($query);
    if ($conn->affected_rows == 1) {
        $assignmentsDeleted = true;
    }
    $conn->close();
    return $assignmentsDeleted;
}

function getAllAssignments() {
    $db = new Database();
    $conn = $db->getConnection();

    $query = "SELECT * FROM judgeAssignments";
    if ($result = $conn->query($query)) {
        $a = 0;
        while ($assignmentRow = $result->fetch_assoc()) {
            $assignments[$a]["id"] = intval($assignmentRow["id"]);
            $assignments[$a]["judge"] = intval($assignmentRow["judge"]);
            $assignments[$a]["pairing"] = intval($assignmentRow["pairing"]);
            $a++;
        }
        /* free result set */
        $result->close();
    }

    $conn->close();

    return $assignments;
}
