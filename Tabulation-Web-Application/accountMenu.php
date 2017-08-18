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

require_once 'dblogin.php';
$connection = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
if ($connection->connect_error) {
    die($connection->connect_error);
}

if(isset($_SESSION['id'])){
    $id = $_SESSION['id'];
    $nameQuery = "SELECT * FROM users WHERE id='$id'";
            $result = $connection->query($nameQuery);
            $name = $result->fetch_assoc()['name'];
    echo<<<_END
    <span>$name</span>|<span>Log Out</span>
_END;
}else{
    echo<<<_END
    <span>Log In</span>
_END;
}
