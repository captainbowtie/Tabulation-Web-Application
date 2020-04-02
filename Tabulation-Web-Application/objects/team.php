<?php

/*
 * Copyright (C) 2019 allen
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

require_once __DIR__ . "/../config.php";
require_once SITE_ROOT . "/database.php";

class Team {

    public $number;
    public $name;

    public function __construct($number, $name) {
        $this->number = $number;
        $this->name = $name;
    }

    public function getOpponents() {
        $number = $this->number;
        //connect to database, draft query
        $db = new Database();
        $conn = $db->getConnection();
        $query = "SELECT * FROM pairings WHERE plaintiff = " . $number . " || defense = " . $number . " ORDER BY round";

        //get results
        if ($result = $conn->query($query)) {
            $i = 0;
            global $opponents;
            while ($row = $result->fetch_row()) {
                //check if team in quesstion is plaintiff or defense, and pull the other one
                if ($row[1] == $team) {
                    $opponents[$i] = $row[2];
                } else {
                    $opponents[$i] = $row[1];
                }
                $i++;
            }
            /* free result set */
            $result->close();
        }
        return $opponents;
    }

}

function createTeam($number, $name) {
    $db = new Database();
    $conn = $db->getConnection();
    $stmt = $conn->prepare("INSERT INTO teams VALUES (?, ?)");
    $stmt->bind_param('is', $number, $name);
    $stmt->execute();
    $stmt->close();
    $conn->close();
    return true;
}

function getAllTeams() {
    $db = new Database();
    $conn = $db->getConnection();
    $query = "SELECT * FROM teams";
    if ($result = $conn->query($query)) {
        $i = 0;
        global $teams;
        while ($row = $result->fetch_row()) {
            $teams[$i]["number"] = $row[1];
            $teams[$i]["name"] = $row[2];
            $i++;
        }
        /* free result set */
        $result->close();
    }
    return $teams;
}
