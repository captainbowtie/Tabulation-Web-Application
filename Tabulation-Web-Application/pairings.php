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
    for ($a = 0; $a < $numberOfTeams; $a++) {
        $rand = rand(0, sizeOf($teamNumbers) - 1);
        $team = $teamNumbers[$rand];
        unset($teamNumbers[$rand]);
        $teamNumbers = array_values($teamNumbers);
        $pairings[$a] = $team;
    }
    $pairings = resolveImpermissibleMatches($pairings);
}

function resolveImpermissibleMatches($pairings) {
    //Create swap list
    $connection = new mysqli(dbhost, dbuser, dbpass, dbname);
    $connection->query("CREATE TABLE swapList (id SMALLINT AUTO_INCREMENT KEY, "
            . "team1 SMALLINT UNSIGNED, team2 SMALLINT UNSIGNED, "
            . "INDEX(team1), INDEX(team2)) ENGINE InnoDB");
    for ($a = 0; $a * 2 < sizeOf($pairings); $a++) {
        //Check to see if there is a conflict for pairing number $a
        $pTeam = $pairings[$a * 2];
        $dTeam = $pairings[$a * 2 + 1];
        $conflictQuery = "SELECT team1,team2 FROM teamConflicts "
                . "WHERE (team1=$pTeam && team2=$dTeam) || "
                . "(team1=$dTeam && team2=$pTeam)";
        $conflictResult = $connection->query($conflictQuery);
        if ($conflictResult->num_rows > 0) {
            //If there is a conflict, create an array of all possible swaps
            $potentialSwaps = array();
            for ($b = 0; $b < sizeOf($pairings); $b++) {

                //Only add the swap to the array if it wouldn't be a swap of the teams in conflict
                if (!($b == $a * 2) && !($b == $a * 2 + 1)) {
                    $potentialSwapTeamNumber = $pairings[$b];

                    //Code for adding plaintiff swap
                    //Check that the swap is not on the swap list
                    $pSwapQuery = "SELECT id FROM swapList "
                            . "WHERE (team1=$pTeam && team2=$potentialSwapTeamNumber) || "
                            . "(team1=$potentialSwapTeamNumber && team2=$pTeam)";
                    $pSwapResult = $connection->query($pSwapQuery);
                    if ($pSwapResult->num_rows == 0) {
                        $potentialSwaps[] = new swap($pTeam, $potentialSwapTeamNumber, $a * 2, $b);
                    }

                    //Code for adding defense swap
                    $dSwapQuery = "SELECT id FROM swapList "
                            . "WHERE (team1=$dTeam && team2=$potentialSwapTeamNumber) || "
                            . "(team1=$potentialSwapTeamNumber && team2=$dTeam)";
                    $dSwapResult = $connection->query($dSwapQuery);
                    if ($dSwapResult->num_rows == 0) {
                        $potentialSwaps[] = new swap($dTeam, $potentialSwapTeamNumber, $a * 2 + 1, $b);
                    }
                }

                //Check if there is only one permissible swap
                if (sizeOf($potentialSwaps) == 1) {
                    //Do the swap
                    $conflictTeam = $potentialSwaps[0]->conflictTeam;
                    $swapTeam = $potentialSwaps[0]->swapTeam;
                    $pairings[$potentialSwaps[0]->conflictTeamRank] = $swapTeam;
                    $pairings[$potentialSwaps[0]->swapTeamRank] = $conflictTeam;
                    $swapQuery = "INSERT INTO swapList (team1,team2) VALUES($swapTeam,$conflictTeam)";
                    $a = -1;
                } else { //Find the teams with the least rank difference
                    $minRankDifference = 9999; //Sets the smallest distance to an absurdly high value
                    for ($b = 0; $b < sizeOf($potentialSwaps); $b++) { //Loop through all swaps
                        $rankDifference = $potentialSwaps[$b]->getRankDifference();
                        if ($rankDifference < $minRankDifference) {  //Check if swap in question has a smaller rank difference
                            $minRankDifference = $rankDifference; //If so, sets the minDistance to that distance and restarts the loop
                            $b = 0;
                        } else if ($rankDifference > $minRankDifference) { //If a swap has a higher rank distance, then remove that swap from the list of possibilities
                            unset($potentialSwaps[$b]);
                            $potentialSwaps = array_values($potentialSwaps);
                        }
                    }

                    //After finding smallest rank distance, see if there is only one swap remaining
                    if (sizeOf($potentialSwaps) == 1) {
                        //Do the swap
                        $conflictTeam = $potentialSwaps[0]->conflictTeam;
                        $swapTeam = $potentialSwaps[0]->swapTeam;
                        $pairings[$potentialSwaps[0]->conflictTeamRank] = $swapTeam;
                        $pairings[$potentialSwaps[0]->swapTeamRank] = $conflictTeam;
                        $swapQuery = "INSERT INTO swapList (team1,team2) VALUES($swapTeam,$conflictTeam)";
                        $a = -1;
                    } else { //Find the teams with the least record difference
                        $minRecordDifference = 9999;
                        for ($b = 0; $b < sizeOf($potentialSwaps); $b++) { //Loop through all swaps
                            $recordDifference = $potentialSwaps[$b]->getRecordDifference();
                            if ($recordDifference < $minRecordDifference) {  //Check if swap in question has a smaller record difference
                                $minRecordDifference = $recordDifference; //If so, sets the minDistance to that distance and restarts the loop
                                $b = 0;
                            } else if ($recordDifference > $minRecordDifference) { //If a swap has a higher record distance, then remove that swap from the list of possibilities
                                unset($potentialSwaps[$b]);
                                $potentialSwaps = array_values($potentialSwaps);
                            }
                        }

                        //After finding smallest record difference, see if there is only one swap remaining
                        if (sizeOf($potentialSwaps) == 1) {
                            //Do the swap
                            $conflictTeam = $potentialSwaps[0]->conflictTeam;
                            $swapTeam = $potentialSwaps[0]->swapTeam;
                            $pairings[$potentialSwaps[0]->conflictTeamRank] = $swapTeam;
                            $pairings[$potentialSwaps[0]->swapTeamRank] = $conflictTeam;
                            $swapQuery = "INSERT INTO swapList (team1,team2) VALUES($swapTeam,$conflictTeam)";
                            $a = -1;
                        } else { //Find the swap with the least CS difference
                            $minCSDifference = 9999;
                            for ($b = 0; $b < sizeOf($potentialSwaps); $b++) { //Loop through all swaps
                                $CSDifference = $potentialSwaps[$b]->getCSDifference();
                                if ($CSDifference < $minCSDifference) {  //Check if swap in question has a smaller CS difference
                                    $minCSDifference = $CSDifference; //If so, sets the minDistance to that distance and restarts the loop
                                    $b = 0;
                                } else if ($CSDifference > $minCSDifference) { //If a swap has a higher CS distance, then remove that swap from the list of possibilities
                                    unset($potentialSwaps[$b]);
                                    $potentialSwaps = array_values($potentialSwaps);
                                }
                            }

                            //After finding smallest CS difference, see if there is only one swap remaining
                            if (sizeOf($potentialSwaps) == 1) {
                                //Do the swap
                                $conflictTeam = $potentialSwaps[0]->conflictTeam;
                                $swapTeam = $potentialSwaps[0]->swapTeam;
                                $pairings[$potentialSwaps[0]->conflictTeamRank] = $swapTeam;
                                $pairings[$potentialSwaps[0]->swapTeamRank] = $conflictTeam;
                                $swapQuery = "INSERT INTO swapList (team1,team2) VALUES($swapTeam,$conflictTeam)";
                                $a = -1;
                            } else {//Find the teams with the least PD difference
                                $minPDDifference = 9999;
                                for ($b = 0; $b < sizeOf($potentialSwaps); $b++) { //Loop through all swaps
                                    $PDDifference = $potentialSwaps[$b]->getPDDifference();
                                    if ($PDDifference < $minPDDifference) {  //Check if swap in question has a smaller PD difference
                                        $minPDDifference = $PDDifference; //If so, sets the minDistance to that distance and restarts the loop
                                        $b = 0;
                                    } else if ($PDDifference > $minPDDifference) { //If a swap has a higher PD distance, then remove that swap from the list of possibilities
                                        unset($potentialSwaps[$b]);
                                        $potentialSwaps = array_values($potentialSwaps);
                                    }
                                }

                                //After finding smallest PD difference, see if there is only one swap remaining
                                if (sizeOf($potentialSwaps) == 1) {
                                    //Do the swap
                                    $conflictTeam = $potentialSwaps[0]->conflictTeam;
                                    $swapTeam = $potentialSwaps[0]->swapTeam;
                                    $pairings[$potentialSwaps[0]->conflictTeamRank] = $swapTeam;
                                    $pairings[$potentialSwaps[0]->swapTeamRank] = $conflictTeam;
                                    $swapQuery = "INSERT INTO swapList (team1,team2) VALUES($swapTeam,$conflictTeam)";
                                    $a = -1;
                                } else {//Find swap with highest rank sum
                                    $maxRankSum = -9999;
                                    for ($b = 0; $b < sizeOf($potentialSwaps); $b++) { //Loop through all swaps
                                        $rankSum = $potentialSwaps[$b]->getRankSum();
                                        if ($rankSum > $maxRankSum) {
                                            $maxRankSum = $rankSum;
                                            $b = 0;
                                        } else if ($rankSum < $maxRankSum) {
                                            unset($potentialSwaps[$b]);
                                            $potentialSwaps = array_values($potentialSwaps);
                                        }
                                    }
                                    //Do the swap
                                    $conflictTeam = $potentialSwaps[0]->conflictTeam;
                                    $swapTeam = $potentialSwaps[0]->swapTeam;
                                    $pairings[$potentialSwaps[0]->conflictTeamRank] = $swapTeam;
                                    $pairings[$potentialSwaps[0]->swapTeamRank] = $conflictTeam;
                                    $swapQuery = "INSERT INTO swapList (team1,team2) VALUES($swapTeam,$conflictTeam)";
                                    $a = -1;
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    //Drop swap list
    $connection->query("DROP TABLE swapList");
    return $pairings;
}

function resolveImpermissibleSideConstrainedMatches($pTeams, $dTeams) {
    //Create swap list
    $connection = new mysqli(dbhost, dbuser, dbpass, dbname);
    $connection->query("CREATE TABLE swapList (id SMALLINT AUTO_INCREMENT KEY, "
            . "team1 SMALLINT UNSIGNED, team2 SMALLINT UNSIGNED, "
            . "INDEX(team1), INDEX(team2)) ENGINE InnoDB");

    //Loop through pairings looking for conflicts and resolve them
    for ($a = 0; $a < sizeOf($pTeams); $a++) {
        $pTeam = $pTeams[$a];
        $dTeam = $dTeams[$a];
        $conflictQuery = "SELECT team1,team2 FROM teamConflicts "
                . "WHERE (team1=$pTeam && team2=$dTeam) || "
                . "(team1=$dTeam && team2=$pTeam)";
        $conflictResult = $connection->query($conflictQuery);
        //Check if there is a conflict
        if ($conflictResult->num_rows > 0) {
            //If there is a conflict, create an array of all possible swaps
            $potentialSwaps = array();
            //Loop through all teams to create list of all possible swaps
            for ($b = 0; $b < sizeOf($pTeams); $b++) {
                //Check to ensure that the swap in question is not the conflict in question
                if(!($b==$a)){
                    //Check to ensure that plaintiff Swap in question has not already happened
                    $pSwapTeam = $pTeams[$b];
                    $pSwapQuery = "SELECT id FROM swapList "
                            . "WHERE (team1=$pTeam && team2=$pSwapTeam) || "
                            . "(team1=$pSwapTeam && team2=$pTeam)";
                    $pSwapResult = $connection->query($pSwapQuery);
                    if ($pSwapResult->num_rows == 0) {
                        $potentialSwaps[] = new swap($pTeam, $pSwapTeam, $a, $b);
                    }
                    
                    //Check to ensure that defense Swap in question has not already happened
                    $dSwapTeam = $dTeams[$b];
                    $dSwapQuery = "SELECT id FROM swapList "
                            . "WHERE (team1=$dTeam && team2=$dSwapTeam) || "
                            . "(team1=$dSwapTeam && team2=$dTeam)";
                    $dSwapResult = $connection->query($dSwapQuery);
                    if ($dSwapResult->num_rows == 0) {
                        $potentialSwaps[] = new swap($dTeam, $dSwapTeam, $a, $b);
                    }
                }
            }
            
            //Check if there is only one permissible swap
                if (sizeOf($potentialSwaps) == 1) {
                    //TODO: Do the swap
                    
                    $a = -1;
                } else { //Find the teams with the least rank difference
                    $minRankDifference = 9999; //Sets the smallest distance to an absurdly high value
                    for ($b = 0; $b < sizeOf($potentialSwaps); $b++) { //Loop through all swaps
                        $rankDifference = $potentialSwaps[$b]->getRankDifference();
                        if ($rankDifference < $minRankDifference) {  //Check if swap in question has a smaller rank difference
                            $minRankDifference = $rankDifference; //If so, sets the minDistance to that distance and restarts the loop
                            $b = 0;
                        } else if ($rankDifference > $minRankDifference) { //If a swap has a higher rank distance, then remove that swap from the list of possibilities
                            unset($potentialSwaps[$b]);
                            $potentialSwaps = array_values($potentialSwaps);
                        }
                    }

                    //After finding smallest rank distance, see if there is only one swap remaining
                    if (sizeOf($potentialSwaps) == 1) {
                        //TODO: Do the swap
                        
                    } else { //Find the teams with the least record difference
                        $minRecordDifference = 9999;
                        for ($b = 0; $b < sizeOf($potentialSwaps); $b++) { //Loop through all swaps
                            $recordDifference = $potentialSwaps[$b]->getRecordDifference();
                            if ($recordDifference < $minRecordDifference) {  //Check if swap in question has a smaller record difference
                                $minRecordDifference = $recordDifference; //If so, sets the minDistance to that distance and restarts the loop
                                $b = 0;
                            } else if ($recordDifference > $minRecordDifference) { //If a swap has a higher record distance, then remove that swap from the list of possibilities
                                unset($potentialSwaps[$b]);
                                $potentialSwaps = array_values($potentialSwaps);
                            }
                        }

                        //After finding smallest record difference, see if there is only one swap remaining
                        if (sizeOf($potentialSwaps) == 1) {
                            //TODO: Do the swap
                            
                        } else { //Find the swap with the least CS difference
                            $minCSDifference = 9999;
                            for ($b = 0; $b < sizeOf($potentialSwaps); $b++) { //Loop through all swaps
                                $CSDifference = $potentialSwaps[$b]->getCSDifference();
                                if ($CSDifference < $minCSDifference) {  //Check if swap in question has a smaller CS difference
                                    $minCSDifference = $CSDifference; //If so, sets the minDistance to that distance and restarts the loop
                                    $b = 0;
                                } else if ($CSDifference > $minCSDifference) { //If a swap has a higher CS distance, then remove that swap from the list of possibilities
                                    unset($potentialSwaps[$b]);
                                    $potentialSwaps = array_values($potentialSwaps);
                                }
                            }

                            //After finding smallest CS difference, see if there is only one swap remaining
                            if (sizeOf($potentialSwaps) == 1) {
                                //TODO: Do the swap
                                
                            } else {//Find the teams with the least PD difference
                                $minPDDifference = 9999;
                                for ($b = 0; $b < sizeOf($potentialSwaps); $b++) { //Loop through all swaps
                                    $PDDifference = $potentialSwaps[$b]->getPDDifference();
                                    if ($PDDifference < $minPDDifference) {  //Check if swap in question has a smaller PD difference
                                        $minPDDifference = $PDDifference; //If so, sets the minDistance to that distance and restarts the loop
                                        $b = 0;
                                    } else if ($PDDifference > $minPDDifference) { //If a swap has a higher PD distance, then remove that swap from the list of possibilities
                                        unset($potentialSwaps[$b]);
                                        $potentialSwaps = array_values($potentialSwaps);
                                    }
                                }

                                //After finding smallest PD difference, see if there is only one swap remaining
                                if (sizeOf($potentialSwaps) == 1) {
                                    //TODO: Do the swap
                                    
                                } else {//Find swap with highest rank sum
                                    $maxRankSum = -9999;
                                    for ($b = 0; $b < sizeOf($potentialSwaps); $b++) { //Loop through all swaps
                                        $rankSum = $potentialSwaps[$b]->getRankSum();
                                        if ($rankSum > $maxRankSum) {
                                            $maxRankSum = $rankSum;
                                            $b = 0;
                                        } else if ($rankSum < $maxRankSum) {
                                            unset($potentialSwaps[$b]);
                                            $potentialSwaps = array_values($potentialSwaps);
                                        }
                                    }
                                    
                                    //After finding greatest rank sum, see if there is only one swap remaining
                                    if(sizeOf($potentialSwaps) == 1){
                                        //TODO: Do the swap
                                        
                                    }else{
                                        //Do the swap on the defense side
                                        //TODO: Do the swap
                                    }
                                    
                                }
                            }
                        }
                    }
                }
            
        }
    }
}
