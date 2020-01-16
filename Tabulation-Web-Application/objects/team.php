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
            $teams[$i]["number"] = $row[0];
            $teams[$i]["name"] = $row[1];
            $i++;
        }
        /* free result set */
        $result->close();
    }
    return $teams;
}
