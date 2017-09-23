<?php

/*
  Copyright (C) 2017 allen

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
 */

session_start();

require_once "dblogin.php";
require_once "setSessionPrivileges.php";
require_once "functions.php";

echo<<<_END
<!DOCTYPE HTML>
<html>
        <head>
            <title>Round Pairings</title>
<script src='https://code.jquery.com/jquery-3.2.1.min.js'></script>
                </head>
        <body>
_END;

require_once "header.php";

if (!$isTab) {
    //TODO: web page if not the tabulation director
} else {
    $fh = fopen("currentRound.txt", 'r') or
            die("File does not exist or you lack permission to open it");
    $currentRound = fgets($fh);
    fclose($fh);
    switch ($currentRound) {
        case 0:
            //Create selector for round 1 pairing method
            echo "How would you like to pair round 1?\n";
            echo "<select id='pairingMethodSelect'>\n";
            echo "<option id='hand'>Manual Entry</option>\n";
            echo "<option id='challenge'>Challenge Draft</option>\n";
            echo "<option id='random'>Randomized by Server</option>\n";
            echo "</select><br>\n";
            //Create pairing selects
            $teamNumbers = getAllTeamNumbers();
            $optionHTML = "<option name='0'>N/A</option>\n";
            for ($a = 0; $a < sizeOf($teamNumbers); $a++) {
                $teamNumber = $teamNumbers[$a];
                $optionHTML .= "<option id='$teamNumber'>$teamNumber</option>\n";
            }
            for ($a = 0; $a < sizeOf($teamNumbers) / 2; $a++) {
                echo "<select id='plaintiff$a' class='pairingSelect'>\n";
                echo $optionHTML;
                echo "</select>\n";
                echo "vs.";
                echo "<select id='defense$a' class='pairingSelect'>\n";
                echo $optionHTML;
                echo "</select><br>\n";
            }
            //code for challenge draft pairing
            echo "<div id='challengePairing'>";
            echo "</div>";
            //code for random draft pairing
            echo "<div id='randomPairing'>";
            echo "</div>";
            //code for hand pairing
            echo "<div id='handPairing'>";
            echo "</div>";
            break;
        case 1:
            break;
        case 2:
            break;
        case 3:
            break;
        case 4:
            break;
    }
    generateRandomPairings();
    echo "<input type='submit' value='Pair Round 1'>";
}


echo "<script src='https://code.jquery.com/jquery-3.2.1.min.js'></script>";
echo "<script src='/pairings.js'></script>";
echo "</body>";
echo "</html>";

function generateRandomPairings() {
    $teamNumbers = getAllTeamNumbers();
    $pairings = array();
    $numberOfTeams = sizeOf($teamNumbers);
    for ($a = 0; $a < $numberOfTeams/2; $a++) {
        $pRand = rand(0, sizeOf($teamNumbers) - 1);
        $pTeam = $teamNumbers[$pRand];
        unset($teamNumbers[$pRand]);
        $teamNumbers = array_values($teamNumbers);
        $dRand = rand(0, sizeOf($teamNumbers) - 1);
        $dTeam = $teamNumbers[$dRand];
        unset($teamNumbers[$dRand]);
        $teamNumbers = array_values($teamNumbers);
        $pairings[$a] = array($pTeam,$dTeam);
    }
    $pairings = resolveImpermissibleMatches($pairings);
}

function resolveImpermissibleMatches($pairings) {
    //Create swap list
    $connection = new mysqli(dbhost, dbuser, dbpass, dbname);
    $connection->query("CREATE TABLE swaplist (id SMALLINT AUTO_INCREMENT KEY, "
            . "team1 SMALLINT UNSIGNED, team2 SMALLINT UNSIGNED, "
            . "INDEX(team1), INDEX(team2)) ENGINE InnoDB");
    for ($a = 0; $a < sizeOf($pairings); $a++) {
        $pTeam = $pairings[$a][0];
        $dTeam = $pairings[$a][1];
        $conflictQuery = "SELECT team1,team2 FROM teamConflicts "
                . "WHERE (team1=$pTeam && team2=$dTeam) || "
                . "(team1=$dTeam && team2=$pTeam)";
        $conflictResult = $connection->query($conflictQuery);
        if($conflictResult->num_rows>0){
            
        }
    }

    //Drop swap list
    $connection->query("DROP TABLE swaplist");
    return $pairings;
}
