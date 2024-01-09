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

		//query pairings to get pairing id
		$pairingStmt = $conn->prepare("SELECT id,pWitness1,pWitness2,pWitness3,dWitness1,dWitness2,dWitness3 FROM pairings WHERE round = (SELECT Max(round) FROM pairings) && (plaintiff=:teamId || defense=:teamId)");
		$pairingStmt->bindParam(':teamId', $teamId);
		$pairingStmt->execute();
		$pairingResult = $pairingStmt->fetchAll(PDO::FETCH_ASSOC);
		$pairingId = $pairingResult[0]["id"];

		//get the captains data for that team
		$capStmt = $conn->prepare("SELECT prosecutingPorta,count1,count2,count3,count4,count5,duress,pWitness1,pWitness2,pWitness3,dWitness1,dWitness2,dWitness3 FROM captains WHERE pairing = :pairing");
		$capStmt->bindParam(':pairing', $pairingId);
		$capStmt->execute();
		$capResult = $capStmt->fetchAll(PDO::FETCH_ASSOC);

		$captainsData = array(
			"prosecutingPorta" => $capResult[0]["prosecutingPorta"],
			"count1" => $capResult[0]["count1"],
			"count2" => $capResult[0]["count2"],
			"count3" => $capResult[0]["count3"],
			"count4" => $capResult[0]["count4"],
			"count5" => $capResult[0]["count5"],
			"duress" => $capResult[0]["duress"],
			"pWitness1" => $capResult[0]["pWitness1"],
			"pWitness2" => $capResult[0]["pWitness2"],
			"pWitness3" => $capResult[0]["pWitness3"],
			"dWitness1" => $capResult[0]["dWitness1"],
			"dWitness2" => $capResult[0]["dWitness2"],
			"dWitness3" => $capResult[0]["dWitness3"],
			"pCall1" => $pairingResult[0]["pWitness1"],
			"pCall2" => $pairingResult[0]["pWitness2"],
			"pCall3" => $pairingResult[0]["pWitness3"],
			"dCall1" => $pairingResult[0]["dWitness1"],
			"dCall2" => $pairingResult[0]["dWitness2"],
			"dCall3" => $pairingResult[0]["dWitness3"]
		);
		echo json_encode($captainsData);
	} catch (PDOException $e) {
		echo "Error: " . $e->getMessage();
	}
	$conn = null;
} else {
	http_response_code(401);
	echo json_encode(array("message" => -1));
}
