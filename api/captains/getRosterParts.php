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
		$pairingStmt = $conn->prepare("SELECT pOpen,dOpen,pDirectingAttorney1,pDirectingAttorney2,pDirectingAttorney3,pCrossingAttorney1,pCrossingAttorney2,pCrossingAttorney3,pStudentWitness1,pStudentWitness2,pStudentWitness3,dDirectingAttorney1,dDirectingAttorney2,dDirectingAttorney3,dCrossingAttorney1,dCrossingAttorney2,dCrossingAttorney3,dStudentWitness1,dStudentWitness2,dStudentWitness3,pClose,dClose FROM pairings WHERE round = (SELECT Max(round) FROM pairings) && (plaintiff=:teamId || defense=:teamId)");
		$pairingStmt->bindParam(':teamId', $teamId);
		$pairingStmt->execute();
		$pairingResult = $pairingStmt->fetchAll(PDO::FETCH_ASSOC);
		echo json_encode($pairingResult[0]);
	} catch (PDOException $e) {
		echo "Error: " . $e->getMessage();
	}
	$conn = null;
} else {
	http_response_code(401);
	echo json_encode(array("message" => -1));
}
