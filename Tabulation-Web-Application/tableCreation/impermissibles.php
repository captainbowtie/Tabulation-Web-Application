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
require_once __DIR__."/../config.php";

// Create db connection
$db = new mysqli(dbhost, dbuser, dbpass, dbname);

// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

//Query to create table
$query = "CREATE TABLE impermissibles (
id INT(2) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
team0 INT(4) UNSIGNED NOT NULL,
team1 INT(4) UNSIGNED NOT NULL
)";

if ($db->query($query) === TRUE) {
    echo "Table impermissibles created successfully";
} else {
    echo "Error creating table: " . $db->error;
}

$db->close();
?>