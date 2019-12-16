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

// Get db login information
require("../config.php");

// Create db connection
$db = new mysqli(dbhost, dbuser, dbpass, dbname);

// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

//Query to create table
$query = "CREATE TABLE ballots (
id INT(4) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
pairing INT(3) UNSIGNED NOT NULL,
plaintiffPD INT(3) SIGNED NOT NULL
)";

if ($db->query($query) === TRUE) {
    echo "Table ballots created successfully";
} else {
    echo "Error creating table: " . $db->error;
}

$db->close();
?>