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

	require_once __DIR__ . "/../../config.php";
	require_once SITE_ROOT . "/database.php";

	//initialize information to be returned
	$ballotData = [];
	$ballotData["pTeam"] = -1;
	$ballotData["dTeam"] = -1;
	$ballotData["judge"] = "";
	$ballotData["pOpen"] = -1;
	$ballotData["pDx1"] = -1;
	$ballotData["pDx2"] = -1;
	$ballotData["pDx3"] = -1;
	$ballotData["pCx1"] = -1;
	$ballotData["pCx2"] = -1;
	$ballotData["pCx3"] = -1;
	$ballotData["pWDx1"] = -1;
	$ballotData["pWDx2"] = -1;
	$ballotData["pWDx3"] = -1;
	$ballotData["pWCx1"] = -1;
	$ballotData["pWCx2"] = -1;
	$ballotData["pWCx3"] = -1;
	$ballotData["pClose"] = -1;
	$ballotData["pOpenCmts"] = "";
	$ballotData["pDx1Cmts"] = "";
	$ballotData["pDx2Cmts"] = "";
	$ballotData["pDx3Cmts"] = "";
	$ballotData["pCx1Cmts"] = "";
	$ballotData["pCx2Cmts"] = "";
	$ballotData["pCx3Cmts"] = "";
	$ballotData["pWDx1Cmts"] = "";
	$ballotData["pWDx2Cmts"] = "";
	$ballotData["pWDx3Cmts"] = "";
	$ballotData["pWCx1Cmts"] = "";
	$ballotData["pWCx2Cmts"] = "";
	$ballotData["pWCx3Cmts"] = "";
	$ballotData["pCloseCmts"] = "";
	$ballotData["dOpen"] = -1;
	$ballotData["dDx1"] = -1;
	$ballotData["dDx2"] = -1;
	$ballotData["dDx3"] = -1;
	$ballotData["dCx1"] = -1;
	$ballotData["dCx2"] = -1;
	$ballotData["dCx3"] = -1;
	$ballotData["dWDx1"] = -1;
	$ballotData["dWDx2"] = -1;
	$ballotData["dWDx3"] = -1;
	$ballotData["dWCx1"] = -1;
	$ballotData["dWCx2"] = -1;
	$ballotData["dWCx3"] = -1;
	$ballotData["dClose"] = -1;
	$ballotData["dOpenCmts"] = "";
	$ballotData["dDx1Cmts"] = "";
	$ballotData["dDx2Cmts"] = "";
	$ballotData["dDx3Cmts"] = "";
	$ballotData["dCx1Cmts"] = "";
	$ballotData["dCx2Cmts"] = "";
	$ballotData["dCx3Cmts"] = "";
	$ballotData["dWDx1Cmts"] = "";
	$ballotData["dWDx2Cmts"] = "";
	$ballotData["dWDx3Cmts"] = "";
	$ballotData["dWCx1Cmts"] = "";
	$ballotData["dWCx2Cmts"] = "";
	$ballotData["dWCx3Cmts"] = "";
	$ballotData["dCloseCmts"] = "";
	$ballotData["pOpenAttorney"] = "";
	$ballotData["pDx1Attorney"] = "";
	$ballotData["pDx2Attorney"] = "";
	$ballotData["pDx3Attorney"] = "";
	$ballotData["pCx1Attorney"] = "";
	$ballotData["pCx2Attorney"] = "";
	$ballotData["pCx3Attorney"] = "";
	$ballotData["pWDx1Student"] = "";
	$ballotData["pWDx2Student"] = "";
	$ballotData["pWDx3Student"] = "";
	$ballotData["pCloseAttorney"] = "";
	$ballotData["dOpenAttorney"] = "";
	$ballotData["dDx1Attorney"] = "";
	$ballotData["dDx2Attorney"] = "";
	$ballotData["dDx3Attorney"] = "";
	$ballotData["dCx1Attorney"] = "";
	$ballotData["dCx2Attorney"] = "";
	$ballotData["dCx3Attorney"] = "";
	$ballotData["dWDx1Student"] = "";
	$ballotData["dWDx2Student"] = "";
	$ballotData["dWDx3Student"] = "";
	$ballotData["dCloseAttorney"] = "";
	$ballotData["witness1"] = "";
	$ballotData["witness2"] = "";
	$ballotData["witness3"] = "";
	$ballotData["witness4"] = "";
	$ballotData["witness5"] = "";
	$ballotData["witness6"] = "";
	$ballotData["aty1"] = 0;
	$ballotData["aty2"] = 0;
	$ballotData["aty3"] = 0;
	$ballotData["aty4"] = 0;
	$ballotData["wit1"] = 0;
	$ballotData["wit2"] = 0;
	$ballotData["wit3"] = 0;
	$ballotData["wit4"] = 0;

	if (isset($_GET["ballot"])) { //if the user has a specific ballot in mind, get that ballot
		$ballotId = htmlspecialchars(strip_tags($_GET["ballot"]));
		$ballot = getBallotDataFromId($ballotId);
		$pairing = getPairingDataFromId($ballot["pairing"]);
		$pTeamNumber = getTeamNumberFromId($pairing["plaintiff"]);
		$dTeamNumber = getTeamNumberFromId($pairing["defense"]);

		$ballotData["pTeam"] = $pTeamNumber;
		$ballotData["dTeam"] = $dTeamNumber;
		$ballotData["judge"] = $ballot["judge"];
		$ballotData["pOpen"] = $ballot["pOpen"];
		$ballotData["pDx1"] = $ballot["pDx1"];
		$ballotData["pDx2"] = $ballot["pDx2"];
		$ballotData["pDx3"] = $ballot["pDx3"];
		$ballotData["pCx1"] = $ballot["pCx1"];
		$ballotData["pCx2"] = $ballot["pCx2"];
		$ballotData["pCx3"] = $ballot["pCx3"];
		$ballotData["pWDx1"] = $ballot["pWDx1"];
		$ballotData["pWDx2"] = $ballot["pWDx2"];
		$ballotData["pWDx3"] = $ballot["pWDx3"];
		$ballotData["pWCx1"] = $ballot["pWCx1"];
		$ballotData["pWCx2"] = $ballot["pWCx2"];
		$ballotData["pWCx3"] = $ballot["pWCx3"];
		$ballotData["pClose"] = $ballot["pClose"];
		$ballotData["pOpenComments"] = htmlspecialchars_decode($ballot["pOpenComments"]);
		$ballotData["pDx1Comments"] = htmlspecialchars_decode($ballot["pDx1Comments"]);
		$ballotData["pDx2Comments"] = htmlspecialchars_decode($ballot["pDx2Comments"]);
		$ballotData["pDx3Comments"] = htmlspecialchars_decode($ballot["pDx3Comments"]);
		$ballotData["pCx1Comments"] = htmlspecialchars_decode($ballot["pCx1Comments"]);
		$ballotData["pCx2Comments"] = htmlspecialchars_decode($ballot["pCx2Comments"]);
		$ballotData["pCx3Comments"] = htmlspecialchars_decode($ballot["pCx3Comments"]);
		$ballotData["pWDx1Comments"] = htmlspecialchars_decode($ballot["pWDx1Comments"]);
		$ballotData["pWDx2Comments"] = htmlspecialchars_decode($ballot["pWDx2Comments"]);
		$ballotData["pWDx3Comments"] = htmlspecialchars_decode($ballot["pWDx3Comments"]);
		$ballotData["pWCx1Comments"] = htmlspecialchars_decode($ballot["pWCx1Comments"]);
		$ballotData["pWCx2Comments"] = htmlspecialchars_decode($ballot["pWCx2Comments"]);
		$ballotData["pWCx3Comments"] = htmlspecialchars_decode($ballot["pWCx3Comments"]);
		$ballotData["pCloseComments"] = htmlspecialchars_decode($ballot["pCloseComments"]);
		$ballotData["dOpen"] = $ballot["dOpen"];
		$ballotData["dDx1"] = $ballot["dDx1"];
		$ballotData["dDx2"] = $ballot["dDx2"];
		$ballotData["dDx3"] = $ballot["dDx3"];
		$ballotData["dCx1"] = $ballot["dCx1"];
		$ballotData["dCx2"] = $ballot["dCx2"];
		$ballotData["dCx3"] = $ballot["dCx3"];
		$ballotData["dWDx1"] = $ballot["dWDx1"];
		$ballotData["dWDx2"] = $ballot["dWDx2"];
		$ballotData["dWDx3"] = $ballot["dWDx3"];
		$ballotData["dWCx1"] = $ballot["dWCx1"];
		$ballotData["dWCx2"] = $ballot["dWCx2"];
		$ballotData["dWCx3"] = $ballot["dWCx3"];
		$ballotData["dClose"] = $ballot["dClose"];
		$ballotData["dOpenComments"] = htmlspecialchars_decode($ballot["dOpenComments"]);
		$ballotData["dDx1Comments"] = htmlspecialchars_decode($ballot["dDx1Comments"]);
		$ballotData["dDx2Comments"] = htmlspecialchars_decode($ballot["dDx2Comments"]);
		$ballotData["dDx3Comments"] = htmlspecialchars_decode($ballot["dDx3Comments"]);
		$ballotData["dCx1Comments"] = htmlspecialchars_decode($ballot["dCx1Comments"]);
		$ballotData["dCx2Comments"] = htmlspecialchars_decode($ballot["dCx2Comments"]);
		$ballotData["dCx3Comments"] = htmlspecialchars_decode($ballot["dCx3Comments"]);
		$ballotData["dWDx1Comments"] = htmlspecialchars_decode($ballot["dWDx1Comments"]);
		$ballotData["dWDx2Comments"] = htmlspecialchars_decode($ballot["dWDx2Comments"]);
		$ballotData["dWDx3Comments"] = htmlspecialchars_decode($ballot["dWDx3Comments"]);
		$ballotData["dWCx1Comments"] = htmlspecialchars_decode($ballot["dWCx1Comments"]);
		$ballotData["dWCx2Comments"] = htmlspecialchars_decode($ballot["dWCx2Comments"]);
		$ballotData["dWCx3Comments"] = htmlspecialchars_decode($ballot["dWCx3Comments"]);
		$ballotData["dCloseComments"] = htmlspecialchars_decode($ballot["dCloseComments"]);
		$ballotData["pOpenAttorney"] = getRosterNameById($pairing["pOpen"]);
		$ballotData["pDx1Attorney"] = getRosterNameById($pairing["pDirectingAttorney1"]);
		$ballotData["pDx2Attorney"] = getRosterNameById($pairing["pDirectingAttorney2"]);
		$ballotData["pDx3Attorney"] = getRosterNameById($pairing["pDirectingAttorney3"]);
		$ballotData["pCx1Attorney"] = getRosterNameById($pairing["pCrossingAttorney1"]);
		$ballotData["pCx2Attorney"] = getRosterNameById($pairing["pCrossingAttorney2"]);
		$ballotData["pCx3Attorney"] = getRosterNameById($pairing["pCrossingAttorney3"]);
		$ballotData["pWDx1Student"] = getRosterNameById($pairing["pStudentWitness1"]);
		$ballotData["pWDx2Student"] = getRosterNameById($pairing["pStudentWitness2"]);
		$ballotData["pWDx3Student"] = getRosterNameById($pairing["pStudentWitness3"]);
		$ballotData["pCloseAttorney"] = getRosterNameById($pairing["pClose"]);
		$ballotData["dOpenAttorney"] = getRosterNameById($pairing["dOpen"]);
		$ballotData["dDx1Attorney"] = getRosterNameById($pairing["dDirectingAttorney1"]);
		$ballotData["dDx2Attorney"] = getRosterNameById($pairing["dDirectingAttorney2"]);
		$ballotData["dDx3Attorney"] = getRosterNameById($pairing["dDirectingAttorney3"]);
		$ballotData["dCx1Attorney"] = getRosterNameById($pairing["dCrossingAttorney1"]);
		$ballotData["dCx2Attorney"] = getRosterNameById($pairing["dCrossingAttorney2"]);
		$ballotData["dCx3Attorney"] = getRosterNameById($pairing["dCrossingAttorney3"]);
		$ballotData["dWDx1Student"] = getRosterNameById($pairing["dStudentWitness1"]);
		$ballotData["dWDx2Student"] = getRosterNameById($pairing["dStudentWitness2"]);
		$ballotData["dWDx3Student"] = getRosterNameById($pairing["dStudentWitness3"]);
		$ballotData["dCloseAttorney"] = getRosterNameById($pairing["dClose"]);
		$ballotData["pDx1AttorneyId"] = $pairing["pDirectingAttorney1"];
		$ballotData["pDx2AttorneyId"] = $pairing["pDirectingAttorney2"];
		$ballotData["pDx3AttorneyId"] = $pairing["pDirectingAttorney3"];
		$ballotData["dDx1AttorneyId"] = $pairing["dDirectingAttorney1"];
		$ballotData["dDx2AttorneyId"] = $pairing["dDirectingAttorney2"];
		$ballotData["dDx3AttorneyId"] = $pairing["dDirectingAttorney3"];
		$ballotData["pWitness1Id"] = $pairing["pStudentWitness1"];
		$ballotData["pWitness2Id"] = $pairing["pStudentWitness2"];
		$ballotData["pWitness3Id"] = $pairing["pStudentWitness3"];
		$ballotData["dWitness1Id"] = $pairing["dStudentWitness1"];
		$ballotData["dWitness2Id"] = $pairing["dStudentWitness2"];
		$ballotData["dWitness3Id"] = $pairing["dStudentWitness3"];
		$ballotData["witness1"] = $pairing["pWitness1"];
		$ballotData["witness2"] = $pairing["pWitness2"];
		$ballotData["witness3"] = $pairing["pWitness3"];
		$ballotData["witness4"] = $pairing["dWitness1"];
		$ballotData["witness5"] = $pairing["dWitness2"];
		$ballotData["witness6"] = $pairing["dWitness3"];
		$ballotData["aty1"] = $ballot["aty1"];
		$ballotData["aty2"] = $ballot["aty2"];
		$ballotData["aty3"] = $ballot["aty3"];
		$ballotData["aty4"] = $ballot["aty4"];
		$ballotData["wit1"] = $ballot["wit1"];
		$ballotData["wit2"] = $ballot["wit2"];
		$ballotData["wit3"] = $ballot["wit3"];
		$ballotData["wit4"] = $ballot["wit4"];

		echo json_encode($ballotData);
	}
} else {
	$_SESSION["isAdmin"] = false;
	http_response_code(401);
	echo json_encode(array("message" => -1));
}

function getBallotDataFromId($ballotId)
{
	try {
		$db = new Database();
		$conn = $db->getConnection();
		$ballotStmt = $conn->prepare("SELECT * FROM ballots WHERE id=:id");
		$ballotStmt->bindParam(':id', $ballotId);
		$ballotStmt->execute();
		$ballotResult = $ballotStmt->fetchAll(PDO::FETCH_ASSOC);
		$ballot = $ballotResult[0];
		$conn = null;
		return $ballot;
	} catch (PDOException $e) {
		echo "Error: " . $e->getMessage();
	}
}

function getPairingDataFromId($pairingId)
{
	try {
		$db = new Database();
		$conn = $db->getConnection();
		$stmt = $conn->prepare("SELECT id,round,plaintiff,defense,pOpen,dOpen,pDirectingAttorney1,pDirectingAttorney2,pDirectingAttorney3,pCrossingAttorney1,pCrossingAttorney2,pCrossingAttorney3,pStudentWitness1,pStudentWitness2,pStudentWitness3,dDirectingAttorney1,dDirectingAttorney2,dDirectingAttorney3,dCrossingAttorney1,dCrossingAttorney2,dCrossingAttorney3,dStudentWitness1,dStudentWitness2,dStudentWitness3,pClose,dClose,pWitness1,pWitness2,pWitness3,dWitness1,dWitness2,dWitness3 FROM pairings WHERE id=:pairingId");
		$stmt->bindParam(':pairingId', $pairingId);
		$stmt->execute();
		$pairingResult = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$pairing = $pairingResult[0];
		$conn = null;
		return $pairing;
	} catch (PDOException $e) {
		echo "Error: " . $e->getMessage();
	}
}

function getPairingDataFromURL($pairingURL)
{
	try {
		$db = new Database();
		$conn = $db->getConnection();
		$stmt = $conn->prepare("SELECT id,round,plaintiff,defense,pOpen,dOpen,pDirectingAttorney1,pDirectingAttorney2,pDirectingAttorney3,pCrossingAttorney1,pCrossingAttorney2,pCrossingAttorney3,pStudentWitness1,pStudentWitness2,pStudentWitness3,dDirectingAttorney1,dDirectingAttorney2,dDirectingAttorney3,dCrossingAttorney1,dCrossingAttorney2,dCrossingAttorney3,dStudentWitness1,dStudentWitness2,dStudentWitness3,pClose,dClose,pWitness1,pWitness2,pWitness3,dWitness1,dWitness2,dWitness3 FROM pairings WHERE url=:url");
		$stmt->bindParam(':url', $pairingURL);
		$stmt->execute();
		$pairingResult = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$pairing = $pairingResult[0];
		$conn = null;
		return $pairing;
	} catch (PDOException $e) {
		echo "Error: " . $e->getMessage();
	}
}

function getTeamNumberFromId($teamId)
{
	try {
		$db = new Database();
		$conn = $db->getConnection();
		$teamNumberStmt = $conn->prepare("SELECT number FROM teams WHERE id=:id");
		$teamNumberStmt->bindParam(':id', $teamId);
		$teamNumberStmt->execute();
		$result = $teamNumberStmt->fetchAll(PDO::FETCH_ASSOC);
		$teamNumber = $result[0]["number"];
		$conn = null;
		return $teamNumber;
	} catch (PDOException $e) {
		echo "Error: " . $e->getMessage();
	}
}

function getRosterNameById($studentId)
{
	if ($studentId == 0) {
		return "";
	} else {
		try {
			$db = new Database();
			$conn = $db->getConnection();
			$rosterStmt = $conn->prepare("SELECT student FROM rosters WHERE id=:id");
			$rosterStmt->bindParam(':id', $studentId);
			$rosterStmt->execute();
			$result = $rosterStmt->fetchAll(PDO::FETCH_ASSOC);
			$name = $result[0]["student"];
			$conn = null;
			return $name;
		} catch (PDOException $e) {
			echo "Error: " . $e->getMessage();
		}
	}
}
