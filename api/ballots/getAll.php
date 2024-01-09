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
	try {
		$db = new Database();
		$conn = $db->getConnection();
		$ballotStmt = $conn->prepare("SELECT id,pairing,judge,releaseComments,releaseScores,locked,url, (CAST(pOpen AS SIGNED)+CAST(pDx1 AS SIGNED)+CAST(pDx2 AS SIGNED)+CAST(pDx3 AS SIGNED)+CAST(pWDx1 AS SIGNED)+CAST(pWDx2 AS SIGNED)+CAST(pWDx3 AS SIGNED)+CAST(pWCx1 AS SIGNED)+CAST(pWDx2 AS SIGNED)+CAST(pWDx3 AS SIGNED)+CAST(pCx1 AS SIGNED)+CAST(pCx2 AS SIGNED)+CAST(pCx3 AS SIGNED)+CAST(pClose AS SIGNED))-(CAST(dOpen AS SIGNED)+CAST(dDx1 AS SIGNED)+CAST(dDx2 AS SIGNED)+CAST(dDx3 AS SIGNED)+CAST(dWDx1 AS SIGNED)+CAST(dWDx2 AS SIGNED)+CAST(dWDx3 AS SIGNED)+CAST(dWCx1 AS SIGNED)+CAST(dWCx2 AS SIGNED)+CAST(dWCx3 AS SIGNED)+CAST(dCx1 AS SIGNED)+CAST(dCx2 AS SIGNED)+CAST(dCx3 AS SIGNED)+CAST(dClose AS SIGNED)) AS 'plaintiffPD' FROM ballots; ");
		$ballotStmt->execute();
		$ballots = $ballotStmt->fetchAll(PDO::FETCH_ASSOC);

		$pairingStmt = $conn->prepare("SELECT id,room,plaintiff,defense,round FROM pairings WHERE id=:pairingId");
		$teamStmt = $conn->prepare("SELECT number FROM teams WHERE id=:teamId");

		$data = array();
		foreach ($ballots as &$ballot) {

			$pairingStmt->bindParam(':pairingId', $ballot["pairing"]);
			$pairingStmt->execute();
			$pairing = $pairingStmt->fetchAll(PDO::FETCH_ASSOC);

			$teamStmt->bindParam(':teamId', $pairing[0]["plaintiff"]);
			$teamStmt->execute();
			$team = $teamStmt->fetchAll(PDO::FETCH_ASSOC);
			$pTeamNumber = $team[0]["number"];

			$teamStmt->bindParam(':teamId', $pairing[0]["defense"]);
			$teamStmt->execute();
			$team = $teamStmt->fetchAll(PDO::FETCH_ASSOC);
			$dTeamNumber = $team[0]["number"];

			$ballotData = array(
				"room" => $pairing[0]["room"],
				"round" => $pairing[0]["round"],
				"plaintiff" => $pTeamNumber,
				"defense" => $dTeamNumber,
				"pPD" => $ballot["plaintiffPD"],
				"judge" => $ballot["judge"],
				"url" => $ballot["url"],
				"locked" => $ballot["locked"],
				"id" => $ballot["id"],
				"releaseComments" => $ballot["releaseComments"],
				"releaseScores" => $ballot["releaseScores"]
			);
			array_push($data, $ballotData);
		}
		echo json_encode($data);
	} catch (PDOException $e) {
		echo "Error: " . $e->getMessage();
	}
	$conn = null;
} else {
	$_SESSION["isAdmin"] = false;
	http_response_code(401);
	echo json_encode(array("message" => -1));
}
