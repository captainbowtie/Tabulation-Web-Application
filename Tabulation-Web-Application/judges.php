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
    require_once __DIR__ . "/config.php";
    require_once SITE_ROOT . "/database.php";
    require_once SITE_ROOT . "/objects/judge.php";
    require_once SITE_ROOT . "/objects/team.php";
    require_once SITE_ROOT . "/loginHeader.php";

    $judges = getAllJudges();
    $teams = getAllTeams();

    $bodyHTML = "<table>\n"
            . "<tr>\n"
            . "<th>Name</th>\n"
            . "<th>Category</th>\n"
            . "<th>Round 1</th>\n"
            . "<th>Round 2</th>\n"
            . "<th>Round 3</th>\n"
            . "<th>Round 4</th>\n"
            . "</tr>\n";
    if (!empty($judges)) {
        for ($a = 0; $a < sizeof($judges); $a++) {
            $bodyHTML .= "<tr id='judge" . $judges[$a]["id"] . "'>\n";
            $bodyHTML .= "<td><input class='name' id='name$a' value='" . addslashes($judges[$a]["name"]) . "'></td>\n";
            $bodyHTML .= "<td>"
                    . "<select class='category' id='category$a'>\n";
            switch ($judges[$a]["category"]) {
                case 1:
                    $bodyHTML .= "<option value='1' selected>1</option>\n"
                            . "<option value='2'>2</option>\n"
                            . "<option value='3'>3</option>\n";
                    break;
                case 2:
                    $bodyHTML .= "<option value='1' selected>1</option>\n"
                            . "<option value='2' selected>2</option>\n"
                            . "<option value='3'>3</option>\n";
                    break;
                case 3:
                    $bodyHTML .= "<option value='1'>1</option>\n"
                            . "<option value='2'>2</option>\n"
                            . "<option value='3' selected>3</option>\n";
                    break;
                default:
                    break;
            }
            $bodyHTML .= "</select>\n</td>\n";
            $bodyHTML .= "<td><input class='round1' type='checkbox' id='round1$a'";
            if ($judges[$a]["round1"]) {
                $bodyHTML .= " checked";
            }
            $bodyHTML .= "></td>\n";
            $bodyHTML .= "<td><input class='round2' type='checkbox' id='round2$a'";
            if ($judges[$a]["round2"]) {
                $bodyHTML .= " checked";
            }
            $bodyHTML .= "></td>\n";
            $bodyHTML .= "<td><input class='round3' type='checkbox' id='round3$a'";
            if ($judges[$a]["round3"]) {
                $bodyHTML .= " checked";
            }
            $bodyHTML .= "></td>\n";
            $bodyHTML .= "<td><input class='round4' type='checkbox' id='round4$a'";
            if ($judges[$a]["round4"]) {
                $bodyHTML .= " checked";
            }
            $bodyHTML .= "></td>\n";
            $bodyHTML .= "<td><button class='conflictsButton' id='conflicts$a'>Conflicts...</button></td>\n";
            $bodyHTML .= "</tr>\n";
        }
    }

    $bodyHTML .= "</table>\n";
    $bodyHTML .= "<button id='newJudge'>New Judge...</button>\n";

    $conflictsHTML = "";
    if (!empty($teams)) {
        for ($a = 0; $a < sizeOf($teams); $a++) {
            $conflictsHTML .= "<div>\n";
            $conflictsHTML .= "<label><input class='conflictCheckbox' type='checkbox' id='".$teams[$a]["number"]."checkbox'>" . $teams[$a]["number"] ." ". $teams[$a]["name"] . "</label>\n";
            $conflictsHTML .= "</div>\n";
        }
    }
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
        <script src = "https://code.jquery.com/jquery-3.5.0.js"></script>

        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

        <title></title>
    </head>
    <body>
<?php
echo $header;
echo $bodyHTML;
?>

        <!-- New Judge Modal -->
        <div id="newJudgeModal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">New Judge</h4>
                    </div>
                    <div class="modal-body"><form>
                            <label for="name">Name:</label>
                            <input id="name" name="name"><br>
                            <label for="category">Category:</label>
                            <select id="category" name="category">
                                <option value="1">1: Non-Coach Judges, Trial Attorneys, Litigators</option>
                                <option value="2">2: Other Non-Coach Attorneys</option>
                                <option value="3">3: Coaches, Law Students, and Other Non-Attorneys</option>
                            </select><br>
                            <label for="round1">Round 1:</label>
                            <input type="checkbox" id="round1" name="round1"><br>
                            <label for="round1">Round 2:</label>
                            <input type="checkbox" id="round2" name="round2"><br>
                            <label for="round1">Round 3:</label>
                            <input type="checkbox" id="round3" name="round3"><br>
                            <label for="round1">Round 4:</label>
                            <input type="checkbox" id="round4" name="round4"><br>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="addJudge" class="btn btn-default" data-dismiss="modal">Add Judge</button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Conflicts Modal -->
        <div id="conflictsModal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Judge Conflicts</h4>
                    </div>
                    <div class="modal-body"><form>
                            <?php
                            echo $conflictsHTML;
                            ?>
                        </form>
                    </div>
                    <div class="modal-footer">
                    </div>
                </div>
            </div>
        </div>
        
        <script src="judges.js"></script>
    </body>
</html>
