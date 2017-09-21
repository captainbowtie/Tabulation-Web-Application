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

session_start();

require_once "dblogin.php";
require_once 'setSessionPrivileges.php';

if (isset($_SESSION['id'])) {
    $id = $_SESSION['id'];
    $nameQuery = "SELECT * FROM users WHERE id='$id'";
    $result = $connection->query($nameQuery);
    $name = $result->fetch_assoc()['name'];
    echo "<span>$name</span>|<span><a href='logout.php'>Log Out</a></span>";
} else {
    echo<<<_END
\n<form id='login'>
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
_END;
}