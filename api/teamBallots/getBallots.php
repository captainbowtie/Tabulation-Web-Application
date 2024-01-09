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

if (isset($_GET["user"])) {
	require_once __DIR__ . "/../../config.php";
	require_once SITE_ROOT . "/database.php";

	$user = htmlspecialchars(strip_tags($_GET["user"]));
	$db = new Database();
	$conn = $db->getConnection();

	$userStmt = $conn->prepare("SELECT id FROM users WHERE url=:url");
	$userStmt->bindParam(':url', $user);
	$userStmt->execute();
	$userResult = $userStmt->fetchAll(PDO::FETCH_ASSOC);
	$userId = $userResult[0]["id"];

	$userTeamStatement = $conn->prepare("SELECT team FROM userTeams WHERE user=:userId");
	$userTeamStatement->bindParam(':userId', $userId);
	$userTeamStatement->execute();
	$userTeamResult = $userTeamStatement->fetchAll(PDO::FETCH_ASSOC);

	$ballotStmt = $conn->prepare("SELECT id,pairing,judge,pOpen,pDx1,pDx2,pDx3,pCx1,pCx2,pCx3,pWDx1,pWDx2,pWDx3,pWCx1,pWCx2,pWCx3,pClose,dOpen,dDx1,dDx2,dDx3,dCx1,dCx2,dCx3,dWDx1,dWDx2,dWDx3,dWCx1,dWCx2,dWCx3,dClose,pOpenComments,pDx1Comments,pDx2Comments,pDx3Comments,pCx1Comments,pCx2Comments,pCx3Comments,pWDx1Comments,pWDx2Comments,pWDx3Comments,pWCx1Comments,pWCx2Comments,pWCx3Comments,pCloseComments,dOpenComments,dDx1Comments,dDx2Comments,dDx3Comments,dCx1Comments,dCx2Comments,dCx3Comments,dWDx1Comments,dWDx2Comments,dWDx3Comments,dWCx1Comments,dWCx2Comments,dWCx3Comments,dCloseComments,aty1,aty2,aty3,aty4,wit1,wit2,wit3,wit4,releaseComments,releaseScores FROM ballots");
	$ballotStmt->execute();
	$ballotResult = $ballotStmt->fetchAll(PDO::FETCH_ASSOC);

	$pairingStmt = $conn->prepare("SELECT id,round,plaintiff,defense,pOpen,dOpen,pDirectingAttorney1,pDirectingAttorney2,pDirectingAttorney3,pCrossingAttorney1,pCrossingAttorney2,pCrossingAttorney3,pStudentWitness1,pStudentWitness2,pStudentWitness3,dDirectingAttorney1,dDirectingAttorney2,dDirectingAttorney3,dCrossingAttorney1,dCrossingAttorney2,dCrossingAttorney3,dStudentWitness1,dStudentWitness2,dStudentWitness3,pClose,dClose,pWitness1,pWitness2,pWitness3,dWitness1,dWitness2,dWitness3 FROM pairings");
	$pairingStmt->execute();
	$pairingResult = $pairingStmt->fetchAll(PDO::FETCH_ASSOC);

	$rosterStmt = $conn->prepare("SELECT id,team,student FROM rosters");
	$rosterStmt->execute();
	$rosterResult = $rosterStmt->fetchAll(PDO::FETCH_ASSOC);

	$teamStmt = $conn->prepare("SELECT id,number,name,url FROM teams");
	$teamStmt->execute();
	$teamResult = $teamStmt->fetchAll(PDO::FETCH_ASSOC);

	$data = [];
	foreach ($ballotResult as &$ballot) {
		if ($ballot["releaseScores"] || $ballot["releaseComments"]) {
			$ballotData = [];

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

			$ballotData["judge"] = $ballot["judge"];
			$pairing = "";
			//find pairing for the ballot
			for ($pairingIndex = 0; $pairingIndex < sizeof($pairingResult); $pairingIndex++) {
				if ($pairingResult[$pairingIndex]["id"] == $ballot["pairing"]) {
					$pairing = $pairingResult[$pairingIndex];
				}
			}

			$ballotData["round"] = $pairing["round"];
			$isUser = false;
			for ($a = 0; $a < sizeof($teamResult); $a++) {
				if ($teamResult[$a]["id"] == $pairing["plaintiff"]) {
					$ballotData["pTeamNumber"] = $teamResult[$a]["number"];
					$ballotData["pTeamName"] = $teamResult[$a]["name"];
					for ($b = 0; $b < sizeof($userTeamResult); $b++) {
						if ($teamResult[$a]["id"] == $userTeamResult[$b]["team"]) {
							$isUser = true;
						}
					}
				}
				if ($teamResult[$a]["id"] == $pairing["defense"]) {
					$ballotData["dTeamNumber"] = $teamResult[$a]["number"];
					$ballotData["dTeamName"] = $teamResult[$a]["name"];
					for ($b = 0; $b < sizeof($userTeamResult); $b++) {
						if ($teamResult[$a]["id"] == $userTeamResult[$b]["team"]) {
							$isUser = true;
						}
					}
				}
			}
			if ($ballot["releaseScores"]) {
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
				$ballotData["aty1"] = 0;
				$ballotData["aty2"] = 0;
				$ballotData["aty3"] = 0;
				$ballotData["aty4"] = 0;
				$ballotData["wit1"] = 0;
				$ballotData["wit2"] = 0;
				$ballotData["wit3"] = 0;
				$ballotData["wit4"] = 0;
			}
			if ($ballot["releaseComments"] && $isUser) {
				$ballotData["pOpenCmts"] = $ballot["pOpenComments"];
				$ballotData["pDx1Cmts"] = $ballot["pDx1Comments"];
				$ballotData["pDx2Cmts"] = $ballot["pDx2Comments"];
				$ballotData["pDx3Cmts"] = $ballot["pDx3Comments"];
				$ballotData["pCx1Cmts"] = $ballot["pCx1Comments"];
				$ballotData["pCx2Cmts"] = $ballot["pCx2Comments"];
				$ballotData["pCx3Cmts"] = $ballot["pCx3Comments"];
				$ballotData["pWDx1Cmts"] = $ballot["pWDx1Comments"];
				$ballotData["pWDx2Cmts"] = $ballot["pWDx2Comments"];
				$ballotData["pWDx3Cmts"] = $ballot["pWDx3Comments"];
				$ballotData["pWCx1Cmts"] = $ballot["pWCx1Comments"];
				$ballotData["pWCx2Cmts"] = $ballot["pWCx2Comments"];
				$ballotData["pWCx3Cmts"] = $ballot["pWCx3Comments"];
				$ballotData["pCloseCmts"] = $ballot["pCloseComments"];

				$ballotData["dOpenCmts"] = $ballot["dOpenComments"];
				$ballotData["dDx1Cmts"] = $ballot["dDx1Comments"];
				$ballotData["dDx2Cmts"] = $ballot["dDx2Comments"];
				$ballotData["dDx3Cmts"] = $ballot["dDx3Comments"];
				$ballotData["dCx1Cmts"] = $ballot["dCx1Comments"];
				$ballotData["dCx2Cmts"] = $ballot["dCx2Comments"];
				$ballotData["dCx3Cmts"] = $ballot["dCx3Comments"];
				$ballotData["dWDx1Cmts"] = $ballot["dWDx1Comments"];
				$ballotData["dWDx2Cmts"] = $ballot["dWDx2Comments"];
				$ballotData["dWDx3Cmts"] = $ballot["dWDx3Comments"];
				$ballotData["dWCx1Cmts"] = $ballot["dWCx1Comments"];
				$ballotData["dWCx2Cmts"] = $ballot["dWCx2Comments"];
				$ballotData["dWCx3Cmts"] = $ballot["dWCx3Comments"];
				$ballotData["dCloseCmts"] = $ballot["dCloseComments"];


				$ballotData["pOpenAttorney"] = getNameFromRosterId($rosterResult, $pairing["pOpen"]);
				$ballotData["pDx1Attorney"] = getNameFromRosterId($rosterResult, $pairing["pDirectingAttorney1"]);
				$ballotData["pDx2Attorney"] = getNameFromRosterId($rosterResult, $pairing["pDirectingAttorney2"]);
				$ballotData["pDx3Attorney"] = getNameFromRosterId($rosterResult, $pairing["pDirectingAttorney3"]);
				$ballotData["pCx1Attorney"] = getNameFromRosterId($rosterResult, $pairing["pCrossingAttorney1"]);
				$ballotData["pCx2Attorney"] = getNameFromRosterId($rosterResult, $pairing["pCrossingAttorney2"]);
				$ballotData["pCx3Attorney"] = getNameFromRosterId($rosterResult, $pairing["pCrossingAttorney3"]);
				$ballotData["pWDx1Student"] = getNameFromRosterId($rosterResult, $pairing["pStudentWitness1"]);
				$ballotData["pWDx2Student"] = getNameFromRosterId($rosterResult, $pairing["pStudentWitness2"]);
				$ballotData["pWDx3Student"] = getNameFromRosterId($rosterResult, $pairing["pStudentWitness3"]);
				$ballotData["pCloseAttorney"] = getNameFromRosterId($rosterResult, $pairing["pClose"]);
				$ballotData["dOpenAttorney"] = getNameFromRosterId($rosterResult, $pairing["dOpen"]);
				$ballotData["dDx1Attorney"] = getNameFromRosterId($rosterResult, $pairing["dDirectingAttorney1"]);
				$ballotData["dDx2Attorney"] = getNameFromRosterId($rosterResult, $pairing["dDirectingAttorney2"]);
				$ballotData["dDx3Attorney"] = getNameFromRosterId($rosterResult, $pairing["dDirectingAttorney3"]);
				$ballotData["dCx1Attorney"] = getNameFromRosterId($rosterResult, $pairing["dCrossingAttorney1"]);
				$ballotData["dCx2Attorney"] = getNameFromRosterId($rosterResult, $pairing["dCrossingAttorney2"]);
				$ballotData["dCx3Attorney"] = getNameFromRosterId($rosterResult, $pairing["dCrossingAttorney3"]);
				$ballotData["dWDx1Student"] = getNameFromRosterId($rosterResult, $pairing["dStudentWitness1"]);
				$ballotData["dWDx2Student"] = getNameFromRosterId($rosterResult, $pairing["dStudentWitness2"]);
				$ballotData["dWDx3Student"] = getNameFromRosterId($rosterResult, $pairing["dStudentWitness3"]);
				$ballotData["dCloseAttorney"] = getNameFromRosterId($rosterResult, $pairing["dClose"]);


				$ballotData["witness1"] = $pairing["pWitness1"];
				$ballotData["witness2"] = $pairing["pWitness2"];
				$ballotData["witness3"] = $pairing["pWitness3"];
				$ballotData["witness4"] = $pairing["dWitness1"];
				$ballotData["witness5"] = $pairing["dWitness2"];
				$ballotData["witness6"] = $pairing["dWitness3"];
			}
			array_push($data, $ballotData);
		}
	}

	$conn = null;

	echo json_encode($data);
}

function getNameFromRosterId($roster, $rosterId)
{
	$name = "";
	for ($a = 0; $a < sizeof($roster); $a++) {
		if ($roster[$a]["id"] == $rosterId) {
			$name = $roster[$a]["student"];
		}
	}
	return $name;
}
