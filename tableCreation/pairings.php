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
$query = "CREATE TABLE IF NOT EXISTS pairings (
id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
round INT(1) UNSIGNED NOT NULL,
room VARCHAR(32) DEFAULT 'N/A',
plaintiff INT(4) UNSIGNED NOT NULL,
defense INT(4) UNSIGNED NOT NULL,
pOpen SMALLINT DEFAULT 0,
dOpen SMALLINT DEFAULT 0,
pDirectingAttorney1 SMALLINT DEFAULT 0,
pDirectingAttorney2 SMALLINT DEFAULT 0,
pDirectingAttorney3 SMALLINT DEFAULT 0,
pCrossingAttorney1 SMALLINT DEFAULT 0,
pCrossingAttorney2 SMALLINT DEFAULT 0,
pCrossingAttorney3 SMALLINT DEFAULT 0,
pStudentWitness1 SMALLINT DEFAULT 0,
pStudentWitness2 SMALLINT DEFAULT 0,
pStudentWitness3 SMALLINT DEFAULT 0,
dDirectingAttorney1 SMALLINT DEFAULT 0,
dDirectingAttorney2 SMALLINT DEFAULT 0,
dDirectingAttorney3 SMALLINT DEFAULT 0,
dCrossingAttorney1 SMALLINT DEFAULT 0,
dCrossingAttorney2 SMALLINT DEFAULT 0,
dCrossingAttorney3 SMALLINT DEFAULT 0,
dStudentWitness1 SMALLINT DEFAULT 0,
dStudentWitness2 SMALLINT DEFAULT 0,
dStudentWitness3 SMALLINT DEFAULT 0,
pClose SMALLINT DEFAULT 0,
dClose SMALLINT DEFAULT 0,
pWitness1 CHAR(32) DEFAULT 'N/A',
pWitness2 CHAR(32) DEFAULT 'N/A',
pWitness3 CHAR(32) DEFAULT 'N/A',
dWitness1 CHAR(32) DEFAULT 'N/A',
dWitness2 CHAR(32) DEFAULT 'N/A',
dWitness3 CHAR(32) DEFAULT 'N/A',
url CHAR(64)
)";

$conn->exec($query);
$conn = null;
