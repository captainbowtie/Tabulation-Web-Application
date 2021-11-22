<?php
/*
 * Copyright (C) 2021 allen
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
    require_once __DIR__ . "/config.php";
    require_once SITE_ROOT . "/database.php";
    require_once SITE_ROOT . "/loginHeader.php";

    require_once SITE_ROOT . "/objects/ballot.php";
    require_once SITE_ROOT . "/objects/pairing.php";
    require_once SITE_ROOT . "/objects/team.php";
    require_once SITE_ROOT . "/objects/judge.php";

    $pairings = getAllPairings();
    $teams = getAllTeams();

    $currentRound = 1;

    for ($a = 0; $a < sizeOf($pairings); $a++) {
        if ($pairings[$a]["round"] > $currentRound) {
            $currentRound = $pairings[$a]["round"];
        }
    }

    $bodyHTML = "<table>\n";
    $bodyHTML .= "<tr>\n";
    $bodyHTML .= "<th>\n";
    $bodyHTML .= "Plaintiff\n";
    $bodyHTML .= "</th>\n";
    $bodyHTML .= "<th>\n";
    $bodyHTML .= "Defense\n";
    $bodyHTML .= "</th>\n";
    $bodyHTML .= "</tr>\n";

    foreach ($pairings as &$pairing) {
        if ($pairing["round"] === $currentRound) {
            $pairingData = getBallotData($pairing["id"]);
            $bodyHTML .= "<tr>\n";
            $bodyHTML .= "<td>\n";
            $bodyHTML .= $pairing["plaintiff"];
            if ($pairingData["pOpen"] === "N/A") {
                $bodyHTML .= "(Not Completed)";
            } else {
                $bodyHTML .= "(Completed)";
                $bodyHTML .= $pairing["pOpen"];
            }
            $bodyHTML .= "</td>\n";
            $bodyHTML .= "<td>\n";
            $bodyHTML .= $pairing["defense"];
            if ($pairingData["dOpen"] === "N/A") {
                $bodyHTML .= "(Not Completed)";
            } else {
                $bodyHTML .= "(Completed)";
                $bodyHTML .= $pairing["pOpen"];
            }
            $bodyHTML .= "</td>\n";
            $bodyHTML .= "</tr>\n";
        }
    }
    $bodyHTML .= "</table>\n";
} else {
    die("Access denied");
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
 but WITHOUT ANY WARRANTY;
without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.
-->
<html>
    <head>
        <meta charset = "UTF-8">
        <meta name = "viewport" content = "width=device-width, initial-scale=1.0">
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

        <!--Latest compiled and minified CSS-->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

        <!-- Latest compiled and minified JavaScript -->
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>

        <title>Judges</title>
    </head>
    <body>
        <?php
        echo $header;
        echo $bodyHTML;
        ?>
    </body>
</html>