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

require_once __DIR__ . "/config.php";
require_once SITE_ROOT . "/database.php";
session_start();
$header = "<div>\n";
if ($_SESSION["isAdmin"]) {
    $header .= '<a href="index.php">Home</a>
        <a href="ballots.php">Ballots</a>
    <a href="pairings.php">Pairings</a>
    <a href="judges.php">Judges</a>
    <a href="teams.php">Teams</a>
    <a href="users.php">Users</a>
    <a href="judgesURLs.php">Judges URLs</a>
    <a href="individuals.php">Individual Awards</a>
    <a href="comments.php">Comments</a>
    <a href="captainsCompletion.php">Captains Completion</a>';
    
    $header .= "<a style='float: right' href='logout.php'>Log Out</a>\n";
} elseif ($_SESSION["isCoach"]) {
    $header .= '<a href="index.php">Home</a>
        <a href="comments.php">Ballots</a>';
    $header .= "<a style='float: right' href='logout.php'>Log Out</a>\n";
} else {
    $header .= "<a style='float: right' href='login.php'>Log In</a>\n";
}
$header .= "</div>\n";
