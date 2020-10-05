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
judge SMALLINT UNSIGNED NOT NULL DEFAULT 0,
locked BOOLEAN DEFAULT FALSE,
pOpen TINYINT UNSIGNED NOT NULL DEFAULT 0,
pOpenComments TEXT,
dOpen TINYINT UNSIGNED NOT NULL DEFAULT 0,
dOpenComments TEXT,
pDx1 TINYINT UNSIGNED NOT NULL DEFAULT 0,
pDx1Comments TEXT,
pWDx1 TINYINT UNSIGNED NOT NULL DEFAULT 0,
pWDx1Comments TEXT,
pWCx1 TINYINT UNSIGNED NOT NULL DEFAULT 0,
pWCx1Comments TEXT,
dCx1 TINYINT UNSIGNED NOT NULL DEFAULT 0,
dCx1Comments TEXT,
pDx2 TINYINT UNSIGNED NOT NULL DEFAULT 0,
pDx2Comments TEXT,
pWDx2 TINYINT UNSIGNED NOT NULL DEFAULT 0,
pWDx2Comments TEXT,
pWCx2 TINYINT UNSIGNED NOT NULL DEFAULT 0,
pWCx2Comments TEXT,
dCx2 TINYINT UNSIGNED NOT NULL DEFAULT 0,
dCx2Comments TEXT,
pDx3 TINYINT UNSIGNED NOT NULL DEFAULT 0,
pDx3Comments TEXT,
pWDx3 TINYINT UNSIGNED NOT NULL DEFAULT 0,
pWDx3Comments TEXT,
pWCx3 TINYINT UNSIGNED NOT NULL DEFAULT 0,
pWCx3Comments TEXT,
dCx3 TINYINT UNSIGNED NOT NULL DEFAULT 0,
dCx3Comments TEXT,
dDx1 TINYINT UNSIGNED NOT NULL DEFAULT 0,
dDx1Comments TEXT,
dWDx1 TINYINT UNSIGNED NOT NULL DEFAULT 0,
dWDx1Comments TEXT,
dWCx1 TINYINT UNSIGNED NOT NULL DEFAULT 0,
dWCx1Comments TEXT,
pCx1 TINYINT UNSIGNED NOT NULL DEFAULT 0,
pCx1Comments TEXT,
dDx2 TINYINT UNSIGNED NOT NULL DEFAULT 0,
dDx2Comments TEXT,
dWDx2 TINYINT UNSIGNED NOT NULL DEFAULT 0,
dWDx2Comments TEXT,
dWCx2 TINYINT UNSIGNED NOT NULL DEFAULT 0,
dWCx2Comments TEXT,
pCx2 TINYINT UNSIGNED NOT NULL DEFAULT 0,
pCx2Comments TEXT,
dDx3 TINYINT UNSIGNED NOT NULL DEFAULT 0,
dDx3Comments TEXT,
dWDx3 TINYINT UNSIGNED NOT NULL DEFAULT 0,
dWDx3Comments TEXT,
dWCx3 TINYINT UNSIGNED NOT NULL DEFAULT 0,
dWCx3Comments TEXT,
pCx3 TINYINT UNSIGNED NOT NULL DEFAULT 0,
pCx3Comments TEXT,
pClose TINYINT UNSIGNED NOT NULL DEFAULT 0,
pCloseComments TEXT,
dClose TINYINT UNSIGNED NOT NULL DEFAULT 0,
dCloseComments TEXT,
aty1 CHAR(32) DEFAULT 'N/A',
aty2 CHAR(32) DEFAULT 'N/A',
aty3 CHAR(32) DEFAULT 'N/A',
aty4 CHAR(32) DEFAULT 'N/A',
wit1 CHAR(32) DEFAULT 'N/A',
wit2 CHAR(32) DEFAULT 'N/A',
wit3 CHAR(32) DEFAULT 'N/A',
wit4 CHAR(32) DEFAULT 'N/A',
url CHAR(64)
)";

if ($conn->query($query) === TRUE) {
    
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
?>