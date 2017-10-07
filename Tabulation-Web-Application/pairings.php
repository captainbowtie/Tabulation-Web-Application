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
    $file = "tab.json";
    $json = json_decode(file_get_contents($file), true);
    $currentRound = $json["currentRound"];
    switch ($currentRound) {
        case 0:
            //Create selector for round 1 pairing method
            echo "How would you like to pair round 1?\n";
            echo "<select id='pairingMethodSelect'>\n";
            echo "<option id='random'>Randomized by Server</option>\n";
            echo "<option id='challenge'>Challenge Draft</option>\n"; 
            echo "</select><br>\n";
            //Create pairing selects
            $teamNumbers = getAllTeamNumbers();
            $optionHTML = "<option name='0'>N/A</option>\n";
            for ($a = 0; $a < sizeOf($teamNumbers); $a++) {
                $teamNumber = $teamNumbers[$a];
                $optionHTML .= "<option id='$teamNumber' value='$teamNumber'>$teamNumber</option>\n";
            }
            for ($a = 0; $a < sizeOf($teamNumbers) / 2; $a++) {
                echo "<select id='p$a' class='pairingSelect'>\n";
                echo $optionHTML;
                echo "</select>\n";
                echo "vs.";
                echo "<select id='d$a' class='pairingSelect'>\n";
                echo $optionHTML;
                echo "</select><br>\n";
            }
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
    echo "<input type='submit' id='sumbitPairingsButton' value='Pair Round ".($currentRound+1)."'>";
}


echo "<script src='https://code.jquery.com/jquery-3.2.1.min.js'></script>";
echo "<script src='/pairings.js'></script>";
echo "</body>";
echo "</html>";

