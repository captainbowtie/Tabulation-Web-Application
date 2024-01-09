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
	$stmt = $conn->prepare("SELECT id,number,name FROM teams WHERE url = :url");
	$stmt->bindParam(':url', $_SESSION["team"]);
	$stmt->execute();
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	$teamId = $result[0]["id"];
	$teamNumber = $result[0]["number"];
	$teamName = $result[0]["name"];

	$pairingStmt = $conn->prepare("SELECT url FROM pairings WHERE round = (SELECT Max(round) FROM pairings) && (plaintiff=:teamId || defense=:teamId)");
	$pairingStmt->bindParam(':teamId', $teamId);
	$pairingStmt->execute();
	$pairingResult = $pairingStmt->fetchAll(PDO::FETCH_ASSOC);
	$pairingURL = $pairingResult[0]["url"];
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
	<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js" integrity="sha512-CNgIRecGo7nphbeZ04Sc13ka07paqdeTu0WR1IM4kNcpmBAUSHSQX0FslNhTDadL4O5SAGapGt4FodqL8My0mA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>

<body>
	<div>
		<div><?php echo $teamNumber . " " . $teamName ?></div>
		<div><a href="roster.html">Roster</a></div>
		<div><a href="captains.html">Captains Form</a></div>
		<div><a href="b.php?p=<?php echo $pairingURL ?>">Ballot Link</a></div>
		<div id='qr'></div>
	</div>
	<script>
		let pairingURL = '<?php echo $pairingURL ?>';
	</script>
	<script src="team.js"></script>
</body>

</html>