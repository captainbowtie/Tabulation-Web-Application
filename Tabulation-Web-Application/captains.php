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


require_once __DIR__ . "/config.php";
require_once SITE_ROOT . "/database.php";

$pairingQuery = "SELECT plaintiff,defense,round FROM pairings";

//get pairing data
$db = new Database();
$conn = $db->getConnection();
$pairingResult = $conn->query($pairingQuery);
$maxRound = 0;
$a = 0;
while ($pairing = $pairingResult->fetch_assoc()) {
    $pairings[$a]["plaintiff"] = intVal($pairing["plaintiff"]);
    $pairings[$a]["defense"] = intVal($pairing["defense"]);
    $pairings[$a]["round"] = intVal($pairing["round"]);
    if (intVal($pairing["round"]) > $maxRound) {
        $maxRound = intVal($pairing["round"]);
    }
    $a++;
}
$pairingResult->close();

//remove pairings from past rounds
$roundPairings = [];
for ($a = 0; $a < sizeOf($pairings); $a++) {
    if ($pairings[$a]["round"] === $maxRound) {
        array_push($roundPairings, $pairings[$a]);
    }
}


//convert team ids to team numbers
$teamQuery = "SELECT id,number FROM teams";
$teamResult = $conn->query($teamQuery);
while ($team = $teamResult->fetch_assoc()) {
    for ($a = 0; $a < sizeOf($roundPairings); $a++) {
        if ($roundPairings[$a]["plaintiff"] === intVal($team["id"])) {
            $roundPairings[$a]["plaintiff"] = intVal($team["number"]);
        } else if ($roundPairings[$a]["defense"] === intVal($team["id"])) {
            $roundPairings[$a]["defense"] = intVal($team["number"]);
        }
    }
}
$teamResult->close();
$conn->close();

//create select options
$pairingOptionsHTML = "<option value='0'>Select Pairing:</option>\n";
for ($a = 0; $a < sizeOf($roundPairings); $a++) {
    $pTeamNumber = $roundPairings[$a]["plaintiff"];
    $dTeamNumber = $roundPairings[$a]["defense"];
    $pairingOptionsHTML .= "<option value='$pTeamNumber'>$pTeamNumber v. $dTeamNumber</option>\n";
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
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <!--Latest compiled and minified JavaScript-->
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>

        <!--Latest compiled and minified CSS-->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

        <link rel="stylesheet" href="captains.css">

        <title>Captains</title>
    </head>
    <body>
        <?php
        echo $header;
        ?>
        <div id="selectDiv">
            <select id="pairingSelect">
                <?php echo $pairingOptionsHTML; ?>
            </select>
        </div>
        <div id="sideSelectDiv">
            <input type ="radio" name="side" value="plainitff" id="pRadio">
            <label for="pRadio">Plaintiff</label><br>
            <input type="radio" name="side" value="defense" id="dRadio">
            <label for="dRadio">Defense</label><br>
        </div>
        <div id="inputDiv">
            <label id="pOpenLabel" for="pOpen">Prosecution Open:</label>
            <input class="pInput" id="pOpen">
            <label id="dOpenLabel" for="dOpen">Defense Open:</label>
            <input class="dInput" id="dOpen">

            <select class="pInput" id="wit1">
                <option value="Weber">Weber</option>
                <option value="Khan">Khan</option>
                <option value="Singh">Singh</option>
                <option value="DeJong">De Jong</option>
                <option value="Vaughn">Vaughn</option>
                <option value="Silva">Silva</option>
                <option value="Johnson">Johnson</option>
            </select>
            <label id="pDx1Label" for="pDx1">Directing Attorney:</label>
            <input class="pInput" id="pDx1">
            <label id="pWDx1Label" for="pWDx1">Witness:</label>
            <input class="pInput" id="pWDx1">
            <label id="dCx1Label" for="dCx1">Crossing Attorney:</label>
            <input class="dInput" id="dCx1">

            <select class="pInput" id="wit2">
                <option value="Weber">Weber</option>
                <option value="Khan">Khan</option>
                <option value="Singh">Singh</option>
                <option value="DeJong">De Jong</option>
                <option value="Vaughn">Vaughn</option>
                <option value="Silva">Silva</option>
                <option value="Johnson">Johnson</option>
            </select>
            <label id="pDx2Label" for="pDx2">Directing Attorney:</label>
            <input class="pInput" id="pDx2">
            <label id="pWDx2Label" for="pWDx2">Witness:</label>
            <input class="pInput" id="pWDx2">
            <label id="dCx2Label" for="dCx2">Crossing Attorney:</label>
            <input class="dInput" id="dCx2">

            <select class="pInput" id="wit3">
                <option value="Weber">Weber</option>
                <option value="Khan">Khan</option>
                <option value="Singh">Singh</option>
                <option value="DeJong">De Jong</option>
                <option value="Vaughn">Vaughn</option>
                <option value="Silva">Silva</option>
                <option value="Johnson">Johnson</option>
            </select>
            <label id="pDx3Label" for="pDx3">Directing Attorney:</label>
            <input class="pInput" id="pDx3">
            <label id="pWDx3Label" for="pWDx3">Witness:</label>
            <input class="pInput" id="pWDx3">
            <label id="dCx3Label" for="dCx3">Crossing Attorney:</label>
            <input class="dInput" id="dCx3">

            <select class="dInput" id="wit4">
                <option value="Syed">Syed</option>
                <option value="Jelani">Jelani</option>
                <option value="DeJong">De Jong</option>
                <option value="Vaughn">Vaughn</option>
                <option value="Silva">Silva</option>
                <option value="Johnson">Johnson</option>
            </select>
            <label id="dDx1Label" for="dDx1">Directing Attorney:</label>
            <input class="dInput" id="dDx1">
            <label id="dWDx1Label" for="dWDx1">Witness:</label>
            <input class="dInput" id="dWDx1">
            <label id="pCx1Label" for="pCx1">Crossing Attorney:</label>
            <input class="pInput" id="pCx1">

            <select class="dInput" id="wit5">
                <option value="Syed">Syed</option>
                <option value="Jelani">Jelani</option>
                <option value="DeJong">De Jong</option>
                <option value="Vaughn">Vaughn</option>
                <option value="Silva">Silva</option>
                <option value="Johnson">Johnson</option>
            </select>
            <label id="dDx2Label" for="dDx2">Directing Attorney:</label>
            <input class="dInput" id="dDx2">
            <label id="dWDx2Label" for="dWDx2">Witness:</label>
            <input class="dInput" id="dWDx2">
            <label id="pCx2Label" for="pCx2">Crossing Attorney:</label>
            <input class="pInput" id="pCx2">

            <select class="dInput" id="wit6">
                <option value="Syed">Syed</option>
                <option value="Jelani">Jelani</option>
                <option value="DeJong">De Jong</option>
                <option value="Vaughn">Vaughn</option>
                <option value="Silva">Silva</option>
                <option value="Johnson">Johnson</option>
            </select>
            <label id="dDx3Label" for="dDx3">Directing Attorney:</label>
            <input class="dInput" id="dDx3">
            <label id="dWDx3Label" for="dWDx3">Witness:</label>
            <input class="dInput" id="dWDx3">
            <label id="pCx3Label" for="pCx3">Crossing Attorney:</label>
            <input class="pInput" id="pCx3">

            <label id="pCloseLabel" for="pClose">Prosecution Close:</label>
            <input class="pInput" id="pClose">
            <label id="dCloseLabel" for="dClose">Defense Close:</label>
            <input class="dInput" id="dClose">
        </div>
        <button id="submit">Submit</button>
        <script>const round = <?php echo $maxRound; ?></script>
        <script src="captains.js"></script>
    </body>
</html>
