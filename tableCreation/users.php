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
$query = "CREATE TABLE IF NOT EXISTS users (
id INT(3) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
username VARCHAR(64) NOT NULL,
isAdmin BOOLEAN NOT NULL DEFAULT false
url CHAR(64)
)";

$conn->exec($query);

$usersExistQuery = 'select * from users';
$data = $conn->query($usersExistQuery);
$rows = $data->fetchAll();
$numRows = count($rows);

if ($numRows === 0) {
    $defaultsQuery = $conn->prepare("INSERT INTO users (username, isAdmin, url) VALUES "
        . "(allen,TRUE,allen)");
    $defaultsQuery->execute();
}

$conn = null;
