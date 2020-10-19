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
require_once __DIR__."/../config.php";
require_once SITE_ROOT."/database.php";

// Create db connection
$db = new Database();
$conn = $db->getConnection();

//Query to create table
$query = "CREATE TABLE IF NOT EXISTS pairings (
id INT(3) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
round INT(1) UNSIGNED NOT NULL,
plaintiff INT(4) UNSIGNED NOT NULL,
defense INT(4) UNSIGNED NOT NULL,
pOpen CHAR(32) DEFAULT 'N/A',
dOpen CHAR(32) DEFAULT 'N/A',
pDx1 CHAR(32) DEFAULT 'N/A',
pDx2 CHAR(32) DEFAULT 'N/A',
pDx3 CHAR(32) DEFAULT 'N/A',
pCx1 CHAR(32) DEFAULT 'N/A',
pCx2 CHAR(32) DEFAULT 'N/A',
pCx3 CHAR(32) DEFAULT 'N/A',
pWDx1 CHAR(32) DEFAULT 'N/A',
pWDx2 CHAR(32) DEFAULT 'N/A',
pWDx3 CHAR(32) DEFAULT 'N/A',
dDx1 CHAR(32) DEFAULT 'N/A',
dDx2 CHAR(32) DEFAULT 'N/A',
dDx3 CHAR(32) DEFAULT 'N/A',
dCx1 CHAR(32) DEFAULT 'N/A',
dCx2 CHAR(32) DEFAULT 'N/A',
dCx3 CHAR(32) DEFAULT 'N/A',
dWDx1 CHAR(32) DEFAULT 'N/A',
dWDx2 CHAR(32) DEFAULT 'N/A',
dWDx3 CHAR(32) DEFAULT 'N/A',
pClose CHAR(32) DEFAULT 'N/A',
dClose CHAR(32) DEFAULT 'N/A',
wit1 CHAR(32) DEFAULT 'N/A',
wit2 CHAR(32) DEFAULT 'N/A',
wit3 CHAR(32) DEFAULT 'N/A',
wit4 CHAR(32) DEFAULT 'N/A',
wit5 CHAR(32) DEFAULT 'N/A',
wit6 CHAR(32) DEFAULT 'N/A'
)";

if ($conn->query($query) === TRUE) {
    
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
