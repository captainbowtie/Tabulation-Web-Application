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

	if (isset($_POST["ballot"]) && isset($_POST["part"]) && isset($_POST["score"])) {
		$ballot = htmlspecialchars(strip_tags($_POST["ballot"]));
		$part = htmlspecialchars(strip_tags($_POST["part"]));
		$score = htmlspecialchars(strip_tags($_POST["score"]));

		$query = "";
		switch ($part) {
			case "pOpen":
				$query = "UPDATE ballots SET pOpen = :score WHERE id=:id";
				break;
			case "pDx1":
				$query = "UPDATE ballots SET pDx1 = :score WHERE id=:id";
				break;
			case "pDx2":
				$query = "UPDATE ballots SET pDx2 = :score WHERE id=:id";
				break;
			case "pDx3":
				$query = "UPDATE ballots SET pDx3 = :score WHERE id=:id";
				break;
			case "pCx1":
				$query = "UPDATE ballots SET pCx1 = :score WHERE id=:id";
				break;
			case "pCx2":
				$query = "UPDATE ballots SET pCx2 = :score WHERE id=:id";
				break;
			case "pCx3":
				$query = "UPDATE ballots SET pCx3 = :score WHERE id=:id";
				break;
			case "pWDx1":
				$query = "UPDATE ballots SET pWDx1 = :score WHERE id=:id";
				break;
			case "pWDx2":
				$query = "UPDATE ballots SET pWDx2 = :score WHERE id=:id";
				break;
			case "pWDx3":
				$query = "UPDATE ballots SET pWDx3 = :score WHERE id=:id";
				break;
			case "pWCx1":
				$query = "UPDATE ballots SET pWCx1 = :score WHERE id=:id";
				break;
			case "pWCx2":
				$query = "UPDATE ballots SET pWCx2 = :score WHERE id=:id";
				break;
			case "pWCx3":
				$query = "UPDATE ballots SET pWCx3 = :score WHERE id=:id";
				break;
			case "pClose":
				$query = "UPDATE ballots SET pClose = :score WHERE id=:id";
				break;
			case "dOpen":
				$query = "UPDATE ballots SET dOpen = :score WHERE id=:id";
				break;
			case "dDx1":
				$query = "UPDATE ballots SET dDx1 = :score WHERE id=:id";
				break;
			case "dDx2":
				$query = "UPDATE ballots SET dDx2 = :score WHERE id=:id";
				break;
			case "dDx3":
				$query = "UPDATE ballots SET dDx3 = :score WHERE id=:id";
				break;
			case "dCx1":
				$query = "UPDATE ballots SET dCx1 = :score WHERE id=:id";
				break;
			case "dCx2":
				$query = "UPDATE ballots SET dCx2 = :score WHERE id=:id";
				break;
			case "dCx3":
				$query = "UPDATE ballots SET dCx3 = :score WHERE id=:id";
				break;
			case "dWDx1":
				$query = "UPDATE ballots SET dWDx1 = :score WHERE id=:id";
				break;
			case "dWDx2":
				$query = "UPDATE ballots SET dWDx2 = :score WHERE id=:id";
				break;
			case "dWDx3":
				$query = "UPDATE ballots SET dWDx3 = :score WHERE id=:id";
				break;
			case "dWCx1":
				$query = "UPDATE ballots SET dWCx1 = :score WHERE id=:id";
				break;
			case "dWCx2":
				$query = "UPDATE ballots SET dWCx2 = :score WHERE id=:id";
				break;
			case "dWCx3":
				$query = "UPDATE ballots SET dWCx3 = :score WHERE id=:id";
				break;
			case "dClose":
				$query = "UPDATE ballots SET dClose = :score WHERE id=:id";
				break;
			case "aty1":
				$query = "UPDATE ballots SET aty1 = :score WHERE id=:id";
				break;
			case "aty2":
				$query = "UPDATE ballots SET aty2 = :score WHERE id=:id";
				break;
			case "aty3":
				$query = "UPDATE ballots SET aty3 = :score WHERE id=:id";
				break;
			case "aty4":
				$query = "UPDATE ballots SET aty4 = :score WHERE id=:id";
				break;
			case "wit1":
				$query = "UPDATE ballots SET wit1 = :score WHERE id=:id";
				break;
			case "wit2":
				$query = "UPDATE ballots SET wit2 = :score WHERE id=:id";
				break;
			case "wit3":
				$query = "UPDATE ballots SET wit3 = :score WHERE id=:id";
				break;
			case "wit4":
				$query = "UPDATE ballots SET wit4 = :score WHERE id=:id";
				break;
		}

		try {
			$db = new Database();
			$conn = $db->getConnection();
			$stmt = $conn->prepare($query);
			$stmt->bindParam(':id', $ballot);
			$stmt->bindParam(':score', $score);
			$stmt->execute();
			$conn = null;
			echo json_encode(array("message" => 0));
		} catch (PDOException $e) {
			echo "Error: " . $e->getMessage();
		}
	}
} else {
	$_SESSION["isAdmin"] = false;
	http_response_code(401);
	echo json_encode(array("message" => -1));
}
