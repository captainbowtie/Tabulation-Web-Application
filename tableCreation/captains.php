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

// Get config information
require_once __DIR__ . "/../config.php";
require_once SITE_ROOT . "/database.php";

// Create db connection
$db = new Database();
$conn = $db->getConnection();

//Query to create table
$query = "CREATE TABLE IF NOT EXISTS captains (
id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
pairing SMALLINT UNSIGNED NOT NULL,
prosecutingPorta BOOLEAN NOT NULL,
count1 BOOLEAN NOT NULL,
count2 BOOLEAN NOT NULL,
count3 BOOLEAN NOT NULL,
count4 BOOLEAN NOT NULL,
count5 BOOLEAN NOT NULL,
duress BOOLEAN NOT NULL,
pWitness1 VARCHAR(7) NOT NULL,
pWitness2 VARCHAR(7) NOT NULL,
pWitness3 VARCHAR(7) NOT NULL,
dWitness1 VARCHAR(7) NOT NULL,
dWitness2 VARCHAR(7) NOT NULL,
dWitness3 VARCHAR(7) NOT NULL
)";

$conn->exec($query);
$conn = null;
