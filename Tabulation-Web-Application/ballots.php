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

//import requirements
require_once __DIR__ . "/config.php";
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

        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

        <!-- grid css -->
        <link rel="stylesheet" href="ballots.css">

        <title></title>
    </head>
    <body>

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
        <div id="scoresDiv">
            <div class="accordion" id="scores">
                <div class="card">
                    <div class="card-header">
                        <h2 class="mb-0">
                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#openingScores" aria-expanded="true" aria-controls="openingScores">
                                Opening
                            </button>
                        </h2>
                    </div>

                    <div id="openingScores" class="collapse show" aria-labelledby="headingOne" data-parent="#scores">
                        <div class="card-body speech">
                            <label for="pOpen" class="pSpeechLabel">π Open:</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="0" class="pSpeech" id="pOpen" name="pOpen">
                            <label for="dOpen" class="dSpeechLabel">∆ Open:</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="0" class="dSpeech" id="dOpen" name="dOpen">
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h2 class="mb-0">
                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#p1Scores" aria-expanded="true" aria-controls="p1Scores">
                                Plaintiff Witness 1
                            </button>
                        </h2>
                    </div>

                    <div id="p1Scores" class="collapse" aria-labelledby="headingOne" data-parent="#scores">
                        <div class="card-body plaintiffWitness">
                            <label for="pDx1" class="pDxLabel" id="pDx1Label">Dx (Atty):</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="0" class="pDx" id="pDx1" name="pDx1">
                            <label for="dCx1" class="dCxLabel" id="dCx1Label">Cx (Atty):</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="0" class="dCx" id="dCx1" name="dCx1">
                            
                            <label for="pWDx1" class="pWDxLabel" id="pWDx1Label">Dx (Wit):</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="0" class="pWDx" id="pWDx1" name="pWDx1">
                            <label for="pWCx1" class="pWCxLabel" id="pWCx1Label">Cx (Wit):</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="0" class="pWCx" id="pWCx1" name="pWCx1">
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h2 class="mb-0">
                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#p2Scores" aria-expanded="true" aria-controls="p2Scores">
                                Plaintiff Witness 2
                            </button>
                        </h2>
                    </div>

                    <div id="p2Scores" class="collapse" aria-labelledby="headingOne" data-parent="#scores">
                        <div class="card-body plaintiffWitness">
                            <label for="pDx3" class="pDxLabel" id="pDx3Label">Dx (Atty):</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="0" class="pDx" id="pDx3" name="pDx3">
                            <label for="dCx3" class="dCxLabel" id="dCx3Label">Cx (Atty):</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="0" class="dCx" id="dCx3" name="dCx3">
                            
                            <label for="pWDx3" class="pWDxLabel" id="pWDx3Label">Dx (Wit):</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="0" class="pWDx" id="pWDx3" name="pWDx3">
                            <label for="pWCx3" class="pWCxLabel" id="pWCx3Label">Cx (Wit):</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="0" class="pWCx" id="pWCx3" name="pWCx3">
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h2 class="mb-0">
                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#p3Scores" aria-expanded="true" aria-controls="p3Scores">
                                Plaintiff Witness 3
                            </button>
                        </h2>
                    </div>

                    <div id="p3Scores" class="collapse" aria-labelledby="headingOne" data-parent="#scores">
                        <div class="card-body plaintiffWitness">
                            <label for="pDx2" class="pDxLabel" id="pDx2Label">Dx (Atty):</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="0" class="pDx" id="pDx2" name="pDx2">
                            <label for="dCx2" class="dCxLabel" id="dCx2Label">Cx (Atty):</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="0" class="dCx" id="dCx2" name="dCx2">
                            
                            <label for="pWDx2" class="pWDxLabel" id="pWDx2Label">Dx (Wit):</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="0" class="pWDx" id="pWDx2" name="pWDx2">
                            <label for="pWCx2" class="pWCxLabel" id="pWCx2Label">Cx (Wit):</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="0" class="pWCx" id="pWCx2" name="pWCx2">
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h2 class="mb-0">
                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#d1Scores" aria-expanded="true" aria-controls="d1Scores">
                                Defense Witness 1
                            </button>
                        </h2>
                    </div>

                    <div id="d1Scores" class="collapse" aria-labelledby="headingOne" data-parent="#scores">
                        <div class="card-body defenseWitness">
                            <label for="pCx1" class="pCxLabel" id="pCx1Label">Cx (Atty):</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="0" class="pCx" id="pCx1" name="pDx1">
                            <label for="dDx1" class="dDxLabel" id="dDx1Label">Dx (Atty):</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="0" class="dDx" id="dDx1" name="dCx1">
                            
                            <label for="dWDx1" class="dWDxLabel" id="dWDx1Label">Dx (Wit):</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="0" class="dWDx" id="dWDx1" name="dWDx1">
                            <label for="dWCx1" class="dWCxLabel" id="dWCx1Label">Cx (Wit):</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="0" class="dWCx" id="dWCx1" name="dWCx1">
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h2 class="mb-0">
                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#d2Scores" aria-expanded="true" aria-controls="d2Scores">
                                Defense Witness 2
                            </button>
                        </h2>
                    </div>

                    <div id="d2Scores" class="collapse" aria-labelledby="headingOne" data-parent="#scores">
                        <div class="card-body defenseWitness">
                            <label for="pCx2" class="pCxLabel" id="pCx2Label">Cx (Atty):</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="0" class="pCx" id="pCx2" name="pDx2">
                            <label for="dDx2" class="dDxLabel" id="dDx2Label">Dx (Atty):</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="0" class="dDx" id="dDx2" name="dCx2">
                            
                            <label for="dWDx2" class="dWDxLabel" id="dWDx2Label">Dx (Wit):</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="0" class="dWDx" id="dWDx2" name="dWDx2">
                            <label for="dWCx2" class="dWCxLabel" id="dWCx2Label">Cx (Wit):</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="0" class="dWCx" id="dWCx2" name="dWCx2">
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h2 class="mb-0">
                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#d3Scores" aria-expanded="true" aria-controls="d3Scores">
                                Defense Witness 3
                            </button>
                        </h2>
                    </div>

                    <div id="d3Scores" class="collapse" aria-labelledby="headingOne" data-parent="#scores">
                        <div class="card-body defenseWitness">
                            <label for="pCx3" class="pCxLabel" id="pCx3Label">Cx (Atty):</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="0" class="pCx" id="pCx3" name="pDx3">
                            <label for="dDx3" class="dDxLabel" id="dDx3Label">Dx (Atty):</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="0" class="dDx" id="dDx3" name="dCx3">
                            
                            <label for="dWDx3" class="dWDxLabel" id="dWDx3Label">Dx (Wit):</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="0" class="dWDx" id="dWDx3" name="dWDx3">
                            <label for="dWCx3" class="dWCxLabel" id="dWCx3Label">Cx (Wit):</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="0" class="dWCx" id="dWCx3" name="dWCx3">
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h2 class="mb-0">
                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#closingScores" aria-expanded="true" aria-controls="closingScores">
                                Closing
                            </button>
                        </h2>
                    </div>

                    <div id="closingScores" class="collapse" aria-labelledby="headingOne" data-parent="#scores">
                        <div class="card-body speech">
                            <label for="pClose" class="pSpeechLabel">π Close:</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="0" class="pSpeech" id="pClose" name="pClose">
                            <label for="dClose" class="dSpeechLabel">∆ Close:</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="0" class="dSpeech" id="dClose" name="dClose">
                        </div>
                    </div>
                </div>
            </div>
            <button id="submit">Submit</button>
        </div>

        <!-- Warning Modal -->
        <div id="warningModal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Warning</h4>
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
        
        <script src="https://code.jquery.com/jquery-3.5.0.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
        <script src="ballots.js"></script>
    </body>
</html>
