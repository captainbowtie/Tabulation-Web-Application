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

require_once __DIR__ . '/../../config.php';
require_once SITE_ROOT . '/objects/pairing.php';

$data = json_decode(file_get_contents("php://input"));

if (
        isset($_POST["pOpen"]) &&
        isset($_POST["dOpen"]) &&
        isset($_POST["pDx1"]) &&
        isset($_POST["pDx2"]) &&
        isset($_POST["pDx3"]) &&
        isset($_POST["pWDx1"]) &&
        isset($_POST["pWDx2"]) &&
        isset($_POST["pWDx3"]) &&
        isset($_POST["pCx1"]) &&
        isset($_POST["pCx2"]) &&
        isset($_POST["pCx3"]) &&
        isset($_POST["dDx1"]) &&
        isset($_POST["dDx2"]) &&
        isset($_POST["dDx3"]) &&
        isset($_POST["dWDx1"]) &&
        isset($_POST["dWDx2"]) &&
        isset($_POST["dWDx3"]) &&
        isset($_POST["dCx1"]) &&
        isset($_POST["dCx2"]) &&
        isset($_POST["dCx3"]) &&
        isset($_POST["pClose"]) &&
        isset($_POST["dClose"]) &&
        isset($_POST["wit1"]) &&
        isset($_POST["wit2"]) &&
        isset($_POST["wit3"]) &&
        isset($_POST["wit4"]) &&
        isset($_POST["wit5"]) &&
        isset($_POST["wit6"]) &&
        isset($_POST["url"])
) {
    $captains["pOpen"] = htmlspecialchars(strip_tags($_POST["pOpen"]));
    $captains["dOpen"] = htmlspecialchars(strip_tags($_POST["dOpen"]));
    $captains["pDx1"] = htmlspecialchars(strip_tags($_POST["pDx1"]));
    $captains["pDx2"] = htmlspecialchars(strip_tags($_POST["pDx2"]));
    $captains["pDx3"] = htmlspecialchars(strip_tags($_POST["pDx3"]));
    $captains["pWDx1"] = htmlspecialchars(strip_tags($_POST["pWDx1"]));
    $captains["pWDx2"] = htmlspecialchars(strip_tags($_POST["pWDx2"]));
    $captains["pWDx3"] = htmlspecialchars(strip_tags($_POST["pWDx3"]));
    $captains["pCx1"] = htmlspecialchars(strip_tags($_POST["pCx1"]));
    $captains["pCx2"] = htmlspecialchars(strip_tags($_POST["pCx2"]));
    $captains["pCx3"] = htmlspecialchars(strip_tags($_POST["pCx3"]));
    $captains["dDx1"] = htmlspecialchars(strip_tags($_POST["dDx1"]));
    $captains["dDx2"] = htmlspecialchars(strip_tags($_POST["dDx2"]));
    $captains["dDx3"] = htmlspecialchars(strip_tags($_POST["dDx3"]));
    $captains["dWDx1"] = htmlspecialchars(strip_tags($_POST["dWDx1"]));
    $captains["dWDx2"] = htmlspecialchars(strip_tags($_POST["dWDx2"]));
    $captains["dWDx3"] = htmlspecialchars(strip_tags($_POST["dWDx3"]));
    $captains["dCx1"] = htmlspecialchars(strip_tags($_POST["dCx1"]));
    $captains["dCx2"] = htmlspecialchars(strip_tags($_POST["dCx2"]));
    $captains["dCx3"] = htmlspecialchars(strip_tags($_POST["dCx3"]));
    $captains["pClose"] = htmlspecialchars(strip_tags($_POST["pClose"]));
    $captains["dClose"] = htmlspecialchars(strip_tags($_POST["dClose"]));
    $captains["wit1"] = htmlspecialchars(strip_tags($_POST["wit1"]));
    $captains["wit2"] = htmlspecialchars(strip_tags($_POST["wit2"]));
    $captains["wit3"] = htmlspecialchars(strip_tags($_POST["wit3"]));
    $captains["wit4"] = htmlspecialchars(strip_tags($_POST["wit4"]));
    $captains["wit5"] = htmlspecialchars(strip_tags($_POST["wit5"]));
    $captains["wit6"] = htmlspecialchars(strip_tags($_POST["wit6"]));
    $captains["url"] = htmlspecialchars(strip_tags($_POST["url"]));

    if (submitCaptains($captains)) {
        // set response code - 201 created
        http_response_code(201);

        // tell the user
        echo json_encode(array("message" => 0));
    } else {

        // set response code - 503 service unavailable
        http_response_code(503);

        // tell the user
        echo json_encode(array("message" => "Unable to write captains data."));
    }
} else {

    // set response code - 400 bad request
    http_response_code(400);

    // tell the user
    echo json_encode(array("message" => "Unable to write captains data. Data is incomplete."));
}
