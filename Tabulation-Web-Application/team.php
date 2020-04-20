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
        <table>
            <tr>
                <th id="number">N/A</th>
                <th colspan="4" id="name">N/A</th>
            </tr>
            <tr>
                <td></td>
                <td>Round 1</td>
                <td>Round 2</td>
                <td>Round 3</td>
                <td>Round 4</td>
            </tr>
            <tr>
                <th>Side/Opponent</th>
                <td id="round1Pairing">N/A</td>
                <td id="round2Pairing">N/A</td>
                <td id="round3Pairing">N/A</td>
                <td id="round4Pairing">N/A</td>        
            </tr>
            <tr>
                <th>Round Result</th>
                <td id="round1Ballots">N/A</td>
                <td id="round2Ballots">N/A</td>
                <td id="round3Ballots">N/A</td>
                <td id="round4Ballots">N/A</td>    
            </tr>
            <tr>
                <th>Wins</th>
                <td id="round1Wins">N/A</td>
                <td id="round2Wins">N/A</td>
                <td id="round3Wins">N/A</td>
                <td id="round4Wins">N/A</td>    
            </tr>
            <tr>
                <th>CS</th>
                <td id="round1CS">N/A</td>
                <td id="round2CS">N/A</td>
                <td id="round3CS">N/A</td>
                <td id="round4CS">N/A</td>   
            </tr>
            <tr>
                <th>PD</th>
                <td id="round1PD">N/A</td>
                <td id="round2PD">N/A</td>
                <td id="round3PD">N/A</td>
                <td id="round4PD">N/A</td>
            </tr>
        </table>
        <br>
        <div id="impermissibleDiv">
            <h3>Impermissibles</h3>
            <div id="impermissibles">
                <p>None</p>
            </div>
            <div>
                <input type="number" size="5" max="9999">
                <input type="button" value="Add">
            </div>
        </div>
        <script src="team.js"></script>
        <script>
            $(document).ready(function () {
                fillData(<?php echo $_GET["number"]; ?>);
            });
        </script>
    </body>
</html>
