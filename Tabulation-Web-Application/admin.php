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

//TODO: add admin for coach and judge

session_start();

require_once "dblogin.php";


//HTML Header information
echo<<<_END
<!DOCTYPE HTML>
<html>
        <head>
            <title>Admin</title>
                </head>
        <body>
_END;

require_once "header.php";

if (!isset($_SESSION['id'])) {
    echo "You must be logged in to view this page";
//Web page if logged in, but not as a coach or tabulation director
} else if (!$isTab) {
    echo "You do not have permission to access this page";
//Code if logged in as tabulation director
} else {
    require_once 'userTable.php';
    echo "<form id='userForm'>\n";
    echo "<label>Name: <input id='name' name='name'></label>\n";
    echo "<label>Email: <input id='email' name='email' pattern='[A-z0-9._%+-]+@[A-z0-9.-]+\.[A-z]{2,}'></label>\n";
    echo "<label>Password: <input type='password' id='pass' name='pass'></label>\n";
    echo "<label><input type=checkbox id='judge' name='judge'>Judge</label>\n" .
    "<label><input type=checkbox id='coach' name='coach'>Coach</label>\n" .
    "<label><input type=checkbox id='tab' name='tab'>Tab</label>\n";
    echo "<label><input type=checkbox id='round1' name='round1'>Available Round 1</label>\n" .
    "<label><input type=checkbox id='round2' name='round2'>Available Round 2</label>\n" .
    "<label><input type=checkbox id='round3' name='round3'>Available Round 3</label>\n" .
    "<label><input type=checkbox id='round4' name='round4'>Available Round 4</label>\n";
    echo "<input type=submit id='addUserButton' name='addUser' value='Add User'>\n";
    echo "</form>\n";
}


echo "<script src='https://code.jquery.com/jquery-3.2.1.min.js'></script>";
echo "<script src='/admin.js'></script>";
echo "</body>\n";
echo "</html>\n";

