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

require_once __DIR__ . '/config.php';
require_once SITE_ROOT . "/database.php";

session_start();
$_SESSION["team"] = $_GET["t"];

try {
	$db = new Database();
	$conn = $db->getConnection();
	$stmt = $conn->prepare("SELECT number,name FROM teams WHERE url = :url");
	$stmt->bindParam(':url', $_SESSION["team"]);
	$stmt->execute();
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	$teamNumber = $result[0]["number"];
	$teamName = $result[0]["name"];
} catch (PDOException $e) {
	echo "Error: " . $e->getMessage();
}
$conn = null;
?>
<!DOCTYPE html>
<!--
Copyright (C) 2023 allen

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
-->
<html>

<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Team</title>
</head>

<body>
	<div>
		<div><?php echo $teamNumber . " " . $teamName ?></div>
		<div><a href="roster.html">Roster</a></div>
		<div><a href="captains.html">Captains Form</a></div>
		<div><a href="ballot.html">Ballot QR Code</a></div>
	</div>
</body>

</html>