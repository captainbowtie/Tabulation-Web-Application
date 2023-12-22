<?php

/*
 * Copyright (C) 2023 allen
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
	require_once SITE_ROOT . "/database.php";

	if (
		isset($_POST["team0"]) &&
		isset($_POST["team1"])
	) {
		$team0 = htmlspecialchars(strip_tags($_POST["team0"]));
		$team1 = htmlspecialchars(strip_tags($_POST["team1"]));

		if (createConflict($team0, $team1)) {
			// set response code - 201 created
			http_response_code(201);

			// tell the user
			echo json_encode(array("message" => 0));
		} else {

			// set response code - 503 service unavailable
			http_response_code(503);

			// tell the user
			echo json_encode(array("message" => "Unable to create conflict."));
		}
	} else {

		// set response code - 400 bad request
		http_response_code(400);

		// tell the user
		echo json_encode(array("message" => "Unable to create conflict. Data is incomplete."));
	}
} else {
	$_SESSION["isAdmin"] = false;
	http_response_code(401);
	echo json_encode(array("message" => -1));
}

function createConflict($team0, $team1)
{
	$conflictCreated = false;
	$db = new Database();
	$conn = $db->getConnection();
	$stmt = $conn->prepare("INSERT INTO teamConflicts (team0, team1) VALUES (:team0, :team1)");
	$stmt->bindParam(':team0', $team0);
	$stmt->bindParam(':team1', $team1);
	$stmt->execute();
	$conn = null;
	$conflictCreated = true;
	return $conflictCreated;
}
