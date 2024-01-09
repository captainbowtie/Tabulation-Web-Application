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
judge TEXT,
locked BOOLEAN DEFAULT FALSE,
pOpen TINYINT NOT NULL DEFAULT -1,
pOpenComments TEXT DEFAULT '',
dOpen TINYINT NOT NULL DEFAULT -1,
dOpenComments TEXT DEFAULT '',
pDx1 TINYINT NOT NULL DEFAULT -1,
pDx1Comments TEXT DEFAULT '',
pWDx1 TINYINT NOT NULL DEFAULT -1,
pWDx1Comments TEXT DEFAULT '',
pWCx1 TINYINT NOT NULL DEFAULT -1,
pWCx1Comments TEXT DEFAULT '',
dCx1 TINYINT NOT NULL DEFAULT -1,
dCx1Comments TEXT DEFAULT '',
pDx2 TINYINT NOT NULL DEFAULT -1,
pDx2Comments TEXT DEFAULT '',
pWDx2 TINYINT NOT NULL DEFAULT -1,
pWDx2Comments TEXT DEFAULT '',
pWCx2 TINYINT NOT NULL DEFAULT -1,
pWCx2Comments TEXT DEFAULT '',
dCx2 TINYINT NOT NULL DEFAULT -1,
dCx2Comments TEXT DEFAULT '',
pDx3 TINYINT NOT NULL DEFAULT -1,
pDx3Comments TEXT DEFAULT '',
pWDx3 TINYINT NOT NULL DEFAULT -1,
pWDx3Comments TEXT DEFAULT '',
pWCx3 TINYINT NOT NULL DEFAULT -1,
pWCx3Comments TEXT DEFAULT '',
dCx3 TINYINT NOT NULL DEFAULT -1,
dCx3Comments TEXT DEFAULT '',
dDx1 TINYINT NOT NULL DEFAULT -1,
dDx1Comments TEXT DEFAULT '',
dWDx1 TINYINT NOT NULL DEFAULT -1,
dWDx1Comments TEXT DEFAULT '',
dWCx1 TINYINT NOT NULL DEFAULT -1,
dWCx1Comments TEXT DEFAULT '',
pCx1 TINYINT NOT NULL DEFAULT -1,
pCx1Comments TEXT DEFAULT '',
dDx2 TINYINT NOT NULL DEFAULT -1,
dDx2Comments TEXT DEFAULT '',
dWDx2 TINYINT NOT NULL DEFAULT -1,
dWDx2Comments TEXT DEFAULT '',
dWCx2 TINYINT NOT NULL DEFAULT -1,
dWCx2Comments TEXT DEFAULT '',
pCx2 TINYINT NOT NULL DEFAULT -1,
pCx2Comments TEXT DEFAULT '',
dDx3 TINYINT NOT NULL DEFAULT -1,
dDx3Comments TEXT DEFAULT '',
dWDx3 TINYINT NOT NULL DEFAULT -1,
dWDx3Comments TEXT DEFAULT '',
dWCx3 TINYINT NOT NULL DEFAULT -1,
dWCx3Comments TEXT DEFAULT '',
pCx3 TINYINT NOT NULL DEFAULT -1,
pCx3Comments TEXT DEFAULT '',
pClose TINYINT NOT NULL DEFAULT -1,
pCloseComments TEXT DEFAULT '',
dClose TINYINT NOT NULL DEFAULT -1,
dCloseComments TEXT DEFAULT '',
aty1 SMALLINT UNSIGNED NOT NULL DEFAULT 0,
aty2 SMALLINT UNSIGNED NOT NULL DEFAULT 0,
aty3 SMALLINT UNSIGNED NOT NULL DEFAULT 0,
aty4 SMALLINT UNSIGNED NOT NULL DEFAULT 0,
wit1 SMALLINT UNSIGNED NOT NULL DEFAULT 0,
wit2 SMALLINT UNSIGNED NOT NULL DEFAULT 0,
wit3 SMALLINT UNSIGNED NOT NULL DEFAULT 0,
wit4 SMALLINT UNSIGNED NOT NULL DEFAULT 0,
url CHAR(64),
releaseComments BOOLEAN DEFAULT FALSE,
releaseScores BOOLEAN DEFAULT FALSE
)";

$conn->exec($query);
$conn = null;
