<?php
session_start();
if($_SESSION["isAdmin"]){
    require_once __DIR__ . "/config.php";
    require_once SITE_ROOT . "/loginHeader.php";

    $number = htmlspecialchars(strip_tags($_GET["number"]));
}else{
    die ("Access denied");
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
        echo $header;
        ?>
        <table>
            <tr>
                <th id="number"><?php echo $number; ?></th>
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
                <input id="newImpermissible" type="number" size="5" max="9999">
                <input id="addButton" type="button" value="Add">
            </div>
        </div>
        
        <!-- Edit Modal -->
        <div id="editModal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Edit Impermissible</h4>
                    </div>
                    <div class="modal-body">
                        <p id="editModalText">Error</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Submit</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Conflict Modal -->
        <div id="deleteConflictModal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Update Team</h4>
                    </div>
                    <div class="modal-body">
                        <p id="deleteModalText">Are you sure you want to delete team N/A from this team's conflict list?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="deleteConflictButton" class="btn btn-default" data-dismiss="modal">Delete</button>
                    </div>
                </div>
            </div>
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
                        <p id="warningModalText">Some text in the modal.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Submit</button>
                    </div>
                </div>
            </div>
        </div>
        
        <script src="team.js"></script>
    </body>
</html>
