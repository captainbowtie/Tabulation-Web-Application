<?php
session_start();
if ($_SESSION["isAdmin"]) {
    //import requirements
    require_once __DIR__ . "/config.php";
    require_once SITE_ROOT . "/database.php";
    require_once SITE_ROOT . "/objects/team.php";

//prepare strings for team table
    $tableHTML = "";
    $teams = getAllTeams();
    if (!empty($teams)) {
        foreach ($teams as $team) {
            $tableHTML .= "<tr>\n";
            $tableHTML .= "<td><a href='/team.php?number=" . $team["number"] . "'>" . $team["number"] . "</a></td>\n";
            $tableHTML .= "<td id='" . $team["number"] . "name'>" . $team["name"] . "</td>\n";
            $tableHTML .= "<td>" . "<a href='' class='edit' id='edit" . $team["number"] . "'>edit</a>" . "</td>\n";
            $tableHTML .= "</tr>\n";
        }
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
        <div id="teamTable"></div>
        <table>
            <tr>
                <th>Number</th>
                <th>Name</th>
                <th></th>
            </tr>
            <?php
            echo $tableHTML;
            ?>
            <tr>
                <td>
                    <label for="number"></label>
                    <input type="number" id="number" name="number" size="5" max="1999">
                </td>
                <td>
                    <label for="name"></label>
                    <input type="text" id="name" name="name">
                </td>
                <td>    
                    <input type="submit" id="addTeam" value="Add Team">
                </td>
            </tr>
        </table>

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
                        <form>
                            <label for="fname">First name:</label>
                            <input type="text" id="fname" name="fname"><br><br>
                            <label for="lname">Last name:</label>
                            <input type="text" id="lname" name="lname"><br><br>
                        </form>
                        <p id="warningModalText">Some text in the modal.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Submit</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Team Update Modal -->
        <div id="teamUpdateModal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Update Team</h4>
                    </div>
                    <div class="modal-body"><form>
                            <label for="updateNumber">Number:</label>
                            <input type="number" id="updateNumber" name="updateNumber" size="5" max="1999">
                            <label for="updateName">Name:</label>
                            <input type="text" id="updateName" name="updateName">
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="updateTeam" class="btn btn-default" data-dismiss="modal">Update</button>
                    </div>
                </div>
            </div>
        </div>

        <script src="teams.js"></script>
    </body>
</html>
