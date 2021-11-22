<?php
session_start();
if ($_SESSION["isAdmin"]) {
    //import requirements
    require_once __DIR__ . "/config.php";
    require_once SITE_ROOT . "/database.php";
    require_once SITE_ROOT . "/objects/user.php";
    require_once SITE_ROOT . "/objects/team.php";
    require_once SITE_ROOT . "/loginHeader.php";

    $users = getAllUsers();
    $teams = getAllTeams();

    $bodyHTML = "<table>\n"
            . "<tr>\n"
            . "<th>Email</th>\n"
            . "<th>isAdmin</th>\n"
            . "<th>isCoach</th>\n"
            . "</tr>\n";
    for ($a = 0; $a < sizeof($users); $a++) {
        $bodyHTML .= "<tr id='user" . $users[$a]["id"] . "'>\n";
        $bodyHTML .= "<td><input class='email' type='email' id='email$a' value='" . $users[$a]["email"] . "'></td>\n";
        $bodyHTML .= "<td><input class='isAdmin' type='checkbox' id='isAdmin$a'";
        if ($users[$a]["isAdmin"]) {
            $bodyHTML .= " checked";
        }
        $bodyHTML .= "></td>\n";
        $bodyHTML .= "<td><input class='isCoach' type='checkbox' id='isCoach$a'";
        if ($users[$a]["isCoach"]) {
            $bodyHTML .= " checked";
        }
        $bodyHTML .= "></td>\n";
        $bodyHTML .= "<td><button class='passwordButton' id='password$a'>Change Password</button></td>\n";
        $bodyHTML .= "<td><button id='teams$a' data-user='" . $users[$a]["id"] . "' class='teamsButton'>Teams</button></td>\n";
        $bodyHTML .= "<td><a href='doLogin.php?url=" . $users[$a]["url"] . "'>Login Link</a></td>\n";
        $bodyHTML .= "<td><button class='urlButton' id='url$a'>Reset Link</button></td>\n";
        $bodyHTML .= "</tr>\n";
    }
    $bodyHTML .= "</table>\n";
    $bodyHTML .= "<button id='newUser'>New User...</button>\n";

    $teamsHTML = "";
    if (!empty($teams)) {
        for ($a = 0; $a < sizeOf($teams); $a++) {
            $teamsHTML .= "<div>\n";
            $teamsHTML .= "<label><input class='teamCheckbox' data-number='" . $teams[$a]["number"] . "' type='checkbox' id='" . $teams[$a]["number"] . "checkbox'>" . $teams[$a]["number"] . " " . $teams[$a]["name"] . "</label>\n";
            $teamsHTML .= "</div>\n";
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
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>

        <!--Latest compiled and minified CSS-->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">


        <title>Users</title>
    </head>
    <body>
        <?php
        echo $header;
        echo $bodyHTML;
        ?>

        <!-- Password Modal -->
        <div id="passwordModal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Change Password</h4>
                    </div>
                    <div class="modal-body">
                        <p id="passwordPrompt">Enter new password:</p>
                        <form>
                            <input type="password" id="newPassword" name="newPassword"><br>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="changePassword" class="btn btn-default" data-dismiss="modal">Change Password</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- New User Modal -->
        <div id="newUserModal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">New User</h4>
                    </div>
                    <div class="modal-body"><form>
                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email"><br>
                            <label for="password">Password:</label>
                            <input type="password" id="password" name="password"><br>
                            <label for="isAdmin">isAdmin:</label>
                            <input type="checkbox" id="isAdmin" name="isAdmin"><br>
                            <label for="isCoach">isCoach:</label>
                            <input type="checkbox" id="isCoach" name="isCoach"><br>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="addUser" class="btn btn-default" data-dismiss="modal">Add User</button>
                    </div>
                </div>
            </div>
        </div>   

        <!-- Conflicts Modal -->
        <div id="teamsModal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Coach's Teams</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>      
                    </div>
                    <div class="modal-body"><form>
                            <?php
                            echo $teamsHTML;
                            ?>
                        </form>
                    </div>
                    <div class="modal-footer">
                    </div>
                </div>
            </div>
        </div>
        
        <!-- URL Modal -->
        <div id="urlModal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Reset Login Link</h4>
                    </div>
                    <div class="modal-body">
                        <p id="urlPrompt">Resetting the link will disable the old link and create a new one.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="resetURL" class="btn btn-default" data-dismiss="modal">Reset Link</button>
                    </div>
                </div>
            </div>
        </div>

        <script src="users.js"></script>
    </body>
</html>
