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
 
require_once __DIR__."/config.php";
require_once SITE_ROOT."/database.php";
require_once SITE_ROOT."/tableCreation/ballots.php";
require_once SITE_ROOT."/tableCreation/impermissibles.php";
require_once SITE_ROOT."/tableCreation/pairings.php";
require_once SITE_ROOT."/tableCreation/teams.php";


 echo <<<_END
<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="UTF-8">
         <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <title></title>
        <script type="text/javascript" src="index.js" defer></script>
    </head>
    <body>
    <a href="teams.php">Teams</a>
    </body>
</html>
_END;

 