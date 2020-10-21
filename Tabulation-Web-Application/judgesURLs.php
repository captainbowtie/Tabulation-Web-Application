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

    require_once SITE_ROOT . "/objects/ballot.php";
    require_once SITE_ROOT . "/objects/pairing.php";
    require_once SITE_ROOT . "/objects/team.php";
    require_once SITE_ROOT . "/objects/judge.php";

    $ballots = getAllBallots();
    $pairings = getAllPairings();
    $teams = getAllTeams();
    $judges = getAllJudges();

    for ($a = 0; $a < 4; $a++) {
        $tabHTML[$a] = "";
    }

    for ($a = 0; $a < sizeOf($ballots); $a++) {
        for ($b = 0; $b < sizeOf($pairings); $b++) {
            if ($ballots[$a]["pairing"] === $pairings[$b]["id"]) {
                $roundNumber = $pairings[$b]["round"];
                for ($c = 0; $c < sizeOf($teams); $c++) {
                    if ($pairings[$b]["plaintiff"] === $teams[$c]["number"]) {
                        $pNumber = $teams[$c]["number"];
                        $pName = $teams[$c]["name"];
                    } else if ($pairings[$b]["defense"] === $teams[$c]["number"]) {
                        $dNumber = $teams[$c]["number"];
                        $dName = $teams[$c]["name"];
                    }
                }
            }
        }
        for ($b = 0; $b < sizeOf($judges); $b++) {
            if ($ballots[$a]["judge"] === $judges[$b]["id"]) {
                $judgeName = $judges[$b]["name"];
            }
        }
        $url = HOST_NAME . "/judgeBallot.php?ballot=" . $ballots[$a]["url"];

        if ($ballots[$a]["locked"] == false) {
            $lockStatus = "Unlocked";
        } else if ($ballots[$a]["locked"] == true) {
            $lockStatus = "Locked";
        }
        $tabHTML[$roundNumber] .= "<span class='teamNumbers'>$pNumber v. $dNumber</span><a class='url' href='https://$url'>$judgeName</a><span class='lockStatus'>$lockStatus</span><br>";
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

        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>

        <!--Latest compiled and minified CSS-->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

        <!-- grid css -->
        <link rel="stylesheet" href="judgeURLs.css">

        <title>Judge URLs</title>
    </head>
    <body>
        <?php
        echo $header;
        ?>
        <div class="tab-content">
            <div id="round1"  class="tab-pane container active">
                <div id="round1Div">
                    <?php
                    echo $tabHTML[1];
                    ?> 
                </div>

            </div>
            <div id="round2" class="tab-pane container fade">
                <div id="round2Div">
                    <?php
                    echo $tabHTML[2];
                    ?> 
                </div>
            </div>
            <div id="round3" class="tab-pane container fade">
                <div id="round3Div">
                    <?php
                    echo $tabHTML[3];
                    ?> 
                </div>
            </div>
            <div id="round4" class="tab-pane container fade">
                <div id="round4Div">
                    <?php
                    echo $tabHTML[4];
                    ?> 
                </div>
            </div>
        </div>
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#round1">Round 1</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#round2">Round 2</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#round3">Round 3</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#round4">Round 4</a>
            </li>
        </ul>
    </body>
</html>
