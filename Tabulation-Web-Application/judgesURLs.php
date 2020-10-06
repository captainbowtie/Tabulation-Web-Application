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
            if($ballots[$a]["judge"] === $judges[$b]["id"]){
                $judgeName = $judges[$b]["name"];
            }
        }
        $url = HOST_NAME."/judgeBallot.php?ballot=".$ballots[$a]["url"];
        
        $tabHTML[$roundNumber] .= "$pNumber - $pName v. $dNumber - $dName, $judgeName, <a href='https://$url'>$url</a><br>";
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
        <script src="https://code.jquery.com/jquery-3.5.0.js"></script>

        <!--Latest compiled and minified CSS-->
        <link rel = "stylesheet" href = "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity = "sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin = "anonymous">

        <!--Optional theme-->
        <link rel = "stylesheet" href = "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity = "sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin = "anonymous">

        <!--Latest compiled and minified JavaScript-->
        <script src = "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity = "sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin = "anonymous"></script>

        <title></title>
    </head>
    <body>
        <?php
        echo $header;
        ?>
        <div class="tab-content">
            <div id="round1" class="tab-pane fade in active">
                <?php
                echo $tabHTML[1];
                ?>    
            </div>
            <div id="round2" class="tab-pane fade">
                <?php
                echo $tabHTML[2];
                ?>
            </div>
            <div id="round3" class="tab-pane fade">
                <?php
                echo $tabHTML[3];
                ?>
            </div>
            <div id="round4" class="tab-pane fade">
                <?php
                echo $tabHTML[4];
                ?>
            </div>
        </div>
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#round1">Round 1</a></li>
            <li><a data-toggle="tab" href="#round2">Round 2</a></li>
            <li><a data-toggle="tab" href="#round3">Round 3</a></li>
            <li><a data-toggle="tab" href="#round4">Round 4</a></li>
        </ul>
    </body>
</html>
