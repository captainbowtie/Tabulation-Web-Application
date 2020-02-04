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

/**
 * Description of impermissible
 *
 * @author allen
 */

require_once __DIR__ . "/../config.php";
require_once SITE_ROOT . "/database.php";

class Impermissible{
 
    // object properties
    public $id;
    public $team0;
    public $team1;
    
    public function __construct($team0, $team1) {
        $this->team0 = $team0;
        $this->team1 = $team1;
    }
 
}

function createImpermissible($team0, $team1) {
    $db = new Database();
    $conn = $db->getConnection();
    $stmt = $conn->prepare("INSERT INTO impermissibles (team0, team1) VALUES (?, ?)");
    $stmt->bind_param('ii', $team0, $team1);
    $stmt->execute();
    $stmt->close();
    $conn->close();
    return true;
}

function getAllImpermissibles() {
    $db = new Database();
    $conn = $db->getConnection();
    $query = "SELECT * FROM impermissibles";
    if ($result = $conn->query($query)) {
        $i = 0;
        global $impermissibles;
        while ($row = $result->fetch_row()) {
            $impermissibles[$i]["team0"] = $row[1];
            $impermissibles[$i]["team1"] = $row[2];
            $i++;
        }
        /* free result set */
        $result->close();
    }
    return $impermissibles;
}