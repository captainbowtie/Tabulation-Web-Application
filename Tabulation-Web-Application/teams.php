<?php
session_start();
if ($_SESSION["isAdmin"]) {
    //import requirements
    require_once __DIR__ . "/config.php";
    require_once SITE_ROOT . "/database.php";
    require_once SITE_ROOT . "/objects/team.php";
    require_once SITE_ROOT . "/loginHeader.php";


//prepare strings for team table
    $tableHTML = "";
    $teams = getAllTeams();
    if (!empty($teams)) {
        foreach ($teams as $team) {
            $number = $team["number"];
            $name = $team["name"];
            $tableHTML .= "<tr>\n";
            $tableHTML .= "<td><a href='/team.php?number=$number'>$number</a></td>\n";
            $tableHTML .= "<td id='{$number}name'>$name</td>\n";
            $tableHTML .= "<td><a href='' class='edit' data-number='$number'>edit</a></td>\n";
            $tableHTML .= "<td><a href='' class='delete' data-number='$number'>delete</a></td>\n";
            $tableHTML .= "</tr>\n";
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

        <!--Latest compiled and minified CSS-->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

        <!-- Latest compiled and minified JavaScript -->
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>

        <title></title>
    </head>
    <body>
        <?php
        echo $navigation;
        echo $header;
        ?>
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
                        <p id="warningModalText">Some text in the modal.</p>
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
                    <div class="modal-body">
                        <form>
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

        <!-- Delete team Modal -->
        <div id="deleteModal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Delete Team</h4>
                    </div>
                    <div class="modal-body">
                        <p id="deleteText">Error: delete modal unexpectedly displayed.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="deleteTeam" class="btn btn-default" data-dismiss="modal">Delete</button>
                    </div>
                </div>
            </div>
        </div>

        <script src="teams.js"></script>
    </body>
</html>
