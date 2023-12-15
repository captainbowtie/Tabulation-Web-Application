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
    $currentRound = 1;

    for ($a = 0; $a < 4; $a++) {
        $tabHTML[$a + 1] = "<table>\n";
        $tabHTML[$a + 1] .= "<tr>\n<th>Pairing</th>\n<th>Judge</th>\n<th>Ballot Status</th>\n<th>Score</th>\n</tr>";
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
            //set current round
            if ($pairings[$b]["round"] > $currentRound) {
                $currentRound = $pairings[$b]["round"];
            }
        }
        for ($b = 0; $b < sizeOf($judges); $b++) {
            if ($ballots[$a]["judge"] === $judges[$b]["id"]) {
                $judgeName = $judges[$b]["name"];
            }
        }
        $url = "judgeBallot.php?ballot=" . $ballots[$a]["url"];

        if ($ballots[$a]["locked"] == false) {
            $lockStatus = "Unlocked";
        } else if ($ballots[$a]["locked"] == true) {
            $lockStatus = "Locked";
        }
        $tabHTML[$roundNumber] .= "<tr id='" . $ballots[$a]["id"] . "'>\n<td class='teamNumbers'>$pNumber v. $dNumber</td>\n<td><a class='url' href='$url'>$judgeName</a></td>\n<td class='status'>$lockStatus</td>\n<td class='score'>0</td>\n</tr>\n";
    }
    for ($a = 0; $a < 4; $a++) {
        $tabHTML[$a + 1] .= "</table>\n";
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
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!--Latest compiled and minified JavaScript-->
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>

        <!--Latest compiled and minified CSS-->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

        <link rel="stylesheet" href="judgeURLs.css">

        <title>Judge URLs</title>
    </head>
    <body>
        <?php
        echo $header;
        ?>
        <div class="tab-content">
            <div id="round1"  class="tab-pane fade in active">
                <div id="round1Div">
                    <h3>Round 1</h3>
                    <br>
                    <?php
                    echo $tabHTML[1];
                    ?> 
                </div>

            </div>
            <div id="round2" class="tab-pane fade">
                <div id="round2Div">
                    <h3>Round 2</h3>
                    <?php
                    echo $tabHTML[2];
                    ?> 
                </div>
            </div>
            <div id="round3" class="tab-pane fade">
                <div id="round3Div">
                    <h3>Round 3</h3>
                    <?php
                    echo $tabHTML[3];
                    ?> 
                </div>
            </div>
            <div id="round4" class="tab-pane fade">
                <div id="round4Div">
                    <h3>Round 4</h3>
                    <?php
                    echo $tabHTML[4];
                    ?> 
                </div>
            </div>
        </div>
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#round1">Round 1</a>
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
        <script type="text/javascript" src="judgeURLs.js"></script>
        <script>
            currentRound = <?php echo $currentRound; ?>;
        </script>
    </body>
</html>
