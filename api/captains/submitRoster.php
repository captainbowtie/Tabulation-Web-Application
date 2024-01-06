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
		isset($_POST["isPlaintiff"]) &&
		isset($_POST["open"]) &&
		isset($_POST["dx1"]) &&
		isset($_POST["dx2"]) &&
		isset($_POST["dx3"]) &&
		isset($_POST["cx1"]) &&
		isset($_POST["cx2"]) &&
		isset($_POST["cx3"]) &&
		isset($_POST["wit1"]) &&
		isset($_POST["wit2"]) &&
		isset($_POST["wit3"]) &&
		isset($_POST["close"])
	) {
		$teamURL = htmlspecialchars(strip_tags($_SESSION["team"]));
		$isPlaintiff = htmlspecialchars(strip_tags($_POST["isPlaintiff"]));
		$open = htmlspecialchars(strip_tags($_POST["open"]));
		$dx1 = htmlspecialchars(strip_tags($_POST["dx1"]));
		$dx2 = htmlspecialchars(strip_tags($_POST["dx2"]));
		$dx3 = htmlspecialchars(strip_tags($_POST["dx3"]));
		$cx1 = htmlspecialchars(strip_tags($_POST["cx1"]));
		$cx2 = htmlspecialchars(strip_tags($_POST["cx2"]));
		$cx3 = htmlspecialchars(strip_tags($_POST["cx3"]));
		$wit1 = htmlspecialchars(strip_tags($_POST["wit1"]));
		$wit2 = htmlspecialchars(strip_tags($_POST["wit2"]));
		$wit3 = htmlspecialchars(strip_tags($_POST["wit3"]));
		$close = htmlspecialchars(strip_tags($_POST["close"]));

		if (submitRoster($teamURL, $isPlaintiff, $open, $dx1, $dx2, $dx3, $cx1, $cx2, $cx3, $wit1, $wit2, $wit3, $close)) {
			// set response code - 201 created
			http_response_code(201);

			// tell the user
			echo json_encode(array("message" => 0));
		} else {

			// set response code - 503 service unavailable
			http_response_code(503);

			// tell the user
			echo json_encode(array("message" => "Unable to update rosters."));
		}
	} else {

		// set response code - 400 bad request
		http_response_code(400);

		// tell the user
		echo json_encode(array("message" => "Unable to update rosters. Data is incomplete."));
	}
} else {
	http_response_code(401);
	echo json_encode(array("message" => -1));
}

function submitRoster($teamURL, $isPlaintiff, $open, $dx1, $dx2, $dx3, $cx1, $cx2, $cx3, $wit1, $wit2, $wit3, $close)
{
	$rosterSubmitted = false;
	$db = new Database();
	$conn = $db->getConnection();

	//get id of relevant team
	$teamStmt = $conn->prepare("SELECT id FROM teams WHERE url = :url");
	$teamStmt->bindParam(':url', $teamURL);
	$teamStmt->execute();
	$teamResult = $teamStmt->fetchAll(PDO::FETCH_ASSOC);
	$teamId = $teamResult[0]["id"];

	//add students to pairing data
	$query = "";
	if ($isPlaintiff) {
		$query = "UPDATE pairings SET pOpen=:open, pDirectingAttorney1=:dx1, pDirectingAttorney2=:dx2, pDirectingAttorney3=:dx3, pCrossingAttorney1=:cx1, pCrossingAttorney2=:cx2, pCrossingAttorney3=:cx3, pStudentWitness1=:wit1, pStudentWitness2=:wit2, pStudentWitness3=:wit3, pClose=:close WHERE plaintiff = :teamId && round = (SELECT Max(round) FROM pairings)";
	} else {
		$query = "UPDATE pairings SET dOpen=:open, dDirectingAttorney1=:dx1, dDirectingAttorney2=:dx2, dDirectingAttorney3=:dx3, dCrossingAttorney1=:cx1, dCrossingAttorney2=:cx2, dCrossingAttorney3=:cx3, dStudentWitness1=:wit1, dStudentWitness2=:wit2, dStudentWitness3=:wit3, dClose=:close WHERE defense = :teamId && round = (SELECT Max(round) FROM pairings)";
	}
	$pairingStmt = $conn->prepare($query);
	$pairingStmt->bindParam(':open', $open);
	$pairingStmt->bindParam(':dx1', $dx1);
	$pairingStmt->bindParam(':dx2', $dx2);
	$pairingStmt->bindParam(':dx3', $dx3);
	$pairingStmt->bindParam(':cx1', $cx1);
	$pairingStmt->bindParam(':cx2', $cx2);
	$pairingStmt->bindParam(':cx3', $cx3);
	$pairingStmt->bindParam(':wit1', $wit1);
	$pairingStmt->bindParam(':wit2', $wit2);
	$pairingStmt->bindParam(':wit3', $wit3);
	$pairingStmt->bindParam(':close', $close);
	$pairingStmt->bindParam(':teamId', $teamId);
	$pairingStmt->execute();

	$conn = null;
	$rosterSubmitted = true;
	return $rosterSubmitted;
}
