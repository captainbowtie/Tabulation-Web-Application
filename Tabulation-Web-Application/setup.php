<?php

/* 
 * Copyright (C) 2017 allen
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

//Query to drop any pre-existing tables with the same names as will be used
$initialDrop = "DROP TABLE teams; DROP TABLE competitors; DROP TABLE judges;"
        . "DROP TABLE ballots; DROP TABLE rooms;";
$createTables = "CREATE TABLE teams(teamNumber SMALLINT UNSIGNED NOT NULL KEY, teamName VARCHAR(64)) ENGINE InnoDB;"
        . "CREATE TABLE competitors(id INT UNSIGNED NOT NULL AUTO_INCREMENT KEY, teamNumber SMALLINT UNSIGNED, name VARCHAR(64)) ENGINE InnoDB;"
        . "CREATE TABLE rooms(id INT UNSIGNED NOT NULL AUTO_INCREMENT KEY, building VARCHAR(64), number SMALL INT UNSIGNED, availableRound1 BINARY(1), availableRound2 BINARY(1), availableRound3 BINARY(1), availableRound4 BINARY (4)) ENGINE InnoDB;"
        . "CREATE TABLE users(id INT UNSIGNED NOT NULL AUTO_INCREMENT KEY, name VARCHAR(64), email VARCHAR(64), password CHAR(128), isJudge BINARY(1), isCoach BINARY(1), isTab BINARY(1)) ENGINE InnoDB;"
        . "CREATE TABLE ballots() ENGINE InnoDB;";