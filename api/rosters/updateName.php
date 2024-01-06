<?php

/*
 * Copyright (C) 2024 allen
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
if (isset($_SESSION["team"])) {
	require_once __DIR__ . '/../../config.php';
	require_once SITE_ROOT . "/database.php";

	if (
		isset($_POST["name"]) &&
		isset($_POST["id"])
	) {
		$name = htmlspecialchars(strip_tags($_POST["name"]));
		$id = htmlspecialchars(strip_tags($_POST["id"]));
		$teamURL = htmlspecialchars(strip_tags($_SESSION["team"]));

		if (updateName($teamURL, $name, $id)) {
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
		echo json_encode(array("message" => "Unable to create student. Data is incomplete."));
	}
} else {
	http_response_code(401);
	echo json_encode(array("message" => -1));
}

function updateName($teamURL, $name, $id)
{
	$nameUpdated = false;
	$db = new Database();
	$conn = $db->getConnection();

	//get id of relevant team
	$teamStmt = $conn->prepare("SELECT id FROM teams WHERE url = :url");
	$teamStmt->bindParam(':url', $teamURL);
	$teamStmt->execute();
	$result = $teamStmt->fetchAll(PDO::FETCH_ASSOC);
	$teamId = $result[0]["id"];

	$stmt = $conn->prepare("UPDATE rosters SET student=:name WHERE id=:id && team=:teamId");
	$stmt->bindParam(':teamId', $teamId);
	$stmt->bindParam(':name', $name);
	$stmt->bindParam(':id', $id);
	$stmt->execute();
	$conn = null;
	$nameUpdated = true;
	return $nameUpdated;
}
