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

if (isset($_GET["ballot"])) {
    $url = htmlspecialchars(strip_tags($_GET["ballot"]));

    require_once __DIR__ . "/config.php";
    require_once SITE_ROOT . "/database.php";

    $ballotQuery = "SELECT * FROM ballots WHERE url = '$url'";

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
    $pairingQuery = "SELECT plaintiff,defense FROM pairings WHERE id = $pairingID";
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
        <script src="https://code.jquery.com/jquery-3.5.0.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>

        <!--Latest compiled and minified CSS-->
        <link rel = "stylesheet" href = "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity = "sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin = "anonymous">

        <!--Optional theme-->
        <link rel = "stylesheet" href = "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity = "sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin = "anonymous">

        <link rel="stylesheet" href="judgeBallot.css">

        <title></title>
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
                            <label for="pOpen" class="pSpeechLabel">π Open:</label>
                            <input type="number" min="0" max="10" step="1" size="2" class="pSpeech" id="pOpen" name="pOpen" value="<?php echo $scores["pOpen"]; ?>">
                            <label for="dOpen" class="dSpeechLabel">∆ Open:</label>
                            <input type="number" min="0" max="10" step="1" value="<?php echo $scores["dOpen"]; ?>" class="dSpeech" id="dOpen" name="dOpen">
                            <textarea class="pSpeechComments" id="pOpenComments">
                                <?php echo $scores["pOpenComments"]; ?>
                            </textarea>
                            <textarea class="dSpeechComments" id="dOpenComments">
                                <?php echo $scores["dOpenComments"]; ?>
                            </textarea>
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
                            <input type="number" min="0" max="10" step="1" size="2" value="<?php echo $scores["pDx1"]; ?>" class="pDx" id="pDx1" name="pDx1">
                            <label for="dCx1" class="dCxLabel" id="dCx1Label">Cx (Atty):</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="<?php echo $scores["dCx1"]; ?>" class="dCx" id="dCx1" name="dCx1">
                            <textarea class="pDxComments" id="pDx1Comments">
                                <?php echo $scores["pDx1Comments"]; ?>
                            </textarea>
                            <textarea class="dCxComments" id="dCx1Comments">
                                <?php echo $scores["dCx1Comments"]; ?>
                            </textarea>

                            <label for="pWDx1" class="pWDxLabel" id="pWDx1Label">Dx (Wit):</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="<?php echo $scores["pWDx1"]; ?>" class="pWDx" id="pWDx1" name="pWDx1">
                            <textarea class="pWDxComments" id="pWDx1Comments">
                                <?php echo $scores["pWDx1Comments"]; ?>
                            </textarea>

                            <label for="pWCx1" class="pWCxLabel" id="pWCx1Label">Cx (Wit):</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="<?php echo $scores["pWCx1"]; ?>" class="pWCx" id="pWCx1" name="pWCx1">
                            <textarea class="pWCxComments" id="dWCx1Comments">
                                <?php echo $scores["dWCx1Comments"]; ?>
                            </textarea>
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
                            <label for="pDx2" class="pDxLabel" id="pDx2Label">Dx (Atty):</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="<?php echo $scores["pDx2"]; ?>" class="pDx" id="pDx2" name="pDx2">
                            <label for="dCx2" class="dCxLabel" id="dCx2Label">Cx (Atty):</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="<?php echo $scores["dCx2"]; ?>" class="dCx" id="dCx2" name="dCx2">

                            <textarea class="pDxComments" id="pDx2Comments">
                                <?php echo $scores["pDx2Comments"]; ?>
                            </textarea>
                            <textarea class="dCxComments" id="dCx2Comments">
                                <?php echo $scores["dCx2Comments"]; ?>
                            </textarea>

                            <label for="pWDx2" class="pWDxLabel" id="pWDx2Label">Dx (Wit):</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="<?php echo $scores["pWDx2"]; ?>" class="pWDx" id="pWDx2" name="pWDx2">
                            <textarea class="pWDxComments" id="pWDx2Comments">
                                <?php echo $scores["pWDx2Comments"]; ?>
                            </textarea>
                            <label for="pWCx2" class="pWCxLabel" id="pWCx2Label">Cx (Wit):</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="<?php echo $scores["pWCx2"]; ?>" class="pWCx" id="pWCx2" name="pWCx2">
                            <textarea class="pWCxComments" id="dWCx2Comments">
                                <?php echo $scores["dWCx2Comments"]; ?>
                            </textarea>
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
                            <label for="pDx3" class="pDxLabel" id="pDx3Label">Dx (Atty):</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="<?php echo $scores["pDx3"]; ?>" class="pDx" id="pDx3" name="pDx3">
                            <label for="dCx3" class="dCxLabel" id="dCx3Label">Cx (Atty):</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="<?php echo $scores["dCx3"]; ?>" class="dCx" id="dCx3" name="dCx3">

                            <textarea class="pDxComments" id="pDx3Comments">
                                <?php echo $scores["pDx3Comments"]; ?>
                            </textarea>
                            <textarea class="dCxComments" id="dCx3Comments">
                                <?php echo $scores["dCx3Comments"]; ?>
                            </textarea>

                            <label for="pWDx3" class="pWDxLabel" id="pWDx3Label">Dx (Wit):</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="<?php echo $scores["pWDx3"]; ?>" class="pWDx" id="pWDx3" name="pWDx3">

                            <textarea class="pWDxComments" id="pWDx3Comments">
                                <?php echo $scores["pWDx3Comments"]; ?>
                            </textarea>

                            <label for="pWCx3" class="pWCxLabel" id="pWCx3Label">Cx (Wit):</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="<?php echo $scores["pWCx3"]; ?>" class="pWCx" id="pWCx3" name="pWCx3">
                            <textarea class="pWCxComments" id="dWCx3Comments">
                                <?php echo $scores["dWCx3Comments"]; ?>
                            </textarea>
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
                            <input type="number" min="0" max="10" step="1" size="2" value="<?php echo $scores["pCx1"]; ?>" class="pCx" id="pCx1" name="pDx1">
                            <label for="dDx1" class="dDxLabel" id="dDx1Label">Dx (Atty):</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="<?php echo $scores["dDx1"]; ?>" class="dDx" id="dDx1" name="dCx1">

                            <textarea class="pCxComments" id="pCx1Comments">
                                <?php echo $scores["pCx1Comments"]; ?>
                            </textarea>
                            <textarea class="dDxComments" id="dDx1Comments">
                                <?php echo $scores["dDx1Comments"]; ?>
                            </textarea>


                            <label for="dWDx1" class="dWDxLabel" id="dWDx1Label">Dx (Wit):</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="<?php echo $scores["dWDx1"]; ?>" class="dWDx" id="dWDx1" name="dWDx1">

                            <textarea class="dWDxComments" id="dWDx1Comments">
                                <?php echo $scores["dWDx1Comments"]; ?>
                            </textarea>

                            <label for="dWCx1" class="dWCxLabel" id="dWCx1Label">Cx (Wit):</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="<?php echo $scores["dWCx1"]; ?>" class="dWCx" id="dWCx1" name="dWCx1">

                            <textarea class="dWCxComments" id="dWCx1Comments">
                                <?php echo $scores["dWCx1Comments"]; ?>
                            </textarea>

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
                            <input type="number" min="0" max="10" step="1" size="2" value="<?php echo $scores["pCx2"]; ?>" class="pCx" id="pCx2" name="pDx2">
                            <label for="dDx2" class="dDxLabel" id="dDx2Label">Dx (Atty):</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="<?php echo $scores["dDx2"]; ?>" class="dDx" id="dDx2" name="dCx2">

                            <textarea class="pCxComments" id="pCx2Comments">
                                <?php echo $scores["pCx2Comments"]; ?>
                            </textarea>
                            <textarea class="dDxComments" id="dDx2Comments">
                                <?php echo $scores["dDx2Comments"]; ?>
                            </textarea>
                            
                            <label for="dWDx2" class="dWDxLabel" id="dWDx2Label">Dx (Wit):</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="<?php echo $scores["dWDx2"]; ?>" class="dWDx" id="dWDx2" name="dWDx2">
                           
                            <textarea class="dWDxComments" id="dWDx2Comments">
                                <?php echo $scores["dWDx2Comments"]; ?>
                            </textarea>
                            
                            <label for="dWCx2" class="dWCxLabel" id="dWCx2Label">Cx (Wit):</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="<?php echo $scores["dWCx2"]; ?>" class="dWCx" id="dWCx2" name="dWCx2">
                        
                        <textarea class="dWCxComments" id="dWCx2Comments">
                                <?php echo $scores["dWCx2Comments"]; ?>
                            </textarea>
                        
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
                            <input type="number" min="0" max="10" step="1" size="2" value="<?php echo $scores["pCx3"]; ?>" class="pCx" id="pCx3" name="pDx3">
                            <label for="dDx3" class="dDxLabel" id="dDx3Label">Dx (Atty):</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="<?php echo $scores["dDx3"]; ?>" class="dDx" id="dDx3" name="dCx3">

                            <textarea class="pCxComments" id="pCx3Comments">
                                <?php echo $scores["pCx3Comments"]; ?>
                            </textarea>
                            <textarea class="dDxComments" id="dDx3Comments">
                                <?php echo $scores["dDx3Comments"]; ?>
                            </textarea>
                            
                            <label for="dWDx3" class="dWDxLabel" id="dWDx3Label">Dx (Wit):</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="<?php echo $scores["dWDx3"]; ?>" class="dWDx" id="dWDx3" name="dWDx3">
                            
                            <textarea class="dWDxComments" id="dWDx3Comments">
                                <?php echo $scores["dWDx3Comments"]; ?>
                            </textarea>
                            
                            <label for="dWCx3" class="dWCxLabel" id="dWCx3Label">Cx (Wit):</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="<?php echo $scores["dWCx3"]; ?>" class="dWCx" id="dWCx3" name="dWCx3">
                        
                        <textarea class="dWCxComments" id="dWCx3Comments">
                                <?php echo $scores["dWCx3Comments"]; ?>
                            </textarea>
                        
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
                            <input type="number" min="0" max="10" step="1" size="2" value="<?php echo $scores["pClose"]; ?>" class="pSpeech" id="pClose">
                            <label for="dClose" class="dSpeechLabel">∆ Close:</label>
                            <input type="number" min="0" max="10" step="1" size="2" value="<?php echo $scores["dClose"]; ?>" class="dSpeech" id="dClose">
                        <textarea class="pSpeechComments" id="pCloseComments">
                                <?php echo $scores["pCloseComments"]; ?>
                            </textarea>
                            <textarea class="dSpeechComments" id="dCloseComments">
                                <?php echo $scores["dCloseComments"]; ?>
                            </textarea>
                        
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
                        <label for="atty1" class="attySelectLabel" id="atty1Label">Atty 1:</label>
                        <select id="atty1">
                            <?php echo $attySelectOptions ?>
                        </select>
                        <label for="atty2" class="attySelectLabel" id="atty2Label">Atty 2:</label>
                        <select id="atty2">
                            <?php echo $attySelectOptions ?>
                        </select>
                        <label for="atty3" class="attySelectLabel" id="atty3Label">Atty 3:</label>
                        <select id="atty3">
                            <?php echo $attySelectOptions ?>
                        </select>
                        <label for="atty4" class="attySelectLabel" id="atty4Label">Atty 4:</label>
                        <select id="atty4">
                            <?php echo $attySelectOptions ?>
                        </select>
                        <p id="witLabel">Outstanding Witnesses</p>
                        <label for="wit1" class="witSelectLabel" id="wit1Label">Wit 1:</label>
                        <select id="wit1">
                            <?php echo $witSelectOptions ?>
                        </select>
                        <label for="wit2" class="witSelectLabel" id="wit2Label">Wit 2:</label>
                        <select id="wit2">
                            <?php echo $witSelectOptions ?>
                        </select>
                        <label for="wit3" class="witSelectLabel" id="wit3Label">Wit 3:</label>
                        <select id="wit3">
                            <?php echo $witSelectOptions ?>
                        </select>
                        <label for="wit4" class="witSelectLabel" id="wit4Label">Wit 4:</label>
                        <select id="wit4">
                            <?php echo $attySelectOptions ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <button id="submit">Lock</button>
        <script>const url = "<?php echo $url; ?>"</script>
        <script src="judgeBallot.js"></script>
    </body>
</html>
