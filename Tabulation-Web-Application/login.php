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
//Require database login credentials
require_once 'dblogin.php';
//Require common PHP functions
require_once 'functions.php';

if (isset($_POST["email"]) && isset($_POST["pass"])){
    $email = sanitize_string($_POST["email"]);
    $pass = sanitize_string($_POST["pass"]);
    
    $hashedPass = hash('whirlpool',$pass);
    $connection = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
    $query = "SELECT * FROM users WHERE email='$email' && password='$hashedPass'";
    $result = $connection->query($query);
    $id = $result->fetch_assoc()['id'];
    $_SESSION['id'] = $id;
}

echo<<<_END
    
<form method="post" action="index.php">
<table>
<tr>
<td>Email</td>
<td><input type="text" maxlength="64" name="email"></td>
        </tr>
            <tr>
<td>Password</td>
<td><input type="password" maxlength="64" name="pass"></td>
        </tr>
        <tr>
        <td></td>
        <td><input type="submit" value="Login"></td>
        </tr></table>
        </form>
_END
?>