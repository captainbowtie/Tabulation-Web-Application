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

	$data = json_decode(file_get_contents("php://input"));

	if (
		isset($_POST["id"]) &&
		isset($_POST["number"])
	) {
		$id = htmlspecialchars(strip_tags($_POST["id"]));
		$number = htmlspecialchars(strip_tags($_POST["number"]));
		if (updateTeam($id, $number)) {
			// set response code - 201 created
			http_response_code(201);

			// tell the user
			echo json_encode(array("message" => 0));
		} else {

			// set response code - 503 service unavailable
			http_response_code(503);

			// tell the user
			echo json_encode(array("message" => "Unable to update team."));
		}
	} else {

		// set response code - 400 bad request
		http_response_code(400);

		// tell the user
		echo json_encode(array("message" => "Unable to update team. Data is incomplete."));
	}
} else {
	$_SESSION["isAdmin"] = false;
	http_response_code(401);
	echo json_encode(array("message" => -1));
}

function updateTeam($id, $number)
{
	$teamUpdated = false;
	$db = new Database();
	$conn = $db->getConnection();
	$stmt = $conn->prepare("UPDATE teams SET number=:number WHERE id=:id");
	$stmt->bindParam(':number', $number);
	$stmt->bindParam(':id', $id);
	$stmt->execute();
	$conn = null;
	$teamUpdated = true;
	return $teamUpdated;
}
