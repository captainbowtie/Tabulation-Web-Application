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
//Web page if logged in as student
} else if (!$isTab && !$isJudge && !$isCoach) {
    echo "You do not have permission to access this page";
//Code if logged in as tabulation director or coach
} else if ($isTab || $isCoach) {
    //Retrieve the ballot
    $id = sanitize_string($_GET['id']);
    $ballotQuery = "SELECT * FROM ballots WHERE id=$id";
    $connection = new mysqli(dbhost, dbuser, dbpass, dbname);
    $ballotResult = $connection->query($ballotQuery);
    $connection->close();
    $ballotResult->data_seek(0);
    $ballot = $ballotResult->fetch_array(MYSQLI_ASSOC);
    //Set values from the ballot
    $pOpen = $ballot['pOpen'];
    $pDirect1 = $ballot['pDirect1'];
    $pWitDirect1 = $ballot['pWitDirect1'];
    $pWitCross1 = $ballot['pWitCross1'];
    $pDirect2 = $ballot['pDirect2'];
    $pWitDirect2 = $ballot['pWitDirect2'];
    $pWitCross2 = $ballot['pWitCross2'];
    $pDirect3 = $ballot['pDirect3'];
    $pWitDirect3 = $ballot['pWitDirect3'];
    $pWitCross3 = $ballot['pWitCross3'];
    $pCross1 = $ballot['pCross1'];
    $pCross2 = $ballot['pCross2'];
    $pCross3 = $ballot['pCross3'];
    $pClose = $ballot['pClose'];
    $dOpen = $ballot['dOpen'];
    $dDirect1 = $ballot['dDirect1'];
    $dWitDirect1 = $ballot['dWitDirect1'];
    $dWitCross1 = $ballot['dWitCross1'];
    $dDirect2 = $ballot['dDirect2'];
    $dWitDirect2 = $ballot['dWitDirect2'];
    $dWitCross2 = $ballot['dWitCross2'];
    $dDirect3 = $ballot['dDirect3'];
    $dWitDirect3 = $ballot['dWitDirect3'];
    $dWitCross3 = $ballot['dWitCross3'];
    $dCross1 = $ballot['dCross1'];
    $dCross2 = $ballot['dCross2'];
    $dCross3 = $ballot['dCross3'];
    $dClose = $ballot['dClose'];
    //Create json object of the ballot data
    $arr = array('pOpen' => $ballot['pOpen'],
        'pDirect1' => $ballot['pDirect1'],
        'pWitDirect1' => $ballot['pWitDirect1'],
        'pWitCross1' => $ballot['pWitCross1'],
        'pDirect2' => $ballot['pDirect2'],
        'pWitDirect2' => $ballot['pWitDirect2'],
        'pWitCross2' => $ballot['pWitCross2'],
        'pDirect3' => $ballot['pDirect3'],
        'pWitDirect3' => $ballot['pWitDirect3'],
        'pWitCross3' => $ballot['pWitCross3'],
        'pCross1' => $ballot['pCross1'],
        'pCross2' => $ballot['pCross2'],
        'pCross3' => $ballot['pCross3'],
        'pClose' => $ballot['pClose'],
        'dOpen' => $ballot['dOpen'],
        'dDirect1' => $ballot['dDirect1'],
        'dWitDirect1' => $ballot['dWitDirect1'],
        'dWitCross1' => $ballot['dWitCross1'],
        'dDirect2' => $ballot['dDirect2'],
        'dWitDirect2' => $ballot['dWitDirect2'],
        'dWitCross2' => $ballot['dWitCross2'],
        'dDirect3' => $ballot['dDirect3'],
        'dWitDirect3' => $ballot['dWitDirect3'],
        'dWitCross3' => $ballot['dWitCross3'],
        'dCross1' => $ballot['dCross1'],
        'dCross2' => $ballot['dCross2'],
        'dCross3' => $ballot['dCross3'],
        'dClose' => $ballot['dClose']
        );

echo json_encode($arr);
    
//More limited code if a judge (so they can only see their ballot)    
} else if ($isJudge) {
    
}