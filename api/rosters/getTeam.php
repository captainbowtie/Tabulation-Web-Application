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

require_once __DIR__ . "/../../config.php";
require_once SITE_ROOT . "/database.php";
session_start();
if (isset($_SESSION["team"])) {

	$teamURL = htmlspecialchars(strip_tags($_SESSION["team"]));

	try {
		$db = new Database();
		$conn = $db->getConnection();

		//get id of relevant team
		$stmt = $conn->prepare("SELECT id FROM teams WHERE url = :url");
		$stmt->bindParam(':url', $teamURL);
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$teamId = $result[0]["id"];

		//get members from that team
		$rosterStmt = $conn->prepare("SELECT * FROM rosters WHERE team = :team");
		$rosterStmt->bindParam(':team', $teamId);
		$rosterStmt->execute();
		echo json_encode($rosterStmt->fetchAll(PDO::FETCH_ASSOC));
	} catch (PDOException $e) {
		echo "Error: " . $e->getMessage();
	}
	$conn = null;
} else {
	http_response_code(401);
	echo json_encode(array("message" => -1));
}
