<?php
//import requirements
require_once __DIR__ . "/config.php";
require_once SITE_ROOT . "/database.php";
require_once SITE_ROOT . "/objects/team.php";

//get team data;
$teams = getAllTeams();

//generate select content
$pSelects = [];
$dSelects = [];
for ($c = 1; $c <= 4; $c++) { //non-zero indexed to match round number
    for ($a = 0; $a < sizeOf($teams) / 2; $a++) {
        $pSelect = "<select id='round$c" . "p$a'>\n";
        $dSelect = "<select id='round$c" . "d$a'>\n";
        for ($b = 0; $b < sizeOf($teams); $b++) {
            $pSelect = $pSelect . "<option value='" . $teams[$b]["number"] . "'>" . $teams[$b]["number"] . " " . $teams[$b]["name"] . "</option>\n";
            $dSelect = $pSelect . "<option value='" . $teams[$b]["number"] . "'>" . $teams[$b]["number"] . " " . $teams[$b]["name"] . "</option>\n";
        }
        $pSelect = $pSelect . "</select>\n";
        $dSelect = $dSelect . "</select>\n";
        $pSelects[$c][] = $pSelect;
        $dSelects[$c][] = $dSelect;
    }
}

//fill page html variables
$tabHTML = [];
$tabHTML[1] = "";
$tabHTML[2] = "";
$tabHTML[3] = "";
$tabHTML[4] = "";
for ($b = 1; $b <= 4; $b++) {
    for ($a = 0; $a < sizeOf($pSelects[$b]); $a++) {
        $tabHTML[$b] .= "<tr>\n";
        $tabHTML[$b] .= "<td>\n" . $pSelects[$b][$a] . "</td>\n";
        $tabHTML[$b] .= "<td>\n" . $dSelects[$b][$a] . "</td>\n";
        $tabHTML[$b] .= "</tr>\n";
    }
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
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://code.jquery.com/jquery-3.5.0.js"></script>

        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>


        <title></title>
    </head>
    <body>

        <div class="tab-content">
            <div id="round1" class="tab-pane fade in active">
                <h3>Round 1</h3>
                <table>
                    <tr>
                        <th>π</th>
                        <th>∆</th>
                    </tr>
                    <?php
                    echo $tabHTML[1];
                    ?>
                </table>
                <br>
                <input type="submit" value="Submit">
            </div>
            <div id="round2" class="tab-pane fade">
                <h3>Round 2</h3>
                <table>
                    <tr>
                        <th>π</th>
                        <th>∆</th>
                    </tr>
                    <?php
                    echo $tabHTML[2];
                    ?>
                </table>
                <br>
                <input type="submit" value="Submit">
            </div>
            <div id="round3" class="tab-pane fade">
                <h3>Round 3</h3>
                <table>
                    <tr>
                        <th>π</th>
                        <th>∆</th>
                    </tr>
                    <?php
                    echo $tabHTML[3];
                    ?>
                </table>
                <br>
                <input type="submit" value="Submit">
            </div>
            <div id="round4" class="tab-pane fade">
                <h3>Round 4</h3>
                <table>
                    <tr>
                        <th>π</th>
                        <th>∆</th>
                    </tr>
                    <?php
                    echo $tabHTML[4];
                    ?>
                </table>
                <br>
                <input type="submit" value="Submit">
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
