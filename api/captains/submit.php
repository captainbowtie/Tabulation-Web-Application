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
		isset($_POST["portaIsDefendant"]) &&
		isset($_POST["count1"]) &&
		isset($_POST["count2"]) &&
		isset($_POST["count3"]) &&
		isset($_POST["count4"]) &&
		isset($_POST["count5"]) &&
		isset($_POST["duress"]) &&
		isset($_POST["pWitness1"]) &&
		isset($_POST["pWitness2"]) &&
		isset($_POST["pWitness3"]) &&
		isset($_POST["dWitness1"]) &&
		isset($_POST["dWitness2"]) &&
		isset($_POST["dWitness3"]) &&
		isset($_POST["pCall1"]) &&
		isset($_POST["pCall2"]) &&
		isset($_POST["pCall3"]) &&
		isset($_POST["dCall1"]) &&
		isset($_POST["dCall2"]) &&
		isset($_POST["dCall3"])
	) {
		$teamURL = htmlspecialchars(strip_tags($_SESSION["team"]));
		$portaIsDefendant = htmlspecialchars(strip_tags($_POST["portaIsDefendant"]));
		$count1 = htmlspecialchars(strip_tags($_POST["count1"]));
		$count2 = htmlspecialchars(strip_tags($_POST["count2"]));
		$count3 = htmlspecialchars(strip_tags($_POST["count3"]));
		$count4 = htmlspecialchars(strip_tags($_POST["count4"]));
		$count5 = htmlspecialchars(strip_tags($_POST["count5"]));
		$duress = htmlspecialchars(strip_tags($_POST["duress"]));
		$pWitness1 = htmlspecialchars(strip_tags($_POST["pWitness1"]));
		$pWitness2 = htmlspecialchars(strip_tags($_POST["pWitness2"]));
		$pWitness3 = htmlspecialchars(strip_tags($_POST["pWitness3"]));
		$dWitness1 = htmlspecialchars(strip_tags($_POST["dWitness1"]));
		$dWitness2 = htmlspecialchars(strip_tags($_POST["dWitness2"]));
		$dWitness3 = htmlspecialchars(strip_tags($_POST["dWitness3"]));
		$pCall1 = htmlspecialchars(strip_tags($_POST["pCall1"]));
		$pCall2 = htmlspecialchars(strip_tags($_POST["pCall2"]));
		$pCall3 = htmlspecialchars(strip_tags($_POST["pCall3"]));
		$dCall1 = htmlspecialchars(strip_tags($_POST["dCall1"]));
		$dCall2 = htmlspecialchars(strip_tags($_POST["dCall2"]));
		$dCall3 = htmlspecialchars(strip_tags($_POST["dCall3"]));

		if (submitCaptains($teamURL, $portaIsDefendant, $count1, $count2, $count3, $count4, $count5, $duress, $pWitness1, $pWitness2, $pWitness3, $dWitness1, $dWitness2, $dWitness3, $pCall1, $pCall2, $pCall3, $dCall1, $dCall2, $dCall3)) {
			// set response code - 201 created
			http_response_code(201);

			// tell the user
			echo json_encode(array("message" => 0));
		} else {

			// set response code - 503 service unavailable
			http_response_code(503);

			// tell the user
			echo json_encode(array("message" => "Unable to create conflict."));
		}
	} else {

		// set response code - 400 bad request
		http_response_code(400);

		// tell the user
		echo json_encode(array("message" => "Unable to create student. Data is incomplete."));
	}
} else {
	http_response_code(401);
	echo json_encode(array("message" => -1));
}

function submitCaptains($teamURL, $defendant, $count1, $count2, $count3, $count4, $count5, $duress, $pWitness1, $pWitness2, $pWitness3, $dWitness1, $dWitness2, $dWitness3, $pCall1, $pCall2, $pCall3, $dCall1, $dCall2, $dCall3)
{
	$captainsSubmitted = false;
	$db = new Database();
	$conn = $db->getConnection();

	//get id of relevant team
	$teamStmt = $conn->prepare("SELECT id FROM teams WHERE url = :url");
	$teamStmt->bindParam(':url', $teamURL);
	$teamStmt->execute();
	$teamResult = $teamStmt->fetchAll(PDO::FETCH_ASSOC);
	$teamId = $teamResult[0]["id"];

	//query pairings to get pairing id
	$pairingStmt = $conn->prepare("SELECT id FROM pairings WHERE round = (SELECT Max(round) FROM pairings) && plaintiff=:teamId");
	$pairingStmt->bindParam(':teamId', $teamId);
	$pairingStmt->execute();
	$pairingResult = $pairingStmt->fetchAll(PDO::FETCH_ASSOC);
	$pairingId = $pairingResult[0]["id"];

	//put witnesses into pairing
	$stmt = $conn->prepare("UPDATE pairings SET pWitness1=:pWitness1, pWitness2=:pWitness2, pWitness3=:pWitness3, dWitness1=:dWitness1, dWitness2=:dWitness2, dWitness3=:dWitness3 WHERE id=:id");
	$stmt->bindParam(':pWitness1', $pCall1);
	$stmt->bindParam(':pWitness2', $pCall2);
	$stmt->bindParam(':pWitness3', $pCall3);
	$stmt->bindParam(':dWitness1', $dCall1);
	$stmt->bindParam(':dWitness2', $dCall2);
	$stmt->bindParam(':dWitness3', $dCall3);
	$stmt->bindParam(':id', $pairingId);
	$stmt->execute();

	//put captains information into captains
	$capStmt = $conn->prepare("INSERT INTO captains (pairing,prosecutingPorta,count1,count2,count3,count4,count5,duress,pWitness1,pWitness2,pWitness3,dWitness1,dWitness2,dWitness3) VALUES(:pairing,:defendant,:count1,:count2,:count3,:count4,:count5,:duress,:pWitness1,:pWitness2,:pWitness3,:dWitness1,:dWitness2,:dWitness3)");
	$capStmt->bindParam(':pairing', $pairingId);
	$capStmt->bindParam(':defendant', $defendant);
	$capStmt->bindParam(':count1', $count1);
	$capStmt->bindParam(':count2', $count2);
	$capStmt->bindParam(':count3', $count3);
	$capStmt->bindParam(':count4', $count4);
	$capStmt->bindParam(':count5', $count5);
	$capStmt->bindParam(':duress', $duress);
	$capStmt->bindParam(':pWitness1', $pWitness1);
	$capStmt->bindParam(':pWitness2', $pWitness2);
	$capStmt->bindParam(':pWitness3', $pWitness3);
	$capStmt->bindParam(':dWitness1', $dWitness1);
	$capStmt->bindParam(':dWitness2', $dWitness2);
	$capStmt->bindParam(':dWitness3', $dWitness3);
	$capStmt->execute();

	$conn = null;
	$captainsSubmitted = true;
	return $captainsSubmitted;
}
