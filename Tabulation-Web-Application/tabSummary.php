<?php
/*
 * Copyright (C) 2021 allen
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
if ($_SESSION["isAdmin"]) {
    require_once __DIR__ . "/config.php";
    require_once SITE_ROOT . "/database.php";
    require_once SITE_ROOT . "/objects/team.php";
    require_once SITE_ROOT . "/objects/pairing.php";
    require_once SITE_ROOT . "/objects/ballot.php";
    require_once SITE_ROOT . "/objects/settings.php";
    require_once SITE_ROOT . "/loginHeader.php";

    $teams = getAllTeams();
    $pairings = getAllPairings();
    $ballots = getAllBallots();

    $bodyHTML = "";

    for ($a = 0; $a < sizeOf($teams); $a++) {
        foreach ($pairings as $pairing) {
            //determine side and opponent for round 1
            if ($pairing["round"] === 1 && $pairing["plaintiff"] === $teams[$a]["number"]) {
                $teams[$a]["r1Side"] = "π";
                $teams[$a]["r1Opponent"] = $pairing["defense"];
            } else if ($pairing["round"] === 1 && $pairing["defense"] === $teams[$a]["number"]) {
                $teams[$a]["r1Side"] = "∆";
                $teams[$a]["r1Opponent"] = $pairing["plaintiff"];
            }

            //determine side and opponent for round 2
            if ($pairing["round"] === 2 && $pairing["plaintiff"] === $teams[$a]["number"]) {
                $teams[$a]["r2Opponent"] = $pairing["defense"];
            } else if ($pairing["round"] === 2 && $pairing["defense"] === $teams[$a]["number"]) {
                $teams[$a]["r2Opponent"] = $pairing["plaintiff"];
            }

            //determine side and opponent for round 3
            if ($pairing["round"] === 3 && $pairing["plaintiff"] === $teams[$a]["number"]) {
                $teams[$a]["r3Side"] = "π";
                $teams[$a]["r3Opponent"] = $pairing["defense"];
            } else if ($pairing["round"] === 3 && $pairing["defense"] === $teams[$a]["number"]) {
                $teams[$a]["r3Side"] = "∆";
                $teams[$a]["r3Opponent"] = $pairing["plaintiff"];
            }

            //determine side and opponent for round 4
            if ($pairing["round"] === 4 && $pairing["plaintiff"] === $teams[$a]["number"]) {
                $teams[$a]["r4Opponent"] = $pairing["defense"];
            } else if ($pairing["round"] === 4 && $pairing["defense"] === $teams[$a]["number"]) {
                $teams[$a]["r4Opponent"] = $pairing["plaintiff"];
            }
            foreach ($ballots as $ballot) {
                
            }
        }
    }
    var_dump($teams);
} else {
    die("Access denied");
}
?>
<!DOCTYPE html>
<!--
Copyright (C) 2020 allen

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
-->


<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width = device-width, initial-scale = 1.0">
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

        <!--Latest compiled and minified JavaScript-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>

        <!--Latest compiled and minified CSS-->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

        <link rel="stylesheet" href="pairings.css">
        <title>Pairings</title>
    </head>
    <body>
        <?php
        echo $header;
        ?>

        <script src="pairings.js"></script>
    </body>
</html>