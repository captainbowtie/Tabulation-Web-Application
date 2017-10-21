<?php

/*
  Copyright (C) 2017 allen

  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU Affero General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU Affero General Public License for more details.

  You should have received a copy of the GNU Affero General Public License
  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

session_start();

require_once 'dblogin.php';

$connection = new mysqli(dbhost, dbuser, dbpass, dbname);
$query = "SHOW TABLES LIKE 'users'";
$result = $connection->query($query);
$usersTableExists = $result->num_rows > 0;

if (!$usersTableExists) {
    require_once 'setup.php';
} else {
//TODO: write actual index page
    require_once 'setSessionPrivileges.php';

    if ($isCoach) {
        header("Location: https://tab.allenbarr.com/teamEntry.php"); /* Redirect browser */
        exit();
    } else {
        echo <<<_END
<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
_END;
        include_once 'header.php';
        echo "<br>";


        echo <<<_END
    </body>
</html>
_END;
    }
}
