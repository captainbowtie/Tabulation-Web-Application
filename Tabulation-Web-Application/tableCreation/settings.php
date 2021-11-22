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
$query = "CREATE TABLE IF NOT EXISTS settings (
id INT(1) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
judgesPerRound INT(2) UNSIGNED NOT NULL,
lowerTeamIsHigherRank BOOLEAN NOT NULL,
snakeStartsOnPlaintiff BOOLEAN NOT NULL,
roundFourBallotsViewable BOOLEAN NOT NULL
)";

if ($conn->query($query) === TRUE) {
    $settingsExistQuery = "SELECT * FROM settings";
    $numRows = mysqli_num_rows($conn->query($settingsExistQuery));
    if ($numRows === 0) {
        $defaultsQuery = "INSERT INTO settings (judgesPerRound, lowerTeamIsHigherRank, snakeStartsOnPlaintiff, roundFourBallotsViewable) VALUES "
                . "(2,TRUE,TRUE,FALSE)";
        $conn->query($defaultsQuery);
    }
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
