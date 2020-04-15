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
<?php
require_once __DIR__ . "/config.php";
require_once SITE_ROOT . "/database.php";
require_once SITE_ROOT . "/objects/team.php";
?>

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
                <td>Number</td>
                <td>Name</td>
                <td></td>
            </tr>
            <?php
            $teams = getAllTeams();
            foreach ($teams as $team) {
                echo "<tr>\n";
                echo "<td>".$team["number"]."</td>\n";
                echo "<td>".$team["name"]."</td>\n";
                echo "<td>"."<a href=''>edit</a>"."</td>\n";
                echo "</tr>\n";
            }
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
                    <input type="submit" id="submit" value="Submit">
                </td>
            </tr>
        </table>
        <script src="teams.js"></script>
    </body>
</html>
