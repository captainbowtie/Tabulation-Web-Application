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
	if (isset($_POST["ballot"]) && isset($_POST["part"]) && isset($_POST["comment"])) {
		$ballot = htmlspecialchars(strip_tags($_POST["ballot"]));
		$part = htmlspecialchars(strip_tags($_POST["part"]));
		$comment = htmlspecialchars(strip_tags($_POST["comment"]));

		$query = "";
		switch ($part) {
			case "pOpenComments":
				$query = "UPDATE ballots SET pOpenComments = :comments WHERE id=:id";
				break;
			case "pDx1Comments":
				$query = "UPDATE ballots SET pDx1Comments = :comments WHERE id=:id";
				break;
			case "pDx2Comments":
				$query = "UPDATE ballots SET pDx2Comments = :comments WHERE id=:id";
				break;
			case "pDx3Comments":
				$query = "UPDATE ballots SET pDx3Comments = :comments WHERE id=:id";
				break;
			case "pCx1Comments":
				$query = "UPDATE ballots SET pCx1Comments = :comments WHERE id=:id";
				break;
			case "pCx2Comments":
				$query = "UPDATE ballots SET pCx2Comments = :comments WHERE id=:id";
				break;
			case "pCx3Comments":
				$query = "UPDATE ballots SET pCx3Comments = :comments WHERE id=:id";
				break;
			case "pWDx1Comments":
				$query = "UPDATE ballots SET pWDx1Comments = :comments WHERE id=:id";
				break;
			case "pWDx2Comments":
				$query = "UPDATE ballots SET pWDx2Comments = :comments WHERE id=:id";
				break;
			case "pWDx3Comments":
				$query = "UPDATE ballots SET pWDx3Comments = :comments WHERE id=:id";
				break;
			case "pWCx1Comments":
				$query = "UPDATE ballots SET pWCx1Comments = :comments WHERE id=:id";
				break;
			case "pWCx2Comments":
				$query = "UPDATE ballots SET pWCx2Comments = :comments WHERE id=:id";
				break;
			case "pWCx3Comments":
				$query = "UPDATE ballots SET pWCx3Comments = :comments WHERE id=:id";
				break;
			case "pCloseComments":
				$query = "UPDATE ballots SET pCloseComments = :comments WHERE id=:id";
				break;
			case "dOpenComments":
				$query = "UPDATE ballots SET dOpenComments = :comments WHERE id=:id";
				break;
			case "dDx1Comments":
				$query = "UPDATE ballots SET dDx1Comments = :comments WHERE id=:id";
				break;
			case "dDx2Comments":
				$query = "UPDATE ballots SET dDx2Comments = :comments WHERE id=:id";
				break;
			case "dDx3Comments":
				$query = "UPDATE ballots SET dDx3Comments = :comments WHERE id=:id";
				break;
			case "dCx1Comments":
				$query = "UPDATE ballots SET dCx1Comments = :comments WHERE id=:id";
				break;
			case "dCx2Comments":
				$query = "UPDATE ballots SET dCx2Comments = :comments WHERE id=:id";
				break;
			case "dCx3Comments":
				$query = "UPDATE ballots SET dCx3Comments = :comments WHERE id=:id";
				break;
			case "dWDx1Comments":
				$query = "UPDATE ballots SET dWDx1Comments = :comments WHERE id=:id";
				break;
			case "dWDx2Comments":
				$query = "UPDATE ballots SET dWDx2Comments = :comments WHERE id=:id";
				break;
			case "dWDx3Comments":
				$query = "UPDATE ballots SET dWDx3Comments = :comments WHERE id=:id";
				break;
			case "dWCx1Comments":
				$query = "UPDATE ballots SET dWCx1Comments = :comments WHERE id=:id";
				break;
			case "dWCx2Comments":
				$query = "UPDATE ballots SET dWCx2Comments = :comments WHERE id=:id";
				break;
			case "dWCx3Comments":
				$query = "UPDATE ballots SET dWCx3Comments = :comments WHERE id=:id";
				break;
			case "dCloseComments":
				$query = "UPDATE ballots SET dCloseComments = :comments WHERE id=:id";
				break;
		}

		try {
			$db = new Database();
			$conn = $db->getConnection();
			$stmt = $conn->prepare($query);
			$stmt->bindParam(':id', $ballot);
			$stmt->bindParam(':comments', $comment);
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
