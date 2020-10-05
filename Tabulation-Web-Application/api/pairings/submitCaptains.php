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
        isset($data->pOpen) &&
        isset($data->dOpen) &&
        isset($data->pDx1) &&
        isset($data->pDx2) &&
        isset($data->pDx3) &&
        isset($data->pWDx1) &&
        isset($data->pWDx2) &&
        isset($data->pWDx3) &&
        isset($data->pCx1) &&
        isset($data->pCx2) &&
        isset($data->pCx3) &&
        isset($data->dDx1) &&
        isset($data->dDx2) &&
        isset($data->dDx3) &&
        isset($data->dWDx1) &&
        isset($data->dWDx2) &&
        isset($data->dWDx3) &&
        isset($data->dCx1) &&
        isset($data->dCx2) &&
        isset($data->dCx3) &&
        isset($data->pClose) &&
        isset($data->dClose) &&
        isset($data->wit1) &&
        isset($data->wit2) &&
        isset($data->wit3) &&
        isset($data->wit4) &&
        isset($data->wit5) &&
        isset($data->wit6) &&
        isset($data->url)
) {
    $captains["pOpen"] = htmlspecialchars(strip_tags($data->pOpen));
    $captains["dOpen"] = htmlspecialchars(strip_tags($data->dOpen));
    $captains["pDx1"] = htmlspecialchars(strip_tags($data->pDx1));
    $captains["pDx2"] = htmlspecialchars(strip_tags($data->pDx2));
    $captains["pDx3"] = htmlspecialchars(strip_tags($data->pDx3));
    $captains["pWDx1"] = htmlspecialchars(strip_tags($data->pWDx1));
    $captains["pWDx2"] = htmlspecialchars(strip_tags($data->pWDx2));
    $captains["pWDx3"] = htmlspecialchars(strip_tags($data->pWDx3));
    $captains["pCx1"] = htmlspecialchars(strip_tags($data->pCx1));
    $captains["pCx2"] = htmlspecialchars(strip_tags($data->pCx2));
    $captains["pCx3"] = htmlspecialchars(strip_tags($data->pCx3));
    $captains["dDx1"] = htmlspecialchars(strip_tags($data->dDx1));
    $captains["dDx2"] = htmlspecialchars(strip_tags($data->dDx2));
    $captains["dDx3"] = htmlspecialchars(strip_tags($data->dDx3));
    $captains["dWDx1"] = htmlspecialchars(strip_tags($data->dWDx1));
    $captains["dWDx2"] = htmlspecialchars(strip_tags($data->dWDx2));
    $captains["dWDx3"] = htmlspecialchars(strip_tags($data->dWDx3));
    $captains["dCx1"] = htmlspecialchars(strip_tags($data->dCx1));
    $captains["dCx2"] = htmlspecialchars(strip_tags($data->dCx2));
    $captains["dCx3"] = htmlspecialchars(strip_tags($data->dCx3));
    $captains["pClose"] = htmlspecialchars(strip_tags($data->pClose));
    $captains["dClose"] = htmlspecialchars(strip_tags($data->dClose));
    $captains["wit1"] = htmlspecialchars(strip_tags($data->wit1));
    $captains["wit2"] = htmlspecialchars(strip_tags($data->wit2));
    $captains["wit3"] = htmlspecialchars(strip_tags($data->wit3));
    $captains["wit4"] = htmlspecialchars(strip_tags($data->wit4));
    $captains["wit5"] = htmlspecialchars(strip_tags($data->wit5));
    $captains["wit6"] = htmlspecialchars(strip_tags($data->wit6));
    $captains["url"] = htmlspecialchars(strip_tags($data->url));

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
