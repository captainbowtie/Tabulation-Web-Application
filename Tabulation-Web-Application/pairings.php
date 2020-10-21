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
    require_once SITE_ROOT . "/objects/team.php";
    require_once SITE_ROOT . "/objects/settings.php";
    require_once SITE_ROOT . "/objects/judge.php";
    require_once SITE_ROOT . "/loginHeader.php";

    //get team data;
    $teams = getAllTeams();

    //get settings data
    $settings = getAllSettings();

    //get judge data
    $judges = getAllJudges();
    $judgesPerRound = getSetting("judgesPerRound");

    //generate team select content
    if (!empty($teams)) {
        $pSelects = [];
        $dSelects = [];
        for ($c = 1; $c <= 4; $c++) { //non-zero indexed to match round number
            for ($a = 0; $a < sizeOf($teams) / 2; $a++) {
                $pSelect = "<select class='pSelect' data-round='$c' data-pairing='$a' id='round$c" . "p$a' style='grid-row:" . ($a + 3) . "/" . ($a + 3) . "'>\n";
                $dSelect = "<select class='dSelect' data-round='$c' data-pairing='$a' id='round$c" . "d$a' style='grid-row:" . ($a + 3) . "/" . ($a + 3) . "'>\n";
                for ($b = 0; $b < sizeOf($teams); $b++) {
                    $pSelect = $pSelect . "<option value='" . $teams[$b]["number"] . "'>" . $teams[$b]["number"] . " " . $teams[$b]["name"] . "</option>\n";
                    $dSelect = $dSelect . "<option value='" . $teams[$b]["number"] . "'>" . $teams[$b]["number"] . " " . $teams[$b]["name"] . "</option>\n";
                }
                $pSelect .= "</select>\n";
                $dSelect .= "</select>\n";
                $pSelects[$c][] = $pSelect;
                $dSelects[$c][] = $dSelect;
            }
        }

        //fill judge selects
        $judgeSelects; //[round number][row number][column number] round indexed to 1, row and column 0-indexed
        for ($a = 1; $a <= 4; $a++) {//non-zero indexed to match actual round number
            for ($b = 0; $b < sizeOf($teams) / 2; $b++) {//number of rows equal to half the number of teams
                for ($c = 0; $c < $judgesPerRound; $c++) {//equal to number of judges per round
                    $judgeSelects[$a][$b][$c] = "<select class='judgeSelect' "
                            . "data-round='$a' "
                            . "data-pairing='$b' "
                            . "data-judge='$c' "
                            . "style='grid-row:" . ($b + 3) . "; grid-column:" . ($c + 3) . "'>\n";
                    for ($d = 0; $d < sizeOf($judges); $d++) {
                        if ($a === 1 && $judges[$d]["round1"]) {
                            $judgeSelects[$a][$b][$c] .= "<option value='" . $judges[$d]["id"] . "'>" . $judges[$d]["name"] . "</option>\n";
                        } else if ($a === 2 && $judges[$d]["round2"]) {
                            $judgeSelects[$a][$b][$c] .= "<option value='" . $judges[$d]["id"] . "'>" . $judges[$d]["name"] . "</option>\n";
                        } else if ($a === 3 && $judges[$d]["round3"]) {
                            $judgeSelects[$a][$b][$c] .= "<option value='" . $judges[$d]["id"] . "'>" . $judges[$d]["name"] . "</option>\n";
                        } else if ($a === 4 && $judges[$d]["round4"]) {
                            $judgeSelects[$a][$b][$c] .= "<option value='" . $judges[$d]["id"] . "'>" . $judges[$d]["name"] . "</option>\n";
                        }
                    }
                    $judgeSelects[$a][$b][$c] .= "</select>\n";
                }
            }
        }

        //fill page html variables
        $tabHTML = [];
        $tabHTML[1] = "<h3 id='round1Header'>Round 1</h3>
                        <p class='pHeader'>π</p>
                        <p class='dHeader'>∆</p>";
        $tabHTML[2] = "<h3 id='round1Header'>Round 2</h3>
                        <p class='pHeader'>π</p>
                        <p class='dHeader'>∆</p>";
        $tabHTML[3] = "<h3 id='round1Header'>Round 3</h3>
                        <p class='pHeader'>π</p>
                        <p class='dHeader'>∆</p>";
        $tabHTML[4] = "<h3 id='round1Header'>Round 4</h3>
                        <p class='pHeader'>π</p>
                        <p class='dHeader'>∆</p>";
        for ($a = 1; $a <= 4; $a++) {
            for ($b = 0; $b < sizeOf($teams) / 2; $b++) {
                $tabHTML[$a] .= "\n" . $pSelects[$a][$b] . "\n";
                $tabHTML[$a] .= "\n" . $dSelects[$a][$b] . "\n";
            }
            for ($b = 0; $b < sizeOf($teams) / 2; $b++) {
                for ($c = 0; $c < $judgesPerRound; $c++) {
                    $tabHTML[$a] .= $judgeSelects[$a][$b][$c];
                }
            }
            $tabHTML[$a] .= "<br>
                <input type='button' id='pair$a' value='Generate Pairings' class='pairButton' style='grid-column:1/1; grid-row:" . (3 + sizeOf($teams) / 2) . "'>
                <input type='submit' id='submit$a' value='Save Pairings' class='saveButton' style='grid-column:2/2; grid-row:" . (3 + sizeOf($teams) / 2) . "'>
            <input type = 'button' id = 'assignJudges$a' value = 'Generate Judge Assignments' class = 'assignJudgesButton' style = 'grid-column:3/3; grid-row:" . (3 + sizeOf($teams) / 2) . "'>
            <input type = 'submit' id = 'submitJudges$a' value = 'Save Judge Assignments' class = 'saveJudgesButton' style = 'grid-column:4/4; grid-row:" . (3 + sizeOf($teams) / 2) . "'>";
        }
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

        <!--Latest compiled and minified JavaScript-->
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>

        <!--Latest compiled and minified CSS-->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

        <link rel="stylesheet" href="pairings.css">
        <title></title>
    </head>
    <body>
        <?php
        echo $header;
        ?>
        <form id="settings">
            <label id="judgesPerRoundLabel" for="judgesPerRound">Judges Per Round:</label>
            <input id="judgesPerRound" value="<?php echo $settings["judgesPerRound"]; ?>">
            <label id="tieBreakerLabel" for="tieBreaker">Team Number Tiebreaker:</label>
            <select id="tieBreaker">
                <option <?php
                if ($settings["lowerTeamIsHigherRank"]) {
                    echo "selected";
                }
                ?> value="true">Lower Team Number Is Better Rank</option>
                <option <?php
                if (!$settings["lowerTeamIsHigherRank"]) {
                    echo "selected";
                }
                ?> value="false">Higher Team Number Is Better Rank</option>
            </select>
            <label id="snakeStartLabel" for="snakeStart">Start Round 3 Snake On:</label>
            <select id="snakeStart">
                <option <?php
                if ($settings["snakeStartsOnPlaintiff"]) {
                    echo "selected";
                }
                ?> value="true">Plaintiff</option>
                <option <?php
                if (!$settings["snakeStartsOnPlaintiff"]) {
                    echo "selected";
                }
                ?> value="false">Defense</option>
            </select>
        </form>
        <div class="tab-content">
            <div id="round1" class="tab-pane fade in active">
                <div id="round1Div">
                    <?php
                    echo $tabHTML[1];
                    ?>  
                </div>
            </div>
            <div id="round2" class="tab-pane fade">
                <div id="round2Div">
                    <?php
                    echo $tabHTML[2];
                    ?>  
                </div>
            </div>
            <div id="round3" class="tab-pane fade">
                <div id="round3Div">
                    <?php
                    echo $tabHTML[3];
                    ?>  
                </div>
            </div>
            <div id="round4" class="tab-pane fade">
                <div id="round4Div">
                    <?php
                    echo $tabHTML[4];
                    ?>  
                </div>
            </div>
        </div>
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#round1">Round 1</a></li>
            <li><a data-toggle="tab" href="#round2">Round 2</a></li>
            <li><a data-toggle="tab" href="#round3">Round 3</a></li>
            <li><a data-toggle="tab" href="#round4">Round 4</a></li>
        </ul>

        <!-- Pairings Warning Modal -->
        <div id="pairingsWarningModal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Warning</h4>
                    </div>
                    <div class="modal-body">
                        <p id="pairingsWarningModalText">Error. Modal unexpectedly displayed.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal" id="pairingSave">Save Pairings</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ballots Warning Modal -->
        <div id="ballotsWarningModal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Warning</h4>
                    </div>
                    <div class="modal-body">
                        <p id="ballotsWarningModalText">Error. Modal unexpectedly displayed.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal" id="ballotSave">Save Judges</button>
                    </div>
                </div>
            </div>
        </div>

        <script src="pairings.js"></script>
    </body>
</html>
