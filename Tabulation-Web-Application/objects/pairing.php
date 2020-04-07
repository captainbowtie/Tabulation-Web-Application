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

class Pairing {

    public $id;
    public $round;
    public $plaintiff;
    public $defense;

    public function __construct($round, $plaintiff, $defense) {
        $this->round = $round;
        $this->plaintiff = $plaintiff;
        $this->defense = $defense;
    }

}

function createPairing($round, $plaintiff, $defense) {
    $db = new Database();
    $conn = $db->getConnection();
    $stmt =$conn->prepare("INSERT INTO pairings (round, plaintiff, defense) VALUES (?, ?, ?)");
    echo($stmt->error_list);
    $stmt->bind_param('iii', $round, $plaintiff, $defense);
    $stmt->execute();
    $stmt->close();
    $conn->close();
    return true;
}

function getAllPairings() {
    global $pairings;

    //connect to database
    $db = new Database();
    $conn = $db->getConnection();

    //get basic team data (number and name)
    $pairingssQuery = "SELECT * FROM pairings";
    if ($result = $conn->query($pairingssQuery)) {
        $i = 0;
        while ($row = $result->fetch_assoc()) {
            $pairings[$i]["round"] = $row["round"];
            $pairings[$i]["plaintiff"] = $row["plaintiff"];
            $pairings[$i]["defense"] = $row["defense"];
            $i++;
        }
        /* free result set */
        $result->close();
    }

    return $pairings;
}
