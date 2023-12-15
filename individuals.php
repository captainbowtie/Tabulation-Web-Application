<?php
/*
 * Copyright (C) 2020 allen
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

    //import requirements
    require_once __DIR__ . "/config.php";
    require_once SITE_ROOT . "/database.php";
    require_once SITE_ROOT . "/loginHeader.php";

    //initialize queries
    $ballotsQuery = "SELECT pairing,judge,aty1,aty2,aty3,aty4,wit1,wit2,wit3,wit4 FROM ballots";
    $pairingsQuery = "SELECT id,round,plaintiff,defense,pDx1,pDx2,pDx3,pWDx1,pWDx2,pWDx3,dDx1,dDx2,dDx3,dWDx1,dWDx2,dWDx3 FROM pairings";
    $teamsQuery = "SELECT id,number,name FROM teams ORDER BY number";

    //initialize database connection
    $db = new Database();
    $conn = $db->getConnection();

    //get all individual awards and fill an array with them
    $ballotsResult = $conn->query($ballotsQuery);
    $individualAwards = [];
    while ($ballot = $ballotsResult->fetch_assoc()) {
        if ($ballot["aty1"] !== "N/A") {
            $award["name"] = $ballot["aty1"];
            $award["pairing"] = intVal($ballot["pairing"]);
            $award["ranks"] = 5;
            $award["isAttorney"] = true;
            array_push($individualAwards, $award);
        }
        if ($ballot["aty2"] !== "N/A") {
            $award["name"] = $ballot["aty2"];
            $award["pairing"] = intVal($ballot["pairing"]);
            $award["ranks"] = 4;
            $award["isAttorney"] = true;
            array_push($individualAwards, $award);
        }
        if ($ballot["aty3"] !== "N/A") {
            $award["name"] = $ballot["aty3"];
            $award["pairing"] = intVal($ballot["pairing"]);
            $award["ranks"] = 3;
            $award["isAttorney"] = true;
            array_push($individualAwards, $award);
        }
        if ($ballot["aty4"] !== "N/A") {
            $award["name"] = $ballot["aty4"];
            $award["pairing"] = intVal($ballot["pairing"]);
            $award["ranks"] = 2;
            $award["isAttorney"] = true;
            array_push($individualAwards, $award);
        }
        if ($ballot["wit1"] !== "N/A") {
            $award["name"] = $ballot["wit1"];
            $award["pairing"] = intVal($ballot["pairing"]);
            $award["ranks"] = 5;
            $award["isAttorney"] = false;
            array_push($individualAwards, $award);
        }
        if ($ballot["wit2"] !== "N/A") {
            $award["name"] = $ballot["wit2"];
            $award["pairing"] = intVal($ballot["pairing"]);
            $award["ranks"] = 4;
            $award["isAttorney"] = false;
            array_push($individualAwards, $award);
        }
        if ($ballot["wit3"] !== "N/A") {
            $award["name"] = $ballot["wit3"];
            $award["pairing"] = intVal($ballot["pairing"]);
            $award["ranks"] = 3;
            $award["isAttorney"] = false;
            array_push($individualAwards, $award);
        }
        if ($ballot["wit4"] !== "N/A") {
            $award["name"] = $ballot["wit4"];
            $award["pairing"] = intVal($ballot["pairing"]);
            $award["ranks"] = 2;
            $award["isAttorney"] = false;
            array_push($individualAwards, $award);
        }
    }
    $ballotsResult->close();

    //cross reference individual awards with pairings to determine team and side
    $pairingsResult = $conn->query($pairingsQuery);
    while ($pairing = $pairingsResult->fetch_assoc()) {
        for ($a = 0; $a < sizeOf($individualAwards); $a++) {
            if ($individualAwards[$a]["pairing"] === intVal($pairing["id"])) {
                if ($pairing["pDx1"] === $individualAwards[$a]["name"]) {
                    $individualAwards[$a]["isPlaintiff"] = true;
                    $individualAwards[$a]["team"] = intVal($pairing["plaintiff"]);
                } else if ($pairing["pDx2"] === $individualAwards[$a]["name"]) {
                    $individualAwards[$a]["isPlaintiff"] = true;
                    $individualAwards[$a]["team"] = intVal($pairing["plaintiff"]);
                } else if ($pairing["pDx3"] === $individualAwards[$a]["name"]) {
                    $individualAwards[$a]["isPlaintiff"] = true;
                    $individualAwards[$a]["team"] = intVal($pairing["plaintiff"]);
                } else if ($pairing["pWDx1"] === $individualAwards[$a]["name"]) {
                    $individualAwards[$a]["isPlaintiff"] = true;
                    $individualAwards[$a]["team"] = intVal($pairing["plaintiff"]);
                } else if ($pairing["pWDx2"] === $individualAwards[$a]["name"]) {
                    $individualAwards[$a]["isPlaintiff"] = true;
                    $individualAwards[$a]["team"] = intVal($pairing["plaintiff"]);
                } else if ($pairing["pWDx3"] === $individualAwards[$a]["name"]) {
                    $individualAwards[$a]["isPlaintiff"] = true;
                    $individualAwards[$a]["team"] = intVal($pairing["plaintiff"]);
                } else if ($pairing["dDx1"] === $individualAwards[$a]["name"]) {
                    $individualAwards[$a]["isPlaintiff"] = false;
                    $individualAwards[$a]["team"] = intVal($pairing["defense"]);
                } else if ($pairing["dDx2"] === $individualAwards[$a]["name"]) {
                    $individualAwards[$a]["isPlaintiff"] = false;
                    $individualAwards[$a]["team"] = intVal($pairing["defense"]);
                } else if ($pairing["dDx3"] === $individualAwards[$a]["name"]) {
                    $individualAwards[$a]["isPlaintiff"] = false;
                    $individualAwards[$a]["team"] = intVal($pairing["defense"]);
                } else if ($pairing["dWDx1"] === $individualAwards[$a]["name"]) {
                    $individualAwards[$a]["isPlaintiff"] = false;
                    $individualAwards[$a]["team"] = intVal($pairing["defense"]);
                } else if ($pairing["dWDx2"] === $individualAwards[$a]["name"]) {
                    $individualAwards[$a]["isPlaintiff"] = false;
                    $individualAwards[$a]["team"] = intVal($pairing["defense"]);
                } else if ($pairing["dWDx3"] === $individualAwards[$a]["name"]) {
                    $individualAwards[$a]["isPlaintiff"] = false;
                    $individualAwards[$a]["team"] = intVal($pairing["defense"]);
                }
            }
        }
    }
    $pairingsResult->close();

    //convert team ids to team names, also fill select with team numbers and names
    $selectHTML = "";
    $teamsResult = $conn->query($teamsQuery);
    while ($team = $teamsResult->fetch_assoc()) {
        for ($a = 0; $a < sizeOf($individualAwards); $a++) {
            if ($individualAwards[$a]["team"] === intVal($team["id"])) {
                $individualAwards[$a]["team"] = intVal($team["number"]);
            }
        }
        $selectHTML .= "<option value='" . $team["number"] . "'>" . $team["number"] . "-" . $team["name"] . "</option>";
    }
} else {
    die("Access denied.");
}
?>

<!DOCTYPE html>
<!--
Copyright (C) 2020 allen

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
-->


<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width = device-width, initial-scale = 1.0">
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

        <!--Latest compiled and minified JavaScript-->
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>

        <!--Latest compiled and minified CSS-->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

        <link rel="stylesheet" href="individuals.css">
        <title>Individual Awards</title>
    </head>
    <body>
        <?php echo $header; ?>
        <select id="team">
            <?php echo $selectHTML; ?>
        </select>
        <div id="awardsGrid">
            <div id="plaintiffAttorneys">
                <h3>Plaintiff Attorney Ranks</h3>
                <div id="pAty"></div>
            </div>
            <div id="plaintiffWitnesses">
                <h3>Plaintiff Witness Ranks</h3>
                <div id="pWit"></div>
            </div>
            <div id="defenseAttorneys">
                <h3>Defense Attorney Ranks</h3>
                <div id="dAty"></div>
            </div>
            <div id="defenseWitnesses">
                <h3>Defense Witness Ranks</h3>
                <div id="dWit"></div>
            </div>
        </div>
        <script>var awards = <?php echo json_encode($individualAwards); ?></script>
        <script src="individuals.js"></script>
    </body>
</html>
