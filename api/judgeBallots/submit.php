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

var_dump($_POST);
if (
	isset($_POST["pOpen"]) &&
	isset($_POST["pOpenComments"]) &&
	isset($_POST["dOpen"]) &&
	isset($_POST["dOpenComments"]) &&
	isset($_POST["pDx1"]) &&
	isset($_POST["pDx1Comments"]) &&
	isset($_POST["pWDx1"]) &&
	isset($_POST["pWDx1Comments"]) &&
	isset($_POST["pWCx1"]) &&
	isset($_POST["pWCx1Comments"]) &&
	isset($_POST["dCx1"]) &&
	isset($_POST["dCx1Comments"]) &&
	isset($_POST["pDx2"]) &&
	isset($_POST["pDx2Comments"]) &&
	isset($_POST["pWDx2"]) &&
	isset($_POST["pWDx2Comments"]) &&
	isset($_POST["pWCx2"]) &&
	isset($_POST["pWCx2Comments"]) &&
	isset($_POST["dCx2"]) &&
	isset($_POST["dCx2Comments"]) &&
	isset($_POST["pDx3"]) &&
	isset($_POST["pDx3Comments"]) &&
	isset($_POST["pWDx3"]) &&
	isset($_POST["pWDx3Comments"]) &&
	isset($_POST["pWCx3"]) &&
	isset($_POST["pWCx3Comments"]) &&
	isset($_POST["dCx3"]) &&
	isset($_POST["dCx3Comments"]) &&
	isset($_POST["dDx1"]) &&
	isset($_POST["dDx1Comments"]) &&
	isset($_POST["dWDx1"]) &&
	isset($_POST["dWDx1Comments"]) &&
	isset($_POST["dWCx1"]) &&
	isset($_POST["dWCx1Comments"]) &&
	isset($_POST["pCx1"]) &&
	isset($_POST["pCx1Comments"]) &&
	isset($_POST["dDx2"]) &&
	isset($_POST["dDx2Comments"]) &&
	isset($_POST["dWDx2"]) &&
	isset($_POST["dWDx2Comments"]) &&
	isset($_POST["dWCx2"]) &&
	isset($_POST["dWCx2Comments"]) &&
	isset($_POST["pCx2"]) &&
	isset($_POST["pCx2Comments"]) &&
	isset($_POST["dDx3"]) &&
	isset($_POST["dDx3Comments"]) &&
	isset($_POST["dWDx3"]) &&
	isset($_POST["dWDx3Comments"]) &&
	isset($_POST["dWCx3"]) &&
	isset($_POST["dWCx3Comments"]) &&
	isset($_POST["pCx3"]) &&
	isset($_POST["pCx3Comments"]) &&
	isset($_POST["pClose"]) &&
	isset($_POST["pCloseComments"]) &&
	isset($_POST["dClose"]) &&
	isset($_POST["dCloseComments"]) &&
	isset($_POST["aty1"]) &&
	isset($_POST["aty2"]) &&
	isset($_POST["aty3"]) &&
	isset($_POST["aty4"]) &&
	isset($_POST["wit1"]) &&
	isset($_POST["wit2"]) &&
	isset($_POST["wit3"]) &&
	isset($_POST["wit4"])
) {
	$ballot["pOpen"] = htmlspecialchars(strip_tags($_POST["pOpen"]));
	$ballot["pOpenComments"] = htmlspecialchars(strip_tags($_POST["pOpenComments"]));
	$ballot["dOpen"] = htmlspecialchars(strip_tags($_POST["dOpen"]));
	$ballot["dOpenComments"] = htmlspecialchars(strip_tags($_POST["dOpenComments"]));
	$ballot["pDx1"] = htmlspecialchars(strip_tags($_POST["pDx1"]));
	$ballot["pDx1Comments"] = htmlspecialchars(strip_tags($_POST["pDx1Comments"]));
	$ballot["pWDx1"] = htmlspecialchars(strip_tags($_POST["pWDx1"]));
	$ballot["pWDx1Comments"] = htmlspecialchars(strip_tags($_POST["pWDx1Comments"]));
	$ballot["pWCx1"] = htmlspecialchars(strip_tags($_POST["pWCx1"]));
	$ballot["pWCx1Comments"] = htmlspecialchars(strip_tags($_POST["pWCx1Comments"]));
	$ballot["dCx1"] = htmlspecialchars(strip_tags($_POST["dCx1"]));
	$ballot["dCx1Comments"] = htmlspecialchars(strip_tags($_POST["dCx1Comments"]));
	$ballot["pDx2"] = htmlspecialchars(strip_tags($_POST["pDx2"]));
	$ballot["pDx2Comments"] = htmlspecialchars(strip_tags($_POST["pDx2Comments"]));
	$ballot["pWDx2"] = htmlspecialchars(strip_tags($_POST["pWDx2"]));
	$ballot["pWDx2Comments"] = htmlspecialchars(strip_tags($_POST["pWDx2Comments"]));
	$ballot["pWCx2"] = htmlspecialchars(strip_tags($_POST["pWCx2"]));
	$ballot["pWCx2Comments"] = htmlspecialchars(strip_tags($_POST["pWCx2Comments"]));
	$ballot["dCx2"] = htmlspecialchars(strip_tags($_POST["dCx2"]));
	$ballot["dCx2Comments"] = htmlspecialchars(strip_tags($_POST["dCx2Comments"]));
	$ballot["pDx3"] = htmlspecialchars(strip_tags($_POST["pDx3"]));
	$ballot["pDx3Comments"] = htmlspecialchars(strip_tags($_POST["pDx3Comments"]));
	$ballot["pWDx3"] = htmlspecialchars(strip_tags($_POST["pWDx3"]));
	$ballot["pWDx3Comments"] = htmlspecialchars(strip_tags($_POST["pWDx3Comments"]));
	$ballot["pWCx3"] = htmlspecialchars(strip_tags($_POST["pWCx3"]));
	$ballot["pWCx3Comments"] = htmlspecialchars(strip_tags($_POST["pWCx3Comments"]));
	$ballot["dCx3"] = htmlspecialchars(strip_tags($_POST["dCx3"]));
	$ballot["dCx3Comments"] = htmlspecialchars(strip_tags($_POST["dCx3Comments"]));
	$ballot["dDx1"] = htmlspecialchars(strip_tags($_POST["dDx1"]));
	$ballot["dDx1Comments"] = htmlspecialchars(strip_tags($_POST["dDx1Comments"]));
	$ballot["dWDx1"] = htmlspecialchars(strip_tags($_POST["dWDx1"]));
	$ballot["dWDx1Comments"] = htmlspecialchars(strip_tags($_POST["dWDx1Comments"]));
	$ballot["dWCx1"] = htmlspecialchars(strip_tags($_POST["dWCx1"]));
	$ballot["dWCx1Comments"] = htmlspecialchars(strip_tags($_POST["dWCx1Comments"]));
	$ballot["pCx1"] = htmlspecialchars(strip_tags($_POST["pCx1"]));
	$ballot["pCx1Comments"] = htmlspecialchars(strip_tags($_POST["pCx1Comments"]));
	$ballot["dDx2"] = htmlspecialchars(strip_tags($_POST["dDx2"]));
	$ballot["dDx2Comments"] = htmlspecialchars(strip_tags($_POST["dDx2Comments"]));
	$ballot["dWDx2"] = htmlspecialchars(strip_tags($_POST["dWDx2"]));
	$ballot["dWDx2Comments"] = htmlspecialchars(strip_tags($_POST["dWDx2Comments"]));
	$ballot["dWCx2"] = htmlspecialchars(strip_tags($_POST["dWCx2"]));
	$ballot["dWCx2Comments"] = htmlspecialchars(strip_tags($_POST["dWCx2Comments"]));
	$ballot["pCx2"] = htmlspecialchars(strip_tags($_POST["pCx2"]));
	$ballot["pCx2Comments"] = htmlspecialchars(strip_tags($_POST["pCx2Comments"]));
	$ballot["dDx3"] = htmlspecialchars(strip_tags($_POST["dDx3"]));
	$ballot["dDx3Comments"] = htmlspecialchars(strip_tags($_POST["dDx3Comments"]));
	$ballot["dWDx3"] = htmlspecialchars(strip_tags($_POST["dWDx3"]));
	$ballot["dWDx3Comments"] = htmlspecialchars(strip_tags($_POST["dWDx3Comments"]));
	$ballot["dWCx3"] = htmlspecialchars(strip_tags($_POST["dWCx3"]));
	$ballot["dWCx3Comments"] = htmlspecialchars(strip_tags($_POST["dWCx3Comments"]));
	$ballot["pCx3"] = htmlspecialchars(strip_tags($_POST["pCx3"]));
	$ballot["pCx3Comments"] = htmlspecialchars(strip_tags($_POST["pCx3Comments"]));
	$ballot["pClose"] = htmlspecialchars(strip_tags($_POST["pClose"]));
	$ballot["pCloseComments"] = htmlspecialchars(strip_tags($_POST["pCloseComments"]));
	$ballot["dClose"] = htmlspecialchars(strip_tags($_POST["dClose"]));
	$ballot["dCloseComments"] = htmlspecialchars(strip_tags($_POST["dCloseComments"]));
	$ballot["aty1"] = htmlspecialchars(strip_tags($_POST["aty1"]));
	$ballot["aty2"] = htmlspecialchars(strip_tags($_POST["aty2"]));
	$ballot["aty3"] = htmlspecialchars(strip_tags($_POST["aty3"]));
	$ballot["aty4"] = htmlspecialchars(strip_tags($_POST["aty4"]));
	$ballot["wit1"] = htmlspecialchars(strip_tags($_POST["wit1"]));
	$ballot["wit2"] = htmlspecialchars(strip_tags($_POST["wit2"]));
	$ballot["wit3"] = htmlspecialchars(strip_tags($_POST["wit3"]));
	$ballot["wit4"] = htmlspecialchars(strip_tags($_POST["wit4"]));
	$ballotQuery = "UPDATE ballots SET pOpen=:pOpen,pOpenComments=:pOpenComments,dOpen=:dOpen,dOpenComments=:dOpenComments,pDx1=:pDx1,pDx1Comments=:pDx1Comments,pWDx1=:pWDx1,pWDx1Comments=:pWDx1Comments,pWCx1=:pWCx1,pWCx1Comments=:pWCx1Comments,dCx1=:dCx1,dCx1Comments=:dCx1Comments,pDx2=:pDx2,pDx2Comments=:pDx2Comments,pWDx2=:pWDx2,pWDx2Comments=:pWDx2Comments,pWCx2=:pWCx2,pWCx2Comments=:pWCx2Comments,dCx2=:dCx2,dCx2Comments=:dCx2Comments,pDx3=:pDx3,pDx3Comments=:pDx3Comments,pWDx3=:pWDx3,pWDx3Comments=:pWDx3Comments,pWCx3=:pWCx3,pWCx3Comments=:pWCx3Comments,dCx3=:dCx3,dCx3Comments=:dCx3Comments,dDx1=:dDx1,dDx1Comments=:dDx1Comments,dWDx1=:dWDx1,dWDx1Comments=:dWDx1Comments,dWCx1=:dWCx1,dWCx1Comments=:dWCx1Comments,pCx1=:pCx1,pCx1Comments=:pCx1Comments,dDx2=:dDx2,dDx2Comments=:dDx2Comments,dWDx2=:dWDx2,dWDx2Comments=:dWDx2Comments,dWCx2=:dWCx2,dWCx2Comments=:dWCx2Comments,pCx2=:pCx2,pCx2Comments=:pCx2Comments,dDx3=:dDx3,dDx3Comments=:dDx3Comments,dWDx3=:dWDx3,dWDx3Comments=:dWDx3Comments,dWCx3=:dWCx3,dWCx3Comments=:dWCx3Comments,pCx3=:pCx3,pCx3Comments=:pCx3Comments,pClose=:pClose,pCloseComments=:pCloseComments,dClose=:dClose,dCloseComments=:dCloseComments,aty1=:aty1,aty2=:aty2,aty3=:aty3,aty4=:aty4,wit1=:wit1,wit2=:wit2,wit3=:wit3,wit4=:wit4,locked=1 WHERE url=:url && locked=0";
	try {
		$db = new Database();
		$conn = $db->getConnection();
		$stmt = $conn->prepare($ballotQuery);
		$stmt->bindParam(':url', $_SESSION["ballot"]);
		$stmt->bindParam(':pOpen', $ballot["pOpen"]);
		$stmt->bindParam(':pOpenComments', $ballot["pOpenComments"]);
		$stmt->bindParam(':dOpen', $ballot["dOpen"]);
		$stmt->bindParam(':dOpenComments', $ballot["dOpenComments"]);
		$stmt->bindParam(':pDx1', $ballot["pDx1"]);
		$stmt->bindParam(':pDx1Comments', $ballot["pDx1Comments"]);
		$stmt->bindParam(':pWDx1', $ballot["pWDx1"]);
		$stmt->bindParam(':pWDx1Comments', $ballot["pWDx1Comments"]);
		$stmt->bindParam(':pWCx1', $ballot["pWCx1"]);
		$stmt->bindParam(':pWCx1Comments', $ballot["pWCx1Comments"]);
		$stmt->bindParam(':dCx1', $ballot["dCx1"]);
		$stmt->bindParam(':dCx1Comments', $ballot["dCx1Comments"]);
		$stmt->bindParam(':pDx2', $ballot["pDx2"]);
		$stmt->bindParam(':pDx2Comments', $ballot["pDx2Comments"]);
		$stmt->bindParam(':pWDx2', $ballot["pWDx2"]);
		$stmt->bindParam(':pWDx2Comments', $ballot["pWDx2Comments"]);
		$stmt->bindParam(':pWCx2', $ballot["pWCx2"]);
		$stmt->bindParam(':pWCx2Comments', $ballot["pWCx2Comments"]);
		$stmt->bindParam(':dCx2', $ballot["dCx2"]);
		$stmt->bindParam(':dCx2Comments', $ballot["dCx2Comments"]);
		$stmt->bindParam(':pDx3', $ballot["pDx3"]);
		$stmt->bindParam(':pDx3Comments', $ballot["pDx3Comments"]);
		$stmt->bindParam(':pWDx3', $ballot["pWDx3"]);
		$stmt->bindParam(':pWDx3Comments', $ballot["pWDx3Comments"]);
		$stmt->bindParam(':pWCx3', $ballot["pWCx3"]);
		$stmt->bindParam(':pWCx3Comments', $ballot["pWCx3Comments"]);
		$stmt->bindParam(':dCx3', $ballot["dCx3"]);
		$stmt->bindParam(':dCx3Comments', $ballot["dCx3Comments"]);
		$stmt->bindParam(':dDx1', $ballot["dDx1"]);
		$stmt->bindParam(':dDx1Comments', $ballot["dDx1Comments"]);
		$stmt->bindParam(':dWDx1', $ballot["dWDx1"]);
		$stmt->bindParam(':dWDx1Comments', $ballot["dWDx1Comments"]);
		$stmt->bindParam(':dWCx1', $ballot["dWCx1"]);
		$stmt->bindParam(':dWCx1Comments', $ballot["dWCx1Comments"]);
		$stmt->bindParam(':pCx1', $ballot["pCx1"]);
		$stmt->bindParam(':pCx1Comments', $ballot["pCx1Comments"]);
		$stmt->bindParam(':dDx2', $ballot["dDx2"]);
		$stmt->bindParam(':dDx2Comments', $ballot["dDx2Comments"]);
		$stmt->bindParam(':dWDx2', $ballot["dWDx2"]);
		$stmt->bindParam(':dWDx2Comments', $ballot["dWDx2Comments"]);
		$stmt->bindParam(':dWCx2', $ballot["dWCx2"]);
		$stmt->bindParam(':dWCx2Comments', $ballot["dWCx2Comments"]);
		$stmt->bindParam(':pCx2', $ballot["pCx2"]);
		$stmt->bindParam(':pCx2Comments', $ballot["pCx2Comments"]);
		$stmt->bindParam(':dDx3', $ballot["dDx3"]);
		$stmt->bindParam(':dDx3Comments', $ballot["dDx3Comments"]);
		$stmt->bindParam(':dWDx3', $ballot["dWDx3"]);
		$stmt->bindParam(':dWDx3Comments', $ballot["dWDx3Comments"]);
		$stmt->bindParam(':dWCx3', $ballot["dWCx3"]);
		$stmt->bindParam(':dWCx3Comments', $ballot["dWCx3Comments"]);
		$stmt->bindParam(':pCx3', $ballot["pCx3"]);
		$stmt->bindParam(':pCx3Comments', $ballot["pCx3Comments"]);
		$stmt->bindParam(':pClose', $ballot["pClose"]);
		$stmt->bindParam(':pCloseComments', $ballot["pCloseComments"]);
		$stmt->bindParam(':dClose', $ballot["dClose"]);
		$stmt->bindParam(':dCloseComments', $ballot["dCloseComments"]);
		$stmt->bindParam(':aty1', $ballot["aty1"]);
		$stmt->bindParam(':aty2', $ballot["aty2"]);
		$stmt->bindParam(':aty3', $ballot["aty3"]);
		$stmt->bindParam(':aty4', $ballot["aty4"]);
		$stmt->bindParam(':wit1', $ballot["wit1"]);
		$stmt->bindParam(':wit2', $ballot["wit2"]);
		$stmt->bindParam(':wit3', $ballot["wit3"]);
		$stmt->bindParam(':wit4', $ballot["wit4"]);
		var_dump($stmt);
		$stmt->execute();
		$conn = null;
		echo json_encode(array("message" => 0));
	} catch (PDOException $e) {
		echo "Error: " . $e->getMessage();
	}
}
