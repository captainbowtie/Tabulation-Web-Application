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
session_start();
if ($_SESSION["isAdmin"]) {
    require_once __DIR__ . '/../../config.php';
    require_once SITE_ROOT . '/objects/judge.php';

    if (
            $_POST["name"] &&
            $_POST["category"] &&
            $_POST["round1"] &&
            $_POST["round2"] &&
            $_POST["round3"] &&
            $_POST["round4"]
    ) {
        $name = htmlspecialchars(strip_tags($_POST["name"]));
        $category = htmlspecialchars(strip_tags($_POST["category"]));
        $round1 = htmlspecialchars(strip_tags($_POST["round1"]));
        $round2 = htmlspecialchars(strip_tags($_POST["round2"]));
        $round3 = htmlspecialchars(strip_tags($_POST["round3"]));
        $round4 = htmlspecialchars(strip_tags($_POST["round4"]));

        if (createJudge($name, $category, $round1, $round2, $round3, $round4)) {
            // set response code - 201 created
            http_response_code(201);

            // tell the user
            echo json_encode(array("message" => 0));
        } else {

            // set response code - 503 service unavailable
            http_response_code(503);

            // tell the user
            echo json_encode(array("message" => "Unable to create judge."));
        }
    } else {

        // set response code - 400 bad request
        http_response_code(400);

        // tell the user
        echo json_encode(array("message" => "Unable to create judge. Data is incomplete."));
    }
} else {
    http_response_code(401);
}

