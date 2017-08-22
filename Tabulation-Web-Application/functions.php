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

require_once 'dblogin.php';
$connection = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

function sanitize_string($string)
  {
    if (get_magic_quotes_gpc()) {
        $string = stripslashes($string);
    }
    return htmlentities ($string);
  }
  
function getBallotPD($ballotId, $teamNumber){
    
    global $connection;
    $query = "SELECT * FROM ballots WHERE id='".$ballotId."'";
    $result = $connection->query($query);
    $ballot = $result->fetch_array(MYSQLI_ASSOC);
    $result->close();
    $pPoints = $ballot['pOpen']+$ballot['pDirect1']+$ballot['pWitDirect1']+
            $ballot['pWitCross1']+$ballot['pDirect2']+$ballot['pWitDirect2']+
            $ballot['pWitCross2']+$ballot['pDirect3']+$ballot['pWitDirect3']+
            $ballot['pWitCross3']+$ballot['pCross1']+$ballot['pCross2']+
            $ballot['pCross3']+$ballot['pClose'];
    $dPoints = $ballot['dOpen']+$ballot['dDirect1']+$ballot['dWitDirect1']+
            $ballot['dWitCross1']+$ballot['dDirect2']+$ballot['dWitDirect2']+
            $ballot['dWitCross2']+$ballot['dDirect3']+$ballot['dWitDirect3']+
            $ballot['dWitCross3']+$ballot['dCross1']+$ballot['dCross2']+
            $ballot['dCross3']+$ballot['dClose'];
    $plaintiffPD = $pPoints - $dPoints;
    if($teamNumber==$ballot['pTeamNumber']){
        return $plaintiffPD;
    }else{
        return -1 * $plaintiffPD;
    }
}
