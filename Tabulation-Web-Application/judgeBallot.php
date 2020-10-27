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

if (isset($_GET["ballot"])) {
    $url = htmlspecialchars(strip_tags($_GET["ballot"]));

    require_once __DIR__ . "/config.php";
    require_once SITE_ROOT . "/database.php";

    $ballotQuery = "SELECT * FROM ballots WHERE url = '$url' && locked = 0";

    if ($_SESSION["isAdmin"]) {
        $ballotQuery = "SELECT * FROM ballots WHERE url = '$url'";
    }

    //get data for ballot
    $db = new Database();
    $conn = $db->getConnection();
    $ballotResult = $conn->query($ballotQuery);
    $scores = $ballotResult->fetch_assoc();
    $ballotResult->close();

    //get judge name to put at top
    $judgeID = $scores["judge"];
    $judgeQuery = "SELECT name FROM judges WHERE id = $judgeID";
    $judgeResult = $conn->query($judgeQuery);
    $judge = $judgeResult->fetch_assoc();
    $judgeResult->close();
    $judgeName = $judge["name"];

    //get team numbers to put at top
    $pairingID = $scores["pairing"];
    $pairingQuery = "SELECT * FROM pairings WHERE id = $pairingID";
    $pairingResult = $conn->query($pairingQuery);
    $pairing = $pairingResult->fetch_assoc();
    $pairingResult->close();
    $pID = $pairing["plaintiff"];
    $dID = $pairing["defense"];
    $pQuery = "SELECT number FROM teams WHERE id = $pID";
    $dQuery = "SELECT number FROM teams WHERE id = $dID";
    $pResult = $conn->query($pQuery);
    $pTeam = $pResult->fetch_assoc();
    $pResult->close();
    $dResult = $conn->query($dQuery);
    $dTeam = $dResult->fetch_assoc();
    $dResult->close();
    $pTeamNumber = $pTeam["number"];
    $dTeamNumber = $dTeam["number"];

    //fill outstanding selects
    for ($a = 0; $a < 4; $a++) {
        //atty selects
        $attySelectOptions[$a] = "<option value='N/A'>---</option>";
        $attySelectOptions[$a] .= "<option value='" . $pairing["pDx1"] . "'";
        if ($scores["aty" . ($a + 1)] === $pairing["pDx1"]) {
            $attySelectOptions[$a] .= " selected";
        }
        $attySelectOptions[$a] .= ">" . $pairing["pDx1"] . "</option>\n";
        $attySelectOptions[$a] .= "<option value='" . $pairing["pDx2"] . "'";
        if ($scores["aty" . ($a + 1)] === $pairing["pDx2"]) {
            $attySelectOptions[$a] .= " selected";
        }
        $attySelectOptions[$a] .= ">" . $pairing["pDx2"] . "</option>\n";
        $attySelectOptions[$a] .= "<option value='" . $pairing["pDx3"] . "'";
        if ($scores["aty" . ($a + 1)] === $pairing["pDx3"]) {
            $attySelectOptions[$a] .= " selected";
        }
        $attySelectOptions[$a] .= ">" . $pairing["pDx3"] . "</option>\n";
        $attySelectOptions[$a] .= "<option value='" . $pairing["dDx1"] . "'";
        if ($scores["aty" . ($a + 1)] === $pairing["dDx1"]) {
            $attySelectOptions[$a] .= " selected";
        }
        $attySelectOptions[$a] .= ">" . $pairing["dDx1"] . "</option>\n";
        $attySelectOptions[$a] .= "<option value='" . $pairing["dDx2"] . "'";
        if ($scores["aty" . ($a + 1)] === $pairing["dDx2"]) {
            $attySelectOptions[$a] .= " selected";
        }
        $attySelectOptions[$a] .= ">" . $pairing["dDx2"] . "</option>\n";
        $attySelectOptions[$a] .= "<option value='" . $pairing["dDx3"] . "'";
        if ($scores["aty" . ($a + 1)] === $pairing["dDx3"]) {
            $attySelectOptions[$a] .= " selected";
        }
        $attySelectOptions[$a] .= ">" . $pairing["dDx3"] . "</option>\n";

        //wit selects
        $witSelectOptions[$a] = "<option value='N/A'>---</option>";
        $witSelectOptions[$a] .= "<option value='" . $pairing["pWDx1"] . "'";
        if ($scores["wit" . ($a + 1)] === $pairing["pWDx1"]) {
            $witSelectOptions[$a] .= " selected";
        }
        $witSelectOptions[$a] .= ">" . $pairing["pWDx1"] . " (" . $pairing["wit1"] . ")" . "</option>\n";
        $witSelectOptions[$a] .= "<option value='" . $pairing["pWDx2"] . "'";
        if ($scores["wit" . ($a + 1)] === $pairing["pWDx2"]) {
            $witSelectOptions[$a] .= " selected";
        }
        $witSelectOptions[$a] .= ">" . $pairing["pWDx2"] . " (" . $pairing["wit2"] . ")" . "</option>\n";
        $witSelectOptions[$a] .= "<option value='" . $pairing["pWDx3"] . "'";
        if ($scores["wit" . ($a + 1)] === $pairing["pWDx3"]) {
            $witSelectOptions[$a] .= " selected";
        }
        $witSelectOptions[$a] .= ">" . $pairing["pWDx3"] . " (" . $pairing["wit3"] . ")" . "</option>\n";
        $witSelectOptions[$a] .= "<option value='" . $pairing["dWDx1"] . "'";
        if ($scores["wit" . ($a + 1)] === $pairing["dWDx1"]) {
            $witSelectOptions[$a] .= " selected";
        }
        $witSelectOptions[$a] .= ">" . $pairing["dWDx1"] . " (" . $pairing["wit4"] . ")" . "</option>\n";
        $witSelectOptions[$a] .= "<option value='" . $pairing["dWDx2"] . "'";
        if ($scores["wit" . ($a + 1)] === $pairing["dWDx2"]) {
            $witSelectOptions[$a] .= " selected";
        }
        $witSelectOptions[$a] .= ">" . $pairing["dWDx2"] . " (" . $pairing["wit5"] . ")" . "</option>\n";
        $witSelectOptions[$a] .= "<option value='" . $pairing["dWDx3"] . "'";
        if ($scores["wit" . ($a + 1)] === $pairing["dWDx3"]) {
            $witSelectOptions[$a] .= " selected";
        }
        $witSelectOptions[$a] .= ">" . $pairing["dWDx3"] . " (" . $pairing["wit6"] . ")" . "</option>\n";
    }

    $conn->close();
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
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
        <!--Latest compiled and minified CSS-->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

        <!--Optional theme-->
        <link rel = "stylesheet" href = "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity = "sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin = "anonymous">

        <link rel="stylesheet" href="judgeBallot.css">

        <title>Ballot</title>
    </head>
    <body>
        <div>
            <p><?php echo $pTeamNumber; ?> v. <?php echo $dTeamNumber; ?></p>
            <p>Judge <?php echo $judgeName; ?></p>
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
                            <label for="pOpen" class="pSpeechLabel">π Open (<?php echo $pairing["pOpen"]; ?>):</label>
                            <input type="number" min="0" max="10" step="1" size="2" class="pSpeech" id="pOpen" name="pOpen" value="<?php echo $scores["pOpen"]; ?>">
                            <label for="dOpen" class="dSpeechLabel">∆ Open (<?php echo $pairing["dOpen"]; ?>):</label>
                            <input type="number" min="0" max="10" step="1" value="<?php echo $scores["dOpen"]; ?>" class="dSpeech" id="dOpen" name="dOpen">
                            <textarea class="pSpeechComments" id="pOpenComments"><?php echo $scores["pOpenComments"]; ?></textarea>
                            <textarea class="dSpeechComments" id="dOpenComments"><?php echo $scores["dOpenComments"]; ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h2 class="mb-0">
                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#p1Scores" aria-expanded="true" aria-controls="p1Scores">
                                Plaintiff Witness 1 (<?php echo $pairing["wit1"]; ?>)
                            </button>
                        </h2>
                    </div>

                    <div id="p1Scores" class="collapse" aria-labelledby="headingOne" data-parent="#scores">
                        <div class="card-body plaintiffWitness">
                            <label for="pDx1" class="pDxLabel" id="pDx1Label">Atty Dx (<?php echo $pairing["pDx1"]; ?>):</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="<?php echo $scores["pDx1"]; ?>" class="pDx" id="pDx1" name="pDx1">
                            <label for="dCx1" class="dCxLabel" id="dCx1Label">Atty Cx (<?php echo $pairing["dCx1"]; ?>):</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="<?php echo $scores["dCx1"]; ?>" class="dCx" id="dCx1" name="dCx1">
                            <textarea class="pDxComments" id="pDx1Comments"><?php echo $scores["pDx1Comments"]; ?></textarea>
                            <textarea class="dCxComments" id="dCx1Comments"><?php echo $scores["dCx1Comments"]; ?></textarea>

                            <label for="pWDx1" class="pWDxLabel" id="pWDx1Label">Wit Dx (<?php echo $pairing["pWDx1"]; ?>):</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="<?php echo $scores["pWDx1"]; ?>" class="pWDx" id="pWDx1" name="pWDx1">
                            <textarea class="pWDxComments" id="pWDx1Comments"><?php echo $scores["pWDx1Comments"]; ?></textarea>

                            <label for="pWCx1" class="pWCxLabel" id="pWCx1Label">Wit Cx (<?php echo $pairing["pWDx1"]; ?>):</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="<?php echo $scores["pWCx1"]; ?>" class="pWCx" id="pWCx1" name="pWCx1">
                            <textarea class="pWCxComments" id="pWCx1Comments"><?php echo $scores["pWCx1Comments"]; ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h2 class="mb-0">
                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#p2Scores" aria-expanded="true" aria-controls="p2Scores">
                                Plaintiff Witness 2 (<?php echo $pairing["wit2"]; ?>)
                            </button>
                        </h2>
                    </div>

                    <div id="p2Scores" class="collapse" aria-labelledby="headingOne" data-parent="#scores">
                        <div class="card-body plaintiffWitness">
                            <label for="pDx2" class="pDxLabel" id="pDx2Label">Atty Dx (<?php echo $pairing["pDx2"]; ?>):</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="<?php echo $scores["pDx2"]; ?>" class="pDx" id="pDx2" name="pDx2">
                            <label for="dCx2" class="dCxLabel" id="dCx2Label">Atty Cx (<?php echo $pairing["dCx2"]; ?>):</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="<?php echo $scores["dCx2"]; ?>" class="dCx" id="dCx2" name="dCx2">

                            <textarea class="pDxComments" id="pDx2Comments"><?php echo $scores["pDx2Comments"]; ?></textarea>
                            <textarea class="dCxComments" id="dCx2Comments"><?php echo $scores["dCx2Comments"]; ?></textarea>

                            <label for="pWDx2" class="pWDxLabel" id="pWDx2Label">Wit Dx (<?php echo $pairing["pWDx2"]; ?>):</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="<?php echo $scores["pWDx2"]; ?>" class="pWDx" id="pWDx2" name="pWDx2">
                            <textarea class="pWDxComments" id="pWDx2Comments"><?php echo $scores["pWDx2Comments"]; ?></textarea>
                            <label for="pWCx2" class="pWCxLabel" id="pWCx2Label">Wit Cx (<?php echo $pairing["pWDx2"]; ?>):</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="<?php echo $scores["pWCx2"]; ?>" class="pWCx" id="pWCx2" name="pWCx2">
                            <textarea class="pWCxComments" id="pWCx2Comments"><?php echo $scores["pWCx2Comments"]; ?></textarea>
                        </div>


                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h2 class="mb-0">
                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#p3Scores" aria-expanded="true" aria-controls="p3Scores">
                                Plaintiff Witness 3 (<?php echo $pairing["wit3"]; ?>)
                            </button>
                        </h2>
                    </div>

                    <div id="p3Scores" class="collapse" aria-labelledby="headingOne" data-parent="#scores">
                        <div class="card-body plaintiffWitness">
                            <label for="pDx3" class="pDxLabel" id="pDx3Label">Atty Dx (<?php echo $pairing["pDx3"]; ?>):</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="<?php echo $scores["pDx3"]; ?>" class="pDx" id="pDx3" name="pDx3">
                            <label for="dCx3" class="dCxLabel" id="dCx3Label">Atty Cx (<?php echo $pairing["dCx3"]; ?>):</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="<?php echo $scores["dCx3"]; ?>" class="dCx" id="dCx3" name="dCx3">

                            <textarea class="pDxComments" id="pDx3Comments"><?php echo $scores["pDx3Comments"]; ?></textarea>
                            <textarea class="dCxComments" id="dCx3Comments"><?php echo $scores["dCx3Comments"]; ?></textarea>

                            <label for="pWDx3" class="pWDxLabel" id="pWDx3Label">Wit Dx (<?php echo $pairing["pWDx3"]; ?>):</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="<?php echo $scores["pWDx3"]; ?>" class="pWDx" id="pWDx3" name="pWDx3">

                            <textarea class="pWDxComments" id="pWDx3Comments"><?php echo $scores["pWDx3Comments"]; ?></textarea>

                            <label for="pWCx3" class="pWCxLabel" id="pWCx3Label">Wit Cx (<?php echo $pairing["pWDx3"]; ?>):</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="<?php echo $scores["pWCx3"]; ?>" class="pWCx" id="pWCx3" name="pWCx3">
                            <textarea class="pWCxComments" id="pWCx3Comments"><?php echo $scores["pWCx3Comments"]; ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h2 class="mb-0">
                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#d1Scores" aria-expanded="true" aria-controls="d1Scores">
                                Defense Witness 1 (<?php echo $pairing["wit4"]; ?>)
                            </button>
                        </h2>
                    </div>

                    <div id="d1Scores" class="collapse" aria-labelledby="headingOne" data-parent="#scores">
                        <div class="card-body defenseWitness">
                            <label for="pCx1" class="pCxLabel" id="pCx1Label">Atty Cx (<?php echo $pairing["pCx1"]; ?>):</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="<?php echo $scores["pCx1"]; ?>" class="pCx" id="pCx1" name="pDx1">
                            <label for="dDx1" class="dDxLabel" id="dDx1Label">Atty Dx (<?php echo $pairing["dDx1"]; ?>):</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="<?php echo $scores["dDx1"]; ?>" class="dDx" id="dDx1" name="dCx1">

                            <textarea class="pCxComments" id="pCx1Comments"><?php echo $scores["pCx1Comments"]; ?></textarea>
                            <textarea class="dDxComments" id="dDx1Comments"><?php echo $scores["dDx1Comments"]; ?></textarea>


                            <label for="dWDx1" class="dWDxLabel" id="dWDx1Label">Wit Dx (<?php echo $pairing["dWDx1"]; ?>):</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="<?php echo $scores["dWDx1"]; ?>" class="dWDx" id="dWDx1" name="dWDx1">

                            <textarea class="dWDxComments" id="dWDx1Comments"><?php echo $scores["dWDx1Comments"]; ?></textarea>

                            <label for="dWCx1" class="dWCxLabel" id="dWCx1Label">Wit Cx (<?php echo $pairing["dWDx1"]; ?>):</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="<?php echo $scores["dWCx1"]; ?>" class="dWCx" id="dWCx1" name="dWCx1">

                            <textarea class="dWCxComments" id="dWCx1Comments"><?php echo $scores["dWCx1Comments"]; ?></textarea>

                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h2 class="mb-0">
                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#d2Scores" aria-expanded="true" aria-controls="d2Scores">
                                Defense Witness 2 (<?php echo $pairing["wit5"]; ?>)
                            </button>
                        </h2>
                    </div>

                    <div id="d2Scores" class="collapse" aria-labelledby="headingOne" data-parent="#scores">
                        <div class="card-body defenseWitness">
                            <label for="pCx2" class="pCxLabel" id="pCx2Label">Aty Cx (<?php echo $pairing["pCx2"]; ?>):</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="<?php echo $scores["pCx2"]; ?>" class="pCx" id="pCx2" name="pDx2">
                            <label for="dDx2" class="dDxLabel" id="dDx2Label">Aty Dx (<?php echo $pairing["dDx2"]; ?>):</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="<?php echo $scores["dDx2"]; ?>" class="dDx" id="dDx2" name="dCx2">

                            <textarea class="pCxComments" id="pCx2Comments"><?php echo $scores["pCx2Comments"]; ?></textarea>
                            <textarea class="dDxComments" id="dDx2Comments"><?php echo $scores["dDx2Comments"]; ?></textarea>

                            <label for="dWDx2" class="dWDxLabel" id="dWDx2Label">Wit Dx (<?php echo $pairing["dWDx2"]; ?>):</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="<?php echo $scores["dWDx2"]; ?>" class="dWDx" id="dWDx2" name="dWDx2">

                            <textarea class="dWDxComments" id="dWDx2Comments"><?php echo $scores["dWDx2Comments"]; ?></textarea>

                            <label for="dWCx2" class="dWCxLabel" id="dWCx2Label">Wit Cx (<?php echo $pairing["dWDx2"]; ?>):</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="<?php echo $scores["dWCx2"]; ?>" class="dWCx" id="dWCx2" name="dWCx2">

                            <textarea class="dWCxComments" id="dWCx2Comments"><?php echo $scores["dWCx2Comments"]; ?></textarea>

                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h2 class="mb-0">
                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#d3Scores" aria-expanded="true" aria-controls="d3Scores">
                                Defense Witness 3 (<?php echo $pairing["wit6"]; ?>)
                            </button>
                        </h2>
                    </div>

                    <div id="d3Scores" class="collapse" aria-labelledby="headingOne" data-parent="#scores">
                        <div class="card-body defenseWitness">
                            <label for="pCx3" class="pCxLabel" id="pCx3Label">Atty Cx (<?php echo $pairing["pCx3"]; ?>):</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="<?php echo $scores["pCx3"]; ?>" class="pCx" id="pCx3" name="pDx3">
                            <label for="dDx3" class="dDxLabel" id="dDx3Label">Atty Dx (<?php echo $pairing["dDx3"]; ?>):</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="<?php echo $scores["dDx3"]; ?>" class="dDx" id="dDx3" name="dCx3">

                            <textarea class="pCxComments" id="pCx3Comments"><?php echo $scores["pCx3Comments"]; ?></textarea>
                            <textarea class="dDxComments" id="dDx3Comments"><?php echo $scores["dDx3Comments"]; ?></textarea>

                            <label for="dWDx3" class="dWDxLabel" id="dWDx3Label">Wit Dx (<?php echo $pairing["dWDx3"]; ?>):</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="<?php echo $scores["dWDx3"]; ?>" class="dWDx" id="dWDx3" name="dWDx3">

                            <textarea class="dWDxComments" id="dWDx3Comments"><?php echo $scores["dWDx3Comments"]; ?></textarea>

                            <label for="dWCx3" class="dWCxLabel" id="dWCx3Label">Wit Cx (<?php echo $pairing["dWDx3"]; ?>):</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="<?php echo $scores["dWCx3"]; ?>" class="dWCx" id="dWCx3" name="dWCx3">

                            <textarea class="dWCxComments" id="dWCx3Comments"><?php echo $scores["dWCx3Comments"]; ?></textarea>

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
                            <label for="pClose" class="pSpeechLabel">π Close (<?php echo $pairing["pClose"]; ?>):</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="<?php echo $scores["pClose"]; ?>" class="pSpeech" id="pClose">
                            <label for="dClose" class="dSpeechLabel">∆ Close (<?php echo $pairing["dClose"]; ?>):</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="<?php echo $scores["dClose"]; ?>" class="dSpeech" id="dClose">
                            <textarea class="pSpeechComments" id="pCloseComments"><?php echo $scores["pCloseComments"]; ?></textarea>
                            <textarea class="dSpeechComments" id="dCloseComments"><?php echo $scores["dCloseComments"]; ?></textarea>

                        </div>
                    </div>
                </div>
            </div>


            <div class="card">
                <div class="card-header">
                    <h2 class="mb-0">
                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#individualAwards" aria-expanded="true" aria-controls="individualAwards">
                            Individual Awards
                        </button>
                    </h2>
                </div>

                <div id="individualAwards" class="collapse" aria-labelledby="headingOne" data-parent="#scores">
                    <div class="card-body" id="awards">
                        <p id="attyLabel">Outstanding Attorneys</p>
                        <label for="aty1" class="attySelectLabel" id="atty1Label">Atty 1:</label>
                        <select id="aty1">
                            <?php echo $attySelectOptions[0] ?>
                        </select>
                        <label for="aty2" class="attySelectLabel" id="atty2Label">Atty 2:</label>
                        <select id="aty2">
                            <?php echo $attySelectOptions[1] ?>
                        </select>
                        <label for="aty3" class="attySelectLabel" id="atty3Label">Atty 3:</label>
                        <select id="aty3">
                            <?php echo $attySelectOptions[2] ?>
                        </select>
                        <label for="aty4" class="attySelectLabel" id="atty4Label">Atty 4:</label>
                        <select id="aty4">
                            <?php echo $attySelectOptions[3] ?>
                        </select>
                        <p id="witLabel">Outstanding Witnesses</p>
                        <label for="wit1" class="witSelectLabel" id="wit1Label">Wit 1:</label>
                        <select id="wit1">
                            <?php echo $witSelectOptions[0] ?>
                        </select>
                        <label for="wit2" class="witSelectLabel" id="wit2Label">Wit 2:</label>
                        <select id="wit2">
                            <?php echo $witSelectOptions[1] ?>
                        </select>
                        <label for="wit3" class="witSelectLabel" id="wit3Label">Wit 3:</label>
                        <select id="wit3">
                            <?php echo $witSelectOptions[2] ?>
                        </select>
                        <label for="wit4" class="witSelectLabel" id="wit4Label">Wit 4:</label>
                        <select id="wit4">
                            <?php echo $witSelectOptions[3] ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <button id="submit">Lock</button>

        <!-- Lock Modal -->
        <div id="lockModal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"></h4>
                    </div>
                    <div class="modal-body" id="lockModalText">
                        Locking the ballot will prevent further changes. If you need to modify the ballot, you will have to contact the tournament's tabulation director at allen@allenbarr.com. Would you like to lock the ballot?
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="lockButton" class="btn btn-default" data-dismiss="modal">Lock Ballot</button>
                    </div>
                </div>
            </div>
        </div>

        <script>const url = "<?php echo $url; ?>"</script>
        <script src="judgeBallot.js"></script>
    </body>
</html>
