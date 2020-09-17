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
require_once SITE_ROOT ."/objects/user.php";

// Create db connection
$db = new Database();
$conn = $db->getConnection();

//Query to create table
$query = "CREATE TABLE IF NOT EXISTS users (
id INT(3) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
email VARCHAR(64) NOT NULL,
password CHAR(60) NOT NULL,
isAdmin BOOLEAN NOT NULL DEFAULT false,
isCoach BOOLEAN NOT NULL DEFAULT false
)";

$timeTarget = 0.06; // 50 milliseconds 



if ($conn->query($query) === TRUE) {
    $adminCheckQuery = "SELECT * FROM users";
    $numRows = mysqli_num_rows($conn->query($adminCheckQuery));
    if ($numRows === 0) {
        if (createUser("allen@allenbarr.com", "password", true)) {
            
        } else {
            echo "Error creating default user: " . $conn->error;
        }
    }
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
?>