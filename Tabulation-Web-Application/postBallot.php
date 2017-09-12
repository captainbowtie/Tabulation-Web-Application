<?php

/*
 * Copyright (C) 2017 allen
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

require_once "dblogin.php";
require_once "setSessionPrivileges.php";
require_once "functions.php";


if (!isset($_SESSION['id'])) {
    echo "You must be logged in to view this page";
//Web page if logged in, but not as a judge or tabulation director
} else if (!$isTab && !$isJudge) {
    echo "You do not have permission to access this page";
//Code if logged in as tabulation director
} else {
    //TODO: various validation checks as well as heightened permission check
    $id = sanitize_string($_POST['id']);
    $connection = new mysqli(dbhost, dbuser, dbpass, dbname);
    if (isset($_POST['finalized'])) {
        $value = sanitize_string($_POST['pOpen']);
        $query = "UPDATE ballots SET pOpen='$value' WHERE id=$id";      
        $connection->query($query);
        $value = sanitize_string($_POST['pDirect1']);
        $query = "UPDATE ballots SET pDirect1='$value' WHERE id=$id";      
        $connection->query($query);
        $value = sanitize_string($_POST['pWitDirect1']);
        $query = "UPDATE ballots SET pWitDirect1='$value' WHERE id=$id";      
        $connection->query($query);
        $value = sanitize_string($_POST['pWitCross1']);
        $query = "UPDATE ballots SET pWitCross1='$value' WHERE id=$id";      
        $connection->query($query);
        $value = sanitize_string($_POST['pDirect2']);
        $query = "UPDATE ballots SET pDirect2='$value' WHERE id=$id";      
        $connection->query($query);
        $value = sanitize_string($_POST['pWitDirect2']);
        $query = "UPDATE ballots SET pWitDirect2='$value' WHERE id=$id";      
        $connection->query($query);
        $value = sanitize_string($_POST['pWitCross2']);
        $query = "UPDATE ballots SET pWitCross2='$value' WHERE id=$id";      
        $connection->query($query);
        $value = sanitize_string($_POST['pDirect3']);
        $query = "UPDATE ballots SET pDirect3='$value' WHERE id=$id";      
        $connection->query($query);
        $value = sanitize_string($_POST['pWitDirect3']);
        $query = "UPDATE ballots SET pWitDirec31='$value' WHERE id=$id";      
        $connection->query($query);
        $value = sanitize_string($_POST['pWitCross3']);
        $query = "UPDATE ballots SET pWitCross3='$value' WHERE id=$id";      
        $connection->query($query);
        $value = sanitize_string($_POST['pCross1']);
        $query = "UPDATE ballots SET pCross1='$value' WHERE id=$id";      
        $connection->query($query);
        $value = sanitize_string($_POST['pCross2']);
        $query = "UPDATE ballots SET pCross2='$value' WHERE id=$id";      
        $connection->query($query);
        $value = sanitize_string($_POST['pCross3']);
        $query = "UPDATE ballots SET pCross3='$value' WHERE id=$id";      
        $connection->query($query);
        $value = sanitize_string($_POST['pClose']);
        $query = "UPDATE ballots SET pClose='$value' WHERE id=$id";      
        $connection->query($query);
        $value = sanitize_string($_POST['dOpen']);
        $query = "UPDATE ballots SET dOpen='$value' WHERE id=$id";      
        $connection->query($query);
        $value = sanitize_string($_POST['dDirect1']);
        $query = "UPDATE ballots SET dDirect1='$value' WHERE id=$id";      
        $connection->query($query);
        $value = sanitize_string($_POST['dWitDirect1']);
        $query = "UPDATE ballots SET dWitDirect1='$value' WHERE id=$id";      
        $connection->query($query);
        $value = sanitize_string($_POST['dWitCross1']);
        $query = "UPDATE ballots SET dWitCross1='$value' WHERE id=$id";      
        $connection->query($query);
        $value = sanitize_string($_POST['dDirect2']);
        $query = "UPDATE ballots SET dDirect2='$value' WHERE id=$id";      
        $connection->query($query);
        $value = sanitize_string($_POST['dWitDirect2']);
        $query = "UPDATE ballots SET dWitDirect2='$value' WHERE id=$id";      
        $connection->query($query);
        $value = sanitize_string($_POST['dWitCross2']);
        $query = "UPDATE ballots SET dWitCross2='$value' WHERE id=$id";      
        $connection->query($query);
        $value = sanitize_string($_POST['dDirect3']);
        $query = "UPDATE ballots SET dDirect3='$value' WHERE id=$id";      
        $connection->query($query);
        $value = sanitize_string($_POST['dWitDirect3']);
        $query = "UPDATE ballots SET dWitDirec31='$value' WHERE id=$id";      
        $connection->query($query);
        $value = sanitize_string($_POST['dWitCross3']);
        $query = "UPDATE ballots SET dWitCross3='$value' WHERE id=$id";      
        $connection->query($query);
        $value = sanitize_string($_POST['dCross1']);
        $query = "UPDATE ballots SET dCross1='$value' WHERE id=$id";      
        $connection->query($query);
        $value = sanitize_string($_POST['dCross2']);
        $query = "UPDATE ballots SET dCross2='$value' WHERE id=$id";      
        $connection->query($query);
        $value = sanitize_string($_POST['dCross3']);
        $query = "UPDATE ballots SET dCross3='$value' WHERE id=$id";      
        $connection->query($query);
        $value = sanitize_string($_POST['dClose']);
        $query = "UPDATE ballots SET dClose='$value' WHERE id=$id";      
        $connection->query($query);
        $value = sanitize_string($_POST['attyRank1']);
        $query = "UPDATE ballots SET attyRank1='$value' WHERE id=$id";      
        $connection->query($query);
        $value = sanitize_string($_POST['attyRank2']);
        $query = "UPDATE ballots SET attyRank2='$value' WHERE id=$id";      
        $connection->query($query);
        $value = sanitize_string($_POST['attyRank3']);
        $query = "UPDATE ballots SET attyRank3='$value' WHERE id=$id";      
        $connection->query($query);
        $value = sanitize_string($_POST['attyRank4']);
        $query = "UPDATE ballots SET attyRank4='$value' WHERE id=$id";      
        $connection->query($query);
        $value = sanitize_string($_POST['attyRank5']);
        $query = "UPDATE ballots SET attyRank5='$value' WHERE id=$id";      
        $connection->query($query);
        $value = sanitize_string($_POST['attyRank6']);
        $query = "UPDATE ballots SET attyRank6='$value' WHERE id=$id";      
        $connection->query($query);
        $value = sanitize_string($_POST['witRank1']);
        $query = "UPDATE ballots SET witRank1='$value' WHERE id=$id";      
        $connection->query($query);
        $value = sanitize_string($_POST['witRank2']);
        $query = "UPDATE ballots SET witRank2='$value' WHERE id=$id";      
        $connection->query($query);
        $value = sanitize_string($_POST['witRank3']);
        $query = "UPDATE ballots SET witRank3='$value' WHERE id=$id";      
        $connection->query($query);
        $value = sanitize_string($_POST['witRank4']);
        $query = "UPDATE ballots SET witRank4='$value' WWHERE id=$id";      
        $connection->query($query);
        $value = sanitize_string($_POST['witRank5']);
        $query = "UPDATE ballots SET witRank5='$value' WHERE id=$id";      
        $connection->query($query);
        $value = sanitize_string($_POST['witRank6']);
        $query = "UPDATE ballots SET witRank6='$value' WHERE id=$id";      
        $connection->query($query);
        $query = "UPDATE ballots SET finalized=1 WHERE id=$id";
        $connection->query($query);
        $connection->close();
        echo "0";
    } else {
        $field = sanitize_string($_POST['field']);
        $value = sanitize_string($_POST['value']);
        $query = "UPDATE ballots SET $field='$value' WHERE id=$id";      
        $connection->query($query);
        $connection->close();
        echo "0";
    }
}