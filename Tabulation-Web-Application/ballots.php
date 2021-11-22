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

//import requirements
require_once __DIR__ . "/config.php";
require_once SITE_ROOT . "/loginHeader.php";

if ($_SESSION["isAdmin"] || $_SESSION["isCoach"]) {
    
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
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>

        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>

        <!--Latest compiled and minified CSS-->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

        <!-- grid css -->
        <link rel="stylesheet" href="ballots.css">

        <title></title>
    </head>
    <body>
        <?php
        echo $navigation;
        echo $header;
        ?>
        <div id="selects">
            <select id="round"> 
                <option>Round 1</option>
                <option>Round 2</option>
                <option>Round 3</option>
                <option>Round 4</option>
            </select>
            <select id="pairing">
            </select>
            <select id="ballot">
            </select>
        </div>
        <div id="tabRoomPD"></div>
        <h3>Opening</h3>
        <div id="scoresDiv">
            <div id="openingScores" class="speech">
                <label for="pOpen" class="pSpeechLabel">π Open:</label>
                <p class="pSpeech" id="pOpen"></p>
                <label for="dOpen" class="dSpeechLabel">∆ Open:</label>
                <p class="dSpeech" id="dOpen"></p>
            </div>
            <h3>Witness 1</h3>
            <div id="p1Scores" class="plaintiffWitness">
                <label for="pDx1" class="pDxLabel" id="pDx1Label">Dx (Atty):</label>
                <p class="pDx" id="pDx1"></p>
                <label for="dCx1" class="dCxLabel" id="dCx1Label">Cx (Atty):</label>
                <p class="dCx" id="dCx1"></p>
                <label for="pWDx1" class="pWDxLabel" id="pWDx1Label">Dx (Wit):</label>
                <p class="pWDx" id="pWDx1"></p>
                <label for="pWCx1" class="pWCxLabel" id="pWCx1Label">Cx (Wit):</label>
                <p class="pWCx" id="pWCx1"></p>
            </div>
            <h3>Witness 2</h3>
            <div id="p2Scores" class="plaintiffWitness">
                <label for="pDx2" class="pDxLabel" id="pDx2Label">Dx (Atty):</label>
                <p class="pDx" id="pDx2"></p>
                <label for="dCx2" class="dCxLabel" id="dCx2Label">Cx (Atty):</label>
                <p class="dCx" id="dCx2"></p>
                <label for="pWDx2" class="pWDxLabel" id="pWDx2Label">Dx (Wit):</label>
                <p class="pWDx" id="pWDx2"></p>
                <label for="pWCx2" class="pWCxLabel" id="pWCx2Label">Cx (Wit):</label>
                <p class="pWCx" id="pWCx2"></p></div>
            <h3>Witness 3</h3>
            <div id="p3Scores" class="plaintiffWitness">
                <label for="pDx3" class="pDxLabel" id="pDx3Label">Dx (Atty):</label>
                <p class="pDx" id="pDx3"></p>
                <label for="dCx3" class="dCxLabel" id="dCx3Label">Cx (Atty):</label>
                <p class="dCx" id="dCx3"></p>
                <label for="pWDx3" class="pWDxLabel" id="pWDx3Label">Dx (Wit):</label>
                <p class="pWDx" id="pWDx3"></p>
                <label for="pWCx3" class="pWCxLabel" id="pWCx3Label">Cx (Wit):</label>
                <p class="pWCx" id="pWCx3"></p>
            </div>
            <h3>Witness 4</h3>
            <div id="d1Scores" class="defenseWitness">
                <label for="pCx1" class="pCxLabel" id="pCx1Label">Cx (Atty):</label>
                <p class="pCx" id="pCx1"></p>
                <label for="dDx1" class="dDxLabel" id="dDx1Label">Dx (Atty):</label>
                <p class="dDx" id="dDx1"></p>

                <label for="dWDx1" class="dWDxLabel" id="dWDx1Label">Dx (Wit):</label>
                <p class="dWDx" id="dWDx1"></p>
                <label for="dWCx1" class="dWCxLabel" id="dWCx1Label">Cx (Wit):</label>
                <p class="dWCx" id="dWCx1"></p>
            </div>
            <h3>Witness 5</h3>
            <div id="d2Scores" class="defenseWitness">
                <label for="pCx2" class="pCxLabel" id="pCx2Label">Cx (Atty):</label>
                <p class="pCx" id="pCx2"></p>
                <label for="dDx2" class="dDxLabel" id="dDx2Label">Dx (Atty):</label>
                <p class="dDx" id="dDx2"></p>

                <label for="dWDx2" class="dWDxLabel" id="dWDx2Label">Dx (Wit):</label>
                <p class="dWDx" id="dWDx2"></p>
                <label for="dWCx2" class="dWCxLabel" id="dWCx2Label">Cx (Wit):</label>
                <p class="dWCx" id="dWCx2"></p>
            </div>
            <h3>Witness 6</h3>
            <div id="d3Scores" class="defenseWitness">
                <label for="pCx3" class="pCxLabel" id="pCx3Label">Cx (Atty):</label>
                <p class="pCx" id="pCx3"></p>
                <label for="dDx3" class="dDxLabel" id="dDx3Label">Dx (Atty):</label>
                <p class="dDx" id="dDx3"></p>

                <label for="dWDx3" class="dWDxLabel" id="dWDx3Label">Dx (Wit):</label>
                <p class="dWDx" id="dWDx3"></p>
                <label for="dWCx3" class="dWCxLabel" id="dWCx3Label">Cx (Wit):</label>
                <p class="dWCx" id="dWCx3"></p>
            </div>
            <h3>Closing</h3>
            <div id="closingScores" class='speech'>
                <label for="pClose" class="pSpeechLabel">π Close:</label>
                <p class="pSpeech" id="pClose"></p>
                <label for="dClose" class="dSpeechLabel">∆ Close:</label>
                <p class="dSpeech" id="dClose"></p>
            </div>
        </div>
        <button id="unlockButton">Unlock Ballot</button>

        <!-- Warning Modal -->
        <div id="warningModal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Ballot Unlocked</h4>
                    </div>
                    <div class="modal-body">
                        <p id="warningModalText">Error. Modal unexpectedly displayed.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal" id="modalDismissButton">Dismiss</button>
                    </div>
                </div>
            </div>
        </div>

        <script src="ballots.js"></script>
    </body>
</html>