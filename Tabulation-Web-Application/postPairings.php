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

if (!$isTab) {
    //TODO: web page if not the tabulation director
} else {
    $teamList = getAllTeamNumbers(); //List of all team numbers,which is emptied as pairings are validated
    $pairingValidation = array();
    $allPairingsValid = TRUE;
    $connection = new mysqli(dbhost, dbuser, dbpass, dbname);
    if ($connection->connect_error) {
        die($connection->connect_error);
    }
    for ($a = 0; $a < sizeOf(getAllTeamNumbers()) / 2; $a++) {
        $pTeam = sanitize_string($_POST["p$a"]);
        $dTeam = sanitize_string($_POST["d$a"]);
        $conflictQuery = "SELECT id FROM teamConflicts WHERE "
                . "(team1=$pTeam && team2=$dTeam) || "
                . "(team1=$dTeam && team2=$pTeam)";
        $conflictResult = $connection->query($conflictQuery);

        //Check if there is an impermissible match
        if ($conflictResult->num_rows > 0) {
            $pairingValidation[$a] = 1;
            $pairingValidation["errorMessage"] = "At least one pairing contains an impermissible match";
            $allPairingsValid = FALSE;
        } else {
            $pairingValidation[$a] = 0;
        }

        //Check if a team is facing itself
        if ($pTeam == $dTeam) {
            $pairingValidation[$a] = 1;
            $pairingValidation["errorMessage"] = "At least one team is facing itself";
            $allPairingsValid = FALSE;
        }

        //Remove pTeam and dTeam from running team list
        $teamList = array_diff($teamList, array($pTeam, $dTeam));
    }

    //Check if team list is empty; if not, then a team has not been paired
    if (sizeOf($teamList) != 0) {
        $allPairingsValid = FALSE;
        $pairingValidation["errorMessage"] = "At least one team does not have a pairing";
    }

    if ($allPairingsValid) {
        //Increment the current round by 1
        $file = "tab.json";
        $json = json_decode(file_get_contents($file), true);
        $currentRound = $json["currentRound"] + 1;
        $json["currentRound"] = $currentRound;
        file_put_contents($file, json_encode($json));
        //Write pairings to database
        for ($a = 0; $a < sizeOf(getAllTeamNumbers()) / 2; $a++) {
            $pTeam = sanitize_string($_POST["p$a"]);
            $dTeam = sanitize_string($_POST["d$a"]);
            $ballotQuery = "INSERT INTO ballots (round,pTeam,dTeam) "
                    . "VALUES($currentRound,$pTeam,$dTeam)";
            $conflictQuery = "INSERT INTO teamConflicts (team1,team2) "
                    . "VALUES($pTeam,$dTeam)";
        }
        
    } else {
        //Inform user of the error
        $connection->close();
        header('Content-Type: application/json');
        $pairingValidation["exitCode"] = 1;
        echo json_encode($pairingValidation);
    }
}