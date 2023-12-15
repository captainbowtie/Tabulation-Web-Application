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
		isset($data->username) &&
		isset($data->isAdmin)
	) {
		$username = htmlspecialchars(strip_tags($data->username));
		$isAdmin = htmlspecialchars(strip_tags($data->isAdmin));

		if (createUser($username, $isAdmin)) {
			// set response code - 201 created
			http_response_code(201);

			// tell the user
			echo json_encode(array("message" => 0));
		} else {

			// set response code - 503 service unavailable
			http_response_code(503);

			// tell the user
			echo json_encode(array("message" => "Unable to create user."));
		}
	} else {

		// set response code - 400 bad request
		http_response_code(400);

		// tell the user
		echo json_encode(array("message" => "Unable to create user. Data is incomplete."));
	}
} else {
	$_SESSION["isAdmin"] = false;
	http_response_code(401);
	echo json_encode(array("message" => -1));
}

function createUser($username, $isAdmin)
{
	$userCreated = false;
	$db = new Database();
	$conn = $db->getConnection();
	$stmt = $conn->prepare("INSERT INTO users (username, isAdmin) VALUES (:username, :isAdmin)");
	$stmt->bindParam(':name', $username);
	$stmt->bindParam(':isAdmin', $isAdmin);
	$stmt->execute();
	$conn = null;
	$userCreated = true;
	return $userCreated;
}
