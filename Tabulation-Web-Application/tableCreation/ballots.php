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
pOpenComments TEXT DEFAULT 'Plaintiff/Prosecution opening comments go here; score goes in box above.',
dOpen TINYINT UNSIGNED NOT NULL DEFAULT 0,
dOpenComments TEXT DEFAULT 'Defense opening comments go here; score goes in box above.',
pDx1 TINYINT UNSIGNED NOT NULL DEFAULT 0,
pDx1Comments TEXT DEFAULT 'Plaintiff/Prosecution's first directing attorney comments go here; score goes in box above.',
pWDx1 TINYINT UNSIGNED NOT NULL DEFAULT 0,
pWDx1Comments TEXT DEFAULT 'Plaintiff/Prosecution first witness direct comments go here; score goes in box above.',
pWCx1 TINYINT UNSIGNED NOT NULL DEFAULT 0,
pWCx1Comments TEXT DEFAULT 'Plaintiff/Prosecution first witness cross comments go here; score goes in box above.',
dCx1 TINYINT UNSIGNED NOT NULL DEFAULT 0,
dCx1Comments TEXT DEFAULT 'Defense's first crossing attorney comments go here; score goes in box above.',
pDx2 TINYINT UNSIGNED NOT NULL DEFAULT 0,
pDx2Comments TEXT DEFAULT 'Plaintiff/Prosecution second directing attorney comments go here; score goes in box above.',
pWDx2 TINYINT UNSIGNED NOT NULL DEFAULT 0,
pWDx2Comments TEXT DEFAULT 'Plaintiff/Prosecution second witness direct comments go here; score goes in box above.',
pWCx2 TINYINT UNSIGNED NOT NULL DEFAULT 0,
pWCx2Comments TEXT DEFAULT 'Plaintiff/Prosecution second witness cross comments go here; score goes in box above.',
dCx2 TINYINT UNSIGNED NOT NULL DEFAULT 0,
dCx2Comments TEXT DEFAULT 'Defense's second crossing attorney comments go here; score goes in box above.',
pDx3 TINYINT UNSIGNED NOT NULL DEFAULT 0,
pDx3Comments TEXT DEFAULT 'Plaintiff/Prosecution third directing attorney comments go here; score goes in box above.',
pWDx3 TINYINT UNSIGNED NOT NULL DEFAULT 0,
pWDx3Comments TEXT DEFAULT 'Plaintiff/Prosecution third witness direct comments go here; score goes in box above.',
pWCx3 TINYINT UNSIGNED NOT NULL DEFAULT 0,
pWCx3Comments TEXT DEFAULT 'Plaintiff/Prosecution third witness cross comments go here; score goes in box above.',
dCx3 TINYINT UNSIGNED NOT NULL DEFAULT 0,
dCx3Comments TEXT DEFAULT 'Defense's third crossing attorney comments go here; score goes in box above.',
dDx1 TINYINT UNSIGNED NOT NULL DEFAULT 0,
dDx1Comments TEXT DEFAULT 'Defense first directing attorney comments go here; score goes in box above.',
dWDx1 TINYINT UNSIGNED NOT NULL DEFAULT 0,
dWDx1Comments TEXT DEFAULT 'Defense first witness direct comments go here; score goes in box above.',
dWCx1 TINYINT UNSIGNED NOT NULL DEFAULT 0,
dWCx1Comments TEXT DEFAULT 'Defense first witness cross comments go here; score goes in box above.',
pCx1 TINYINT UNSIGNED NOT NULL DEFAULT 0,
pCx1Comments TEXT DEFAULT 'Plaintiff/Prosecution first crossing attorney comments go here; score goes in box above.',
dDx2 TINYINT UNSIGNED NOT NULL DEFAULT 0,
dDx2Comments TEXT DEFAULT 'Defense second directing attorney comments go here; score goes in box above.',
dWDx2 TINYINT UNSIGNED NOT NULL DEFAULT 0,
dWDx2Comments TEXT DEFAULT 'Defense second witness direct comments go here; score goes in box above.',
dWCx2 TINYINT UNSIGNED NOT NULL DEFAULT 0,
dWCx2Comments TEXT DEFAULT 'Defense second witness cross comments go here; score goes in box above.',
pCx2 TINYINT UNSIGNED NOT NULL DEFAULT 0,
pCx2Comments TEXT DEFAULT 'Plaintiff/Prosecution second crossing attorney comments go here; score goes in box above.',
dDx3 TINYINT UNSIGNED NOT NULL DEFAULT 0,
dDx3Comments TEXT DEFAULT 'Defense third directing attorney comments go here; score goes in box above.',
dWDx3 TINYINT UNSIGNED NOT NULL DEFAULT 0,
dWDx3Comments TEXT DEFAULT 'Defense third witness direct comments go here; score goes in box above.',
dWCx3 TINYINT UNSIGNED NOT NULL DEFAULT 0,
dWCx3Comments TEXT DEFAULT 'Defense third witness cross  comments go here; score goes in box above.',
pCx3 TINYINT UNSIGNED NOT NULL DEFAULT 0,
pCx3Comments TEXT DEFAULT 'Plaintiff/Prosecution third crossing attorney comments go here; score goes in box above.',
pClose TINYINT UNSIGNED NOT NULL DEFAULT 0,
pCloseComments TEXT  DEFAULT 'Plaintiff/Prosecution closing comments go here; score goes in box above.',
dClose TINYINT UNSIGNED NOT NULL DEFAULT 0,
dCloseComments TEXT  DEFAULT 'Defense closing comments go here; score goes in box above.',
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