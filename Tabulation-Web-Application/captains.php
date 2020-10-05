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

if (isset($_GET["round"])) {
    $url = htmlspecialchars(strip_tags($_GET["round"]));
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

        <link rel="stylesheet" href="captains.css">

        <title></title>
    </head>
    <body>
        <?php
        echo $header;
        ?>
        <div id="inputDiv">
            <label id="pOpenLabel" for="pOpen">Plaintiff Open:</label>
            <input id="pOpen">
            <label id="dOpenLabel" for="dOpen">Defense Open:</label>
            <input id="dOpen">
            
            <select id="wit1">
                <option value="Rodriguez">Rodriguez</option>
                <option value="Paras">Paras</option>
                <option value="OKeefe">O'Keefe</option>
                <option value="Kwon">Kwon</option>
                <option value="Cannon">Cannon</option>
                <option value="Arnould">Arnould</option>
                <option value="Francazio">Francazio</option>
                <option value="Soto">Soto</option>      
            </select>
            <label id="pDx1Label" for="pDx1">Directing Attorney:</label>
            <input id="pDx1">
            <label id="pWDx1Label" for="pWDx1">Witness:</label>
            <input id="pWDx1">
            <label id="dCx1Label" for="dCx1">Crossing Attorney:</label>
            <input id="dCx1">

            <select id="wit2">
                <option value="Rodriguez">Rodriguez</option>
                <option value="Paras">Paras</option>
                <option value="OKeefe">O'Keefe</option>
                <option value="Kwon">Kwon</option>
                <option value="Cannon">Cannon</option>
                <option value="Arnould">Arnould</option>
                <option value="Francazio">Francazio</option>
                <option value="Soto">Soto</option>      
            </select>
            <label id="pDx2Label" for="pDx2">Directing Attorney:</label>
            <input id="pDx2">
            <label id="pWDx2Label" for="pWDx2">Witness:</label>
            <input id="pWDx2">
            <label id="dCx2Label" for="dCx2">Crossing Attorney:</label>
            <input id="dCx2">

            <select id="wit3">
                <option value="Rodriguez">Rodriguez</option>
                <option value="Paras">Paras</option>
                <option value="OKeefe">O'Keefe</option>
                <option value="Kwon">Kwon</option>
                <option value="Cannon">Cannon</option>
                <option value="Arnould">Arnould</option>
                <option value="Francazio">Francazio</option>
                <option value="Soto">Soto</option>      
            </select>
            <label id="pDx3Label" for="pDx3">Directing Attorney:</label>
            <input id="pDx3">
            <label id="pWDx3Label" for="pWDx3">Witness:</label>
            <input id="pWDx3">
            <label id="dCx3Label" for="dCx3">Crossing Attorney:</label>
            <input id="dCx3">

            <select id="wit4">
                <option value="Martini">Martini</option>
                <option value="Lewis">Lewis</option>
                <option value="Osborne">Osborne</option>
                <option value="Kwon">Kwon</option>
                <option value="Cannon">Cannon</option>
                <option value="Arnould">Arnould</option>
                <option value="Francazio">Francazio</option>
                <option value="Soto">Soto</option>       
            </select>
            <label id="dDx1Label" for="dDx1">Directing Attorney:</label>
            <input id="dDx1">
            <label id="dWDx1Label" for="dWDx1">Witness:</label>
            <input id="dWDx1">
            <label id="pCx1Label" for="pCx1">Crossing Attorney:</label>
            <input id="pCx1">

            <select id="wit5">
                <option value="Martini">Martini</option>
                <option value="Lewis">Lewis</option>
                <option value="Osborne">Osborne</option>
                <option value="Kwon">Kwon</option>
                <option value="Cannon">Cannon</option>
                <option value="Arnould">Arnould</option>
                <option value="Francazio">Francazio</option>
                <option value="Soto">Soto</option>      
            </select>
            <label id="dDx2Label" for="dDx2">Directing Attorney:</label>
            <input id="dDx2">
            <label id="dWDx2Label" for="dWDx2">Witness:</label>
            <input id="dWDx2">
            <label id="pCx2Label" for="pCx2">Crossing Attorney:</label>
            <input id="pCx2">

            <select id="wit6">
                <option value="Martini">Martini</option>
                <option value="Lewis">Lewis</option>
                <option value="Osborne">Osborne</option>
                <option value="Kwon">Kwon</option>
                <option value="Cannon">Cannon</option>
                <option value="Arnould">Arnould</option>
                <option value="Francazio">Francazio</option>
                <option value="Soto">Soto</option>     
            </select>
            <label id="dDx3Label" for="dDx3">Directing Attorney:</label>
            <input id="dDx3">
            <label id="dWDx3Label" for="dWDx3">Witness:</label>
            <input id="dWDx3">
            <label id="pCx3Label" for="pCx3">Crossing Attorney:</label>
            <input id="pCx3">
            
            <label id="pCloseLabel" for="pClose">Plaintiff Close:</label>
            <input id="pClose">
            <label id="dCloseLabel" for="dClose">Defense Close:</label>
            <input id="dClose">
        </div>
        <button id="submit">Submit</button>
        <script>const url = "<?php echo $url; ?>"</script>
        <script src="captains.js"></script>
    </body>
</html>
