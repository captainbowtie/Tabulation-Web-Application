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

require_once __DIR__ . "/../../config.php";
require_once SITE_ROOT . "/database.php";
session_start();
if ($_SESSION["isAdmin"]) {
	if (isset($_POST["ballot"]) && isset($_POST["name"])) {
		$name = htmlspecialchars(strip_tags($_POST["name"]));
		$ballot = htmlspecialchars(strip_tags($_POST["ballot"]));


		$query = "UPDATE ballots SET judge = :name WHERE id=:id";

		try {
			$db = new Database();
			$conn = $db->getConnection();
			$stmt = $conn->prepare($query);
			$stmt->bindParam(':id', $ballot);
			$stmt->bindParam(':name', $name);
			$stmt->execute();
			$conn = null;
			echo json_encode(array("message" => 0));
		} catch (PDOException $e) {
			echo "Error: " . $e->getMessage();
		}
	}
} else {
	$_SESSION["isAdmin"] = false;
	http_response_code(401);
	echo json_encode(array("message" => -1));
}
