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

class Ballot{
 
    // object properties
    public $id;
    public $pairing;
    public $plaintiffPD;
    
}

function createBallot($pairing, $plaintiffPD) {
    $db = new Database();
    $conn = $db->getConnection();
    $stmt =$conn->prepare("INSERT INTO ballots (pairing, plaintiffPD) VALUES (?, ?)");
    echo($stmt->error_list);
    $stmt->bind_param('ii', $pairing, $plaintiffPD);
    $stmt->execute();
    $stmt->close();
    $conn->close();
    return true;
}