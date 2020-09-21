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
 *
 * @author allen
 */
session_start();
if ($_SESSION["isAdmin"]) {
    require_once __DIR__ . '/../../config.php';
    require_once SITE_ROOT . "/database.php";

    $judgesPerRound = json_decode(file_get_contents("php://input"))->judgesPerRound;
//echo json_decode(file_get_contents("php://input"));
    if (
            true //TODO: create some actual tests, sike whether the value is already set
    ) {
        $query = "INSERT INTO settings (judgesPerRound) VALUES ($judgesPerRound)";
        $db = new Database();
        $conn = $db->getConnection();

        if (!$conn->query($query)) {
            // set response code - 503 service unavailable
            http_response_code(503);

            // tell the user
            echo json_encode(array("message" => "Unable to create set judges per round."));
        } else {
            http_response_code(201);
            echo json_encode(array("message" => 0));
        }
    } else {
        // set response code - 400 bad request
        http_response_code(400);

        // tell the user
        echo json_encode(array("message" => "Unable to set judges per round. Data is incomplete."));
    }
} else {
    http_response_code(401);
}
