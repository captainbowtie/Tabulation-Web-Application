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

// Get config information
require_once __DIR__ . "/../config.php";
require_once SITE_ROOT . "/database.php";

// Create db connection

$db = new Database();
$conn = $db->getConnection();

//Query to create table
$query = "CREATE TABLE IF NOT EXISTS ballots (
id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
pairing INT UNSIGNED NOT NULL,
pOpen TINYINT UNSIGNED NOT NULL DEFAULT 0,
dOpen TINYINT UNSIGNED NOT NULL DEFAULT 0,
pDx1 TINYINT UNSIGNED NOT NULL DEFAULT 0,
pWDx1 TINYINT UNSIGNED NOT NULL DEFAULT 0,
pWCx1 TINYINT UNSIGNED NOT NULL DEFAULT 0,
dCx1 TINYINT UNSIGNED NOT NULL DEFAULT 0,
pDx2 TINYINT UNSIGNED NOT NULL DEFAULT 0,
pWDx2 TINYINT UNSIGNED NOT NULL DEFAULT 0,
pWCx2 TINYINT UNSIGNED NOT NULL DEFAULT 0,
dCx2 TINYINT UNSIGNED NOT NULL DEFAULT 0,
pDx3 TINYINT UNSIGNED NOT NULL DEFAULT 0,
pWDx3 TINYINT UNSIGNED NOT NULL DEFAULT 0,
pWCx3 TINYINT UNSIGNED NOT NULL DEFAULT 0,
dCx3 TINYINT UNSIGNED NOT NULL DEFAULT 0,
dDx1 TINYINT UNSIGNED NOT NULL DEFAULT 0,
dWDx1 TINYINT UNSIGNED NOT NULL DEFAULT 0,
dWCx1 TINYINT UNSIGNED NOT NULL DEFAULT 0,
pCx1 TINYINT UNSIGNED NOT NULL DEFAULT 0,
dDx2 TINYINT UNSIGNED NOT NULL DEFAULT 0,
dWDx2 TINYINT UNSIGNED NOT NULL DEFAULT 0,
dWCx2 TINYINT UNSIGNED NOT NULL DEFAULT 0,
pCx2 TINYINT UNSIGNED NOT NULL DEFAULT 0,
dDx3 TINYINT UNSIGNED NOT NULL DEFAULT 0,
dWDx3 TINYINT UNSIGNED NOT NULL DEFAULT 0,
dWCx3 TINYINT UNSIGNED NOT NULL DEFAULT 0,
pCx3 TINYINT UNSIGNED NOT NULL DEFAULT 0,
pClose TINYINT UNSIGNED NOT NULL DEFAULT 0,
dClose TINYINT UNSIGNED NOT NULL DEFAULT 0
)";

if ($conn->query($query) === TRUE) {
    
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
?>