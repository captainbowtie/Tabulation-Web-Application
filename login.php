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

require_once __DIR__ . "/config.php";
require_once SITE_ROOT . "/database.php";

if (
	isset($_GET['url'])
) {
	$url = htmlspecialchars($_GET['url']);
	$user = getUserByURL($url);
	if ($user["username"] == "") {
		echo "Error: invalid login URL";
		$_SESSION["isAdmin"] = 0;
		$_SESSION["id"] = 0;
	} else {
		$_SESSION["id"] = $user["id"];
		$_SESSION["username"] = $user["username"];
		$_SESSION["isAdmin"] = $user["isAdmin"];
		header("Location: index.php");
	}
} else {
	// tell the user
	echo json_encode(array("message" => "Unable to login. Data is incomplete."));
}

function getUserByURL($url)
{
	try {
		$db = new Database();
		$conn = $db->getConnection();
		$stmt = $conn->prepare("SELECT id,username,isAdmin FROM users WHERE url = :url");
		$stmt->bindParam(':url', $url);
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		$result = $stmt->fetchAll();
		return $result[0];
	} catch (PDOException $e) {
		echo "Error: " . $e->getMessage();
	}
	$conn = null;
}
