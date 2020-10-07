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
require_once SITE_ROOT . '/objects/ballot.php';

$data = json_decode(file_get_contents("php://input"));

if (
        isset($_POST["pOpen"]) &&
        isset($_POST["pOpenComments"]) &&
        isset($_POST["dOpen"]) &&
        isset($_POST["dOpenComments"]) &&
        isset($_POST["pDx1"]) &&
        isset($_POST["pDx1Comments"]) &&
        isset($_POST["pWDx1"]) &&
        isset($_POST["pWDx1Comments"]) &&
        isset($_POST["pWCx1"]) &&
        isset($_POST["pWCx1Comments"]) &&
        isset($_POST["dCx1"]) &&
        isset($_POST["dCx1Comments"]) &&
        isset($_POST["pDx2"]) &&
        isset($_POST["pDx2Comments"]) &&
        isset($_POST["pWDx2"]) &&
        isset($_POST["pWDx2Comments"]) &&
        isset($_POST["pWCx2"]) &&
        isset($_POST["pWCx2Comments"]) &&
        isset($_POST["dCx2"]) &&
        isset($_POST["dCx2Comments"]) &&
        isset($_POST["pDx3"]) &&
        isset($_POST["pDx3Comments"]) &&
        isset($_POST["pWDx3"]) &&
        isset($_POST["pWDx3Comments"]) &&
        isset($_POST["pWCx3"]) &&
        isset($_POST["pWCx3Comments"]) &&
        isset($_POST["dCx3"]) &&
        isset($_POST["dCx3Comments"]) &&
        isset($_POST["dDx1"]) &&
        isset($_POST["dDx1Comments"]) &&
        isset($_POST["dWDx1"]) &&
        isset($_POST["dWDx1Comments"]) &&
        isset($_POST["dWCx1"]) &&
        isset($_POST["dWCx1Comments"]) &&
        isset($_POST["pCx1"]) &&
        isset($_POST["pCx1Comments"]) &&
        isset($_POST["dDx2"]) &&
        isset($_POST["dDx2Comments"]) &&
        isset($_POST["dWDx2"]) &&
        isset($_POST["dWDx2Comments"]) &&
        isset($_POST["dWCx2"]) &&
        isset($_POST["dWCx2Comments"]) &&
        isset($_POST["pCx2"]) &&
        isset($_POST["pCx2Comments"]) &&
        isset($_POST["dDx3"]) &&
        isset($_POST["dDx3Comments"]) &&
        isset($_POST["dWDx3"]) &&
        isset($_POST["dWDx3Comments"]) &&
        isset($_POST["dWCx3"]) &&
        isset($_POST["dWCx3Comments"]) &&
        isset($_POST["pCx3"]) &&
        isset($_POST["pCx3Comments"]) &&
        isset($_POST["pClose"]) &&
        isset($_POST["pCloseComments"]) &&
        isset($_POST["dClose"]) &&
        isset($_POST["dCloseComments"]) &&
        isset($_POST["aty1"]) &&
        isset($_POST["aty2"]) &&
        isset($_POST["aty3"]) &&
        isset($_POST["aty4"]) &&
        isset($_POST["wit1"]) &&
        isset($_POST["wit2"]) &&
        isset($_POST["wit3"]) &&
        isset($_POST["wit4"]) &&
        isset($_POST["url"])
) {
    $ballot["pOpen"] = htmlspecialchars(strip_tags($_POST["pOpen"]));
    $ballot["pOpenComments"] = htmlspecialchars(strip_tags($_POST["pOpenComments"]));
    $ballot["dOpen"] = htmlspecialchars(strip_tags($_POST["dOpen"]));
    $ballot["dOpenComments"] = htmlspecialchars(strip_tags($_POST["dOpenComments"]));
    $ballot["pDx1"] = htmlspecialchars(strip_tags($_POST["pDx1"]));
    $ballot["pDx1Comments"] = htmlspecialchars(strip_tags($_POST["pDx1Comments"]));
    $ballot["pWDx1"] = htmlspecialchars(strip_tags($_POST["pWDx1"]));
    $ballot["pWDx1Comments"] = htmlspecialchars(strip_tags($_POST["pWDx1Comments"]));
    $ballot["pWCx1"] = htmlspecialchars(strip_tags($_POST["pWCx1"]));
    $ballot["pWCx1Comments"] = htmlspecialchars(strip_tags($_POST["pWCx1Comments"]));
    $ballot["dCx1"] = htmlspecialchars(strip_tags($_POST["dCx1"]));
    $ballot["dCx1Comments"] = htmlspecialchars(strip_tags($_POST["dCx1Comments"]));
    $ballot["pDx2"] = htmlspecialchars(strip_tags($_POST["pDx2"]));
    $ballot["pDx2Comments"] = htmlspecialchars(strip_tags($_POST["pDx2Comments"]));
    $ballot["pWDx2"] = htmlspecialchars(strip_tags($_POST["pWDx2"]));
    $ballot["pWDx2Comments"] = htmlspecialchars(strip_tags($_POST["pWDx2Comments"]));
    $ballot["pWCx2"] = htmlspecialchars(strip_tags($_POST["pWCx2"]));
    $ballot["pWCx2Comments"] = htmlspecialchars(strip_tags($_POST["pWCx2Comments"]));
    $ballot["dCx2"] = htmlspecialchars(strip_tags($_POST["dCx2"]));
    $ballot["dCx2Comments"] = htmlspecialchars(strip_tags($_POST["dCx2Comments"]));
    $ballot["pDx3"] = htmlspecialchars(strip_tags($_POST["pDx3"]));
    $ballot["pDx3Comments"] = htmlspecialchars(strip_tags($_POST["pDx3Comments"]));
    $ballot["pWDx3"] = htmlspecialchars(strip_tags($_POST["pWDx3"]));
    $ballot["pWDx3Comments"] = htmlspecialchars(strip_tags($_POST["pWDx3Comments"]));
    $ballot["pWCx3"] = htmlspecialchars(strip_tags($_POST["pWCx3"]));
    $ballot["pWCx3Comments"] = htmlspecialchars(strip_tags($_POST["pWCx3Comments"]));
    $ballot["dCx3"] = htmlspecialchars(strip_tags($_POST["dCx3"]));
    $ballot["dCx3Comments"] = htmlspecialchars(strip_tags($_POST["dCx3Comments"]));
    $ballot["dDx1"] = htmlspecialchars(strip_tags($_POST["dDx1"]));
    $ballot["dDx1Comments"] = htmlspecialchars(strip_tags($_POST["dDx1Comments"]));
    $ballot["dWDx1"] = htmlspecialchars(strip_tags($_POST["dWDx1"]));
    $ballot["dWDx1Comments"] = htmlspecialchars(strip_tags($_POST["dWDx1Comments"]));
    $ballot["dWCx1"] = htmlspecialchars(strip_tags($_POST["dWCx1"]));
    $ballot["dWCx1Comments"] = htmlspecialchars(strip_tags($_POST["dWCx1Comments"]));
    $ballot["pCx1"] = htmlspecialchars(strip_tags($_POST["pCx1"]));
    $ballot["pCx1Comments"] = htmlspecialchars(strip_tags($_POST["pCx1Comments"]));
    $ballot["dDx2"] = htmlspecialchars(strip_tags($_POST["dDx2"]));
    $ballot["dDx2Comments"] = htmlspecialchars(strip_tags($_POST["dDx2Comments"]));
    $ballot["dWDx2"] = htmlspecialchars(strip_tags($_POST["dWDx2"]));
    $ballot["dWDx2Comments"] = htmlspecialchars(strip_tags($_POST["dWDx2Comments"]));
    $ballot["dWCx2"] = htmlspecialchars(strip_tags($_POST["dWCx2"]));
    $ballot["dWCx2Comments"] = htmlspecialchars(strip_tags($_POST["dWCx2Comments"]));
    $ballot["pCx2"] = htmlspecialchars(strip_tags($_POST["pCx2"]));
    $ballot["pCx2Comments"] = htmlspecialchars(strip_tags($_POST["pCx2Comments"]));
    $ballot["dDx3"] = htmlspecialchars(strip_tags($_POST["dDx3"]));
    $ballot["dDx3Comments"] = htmlspecialchars(strip_tags($_POST["dDx3Comments"]));
    $ballot["dWDx3"] = htmlspecialchars(strip_tags($_POST["dWDx3"]));
    $ballot["dWDx3Comments"] = htmlspecialchars(strip_tags($_POST["dWDx3Comments"]));
    $ballot["dWCx3"] = htmlspecialchars(strip_tags($_POST["dWCx3"]));
    $ballot["dWCx3Comments"] = htmlspecialchars(strip_tags($_POST["dWCx3Comments"]));
    $ballot["pCx3"] = htmlspecialchars(strip_tags($_POST["pCx3"]));
    $ballot["pCx3Comments"] = htmlspecialchars(strip_tags($_POST["pCx3Comments"]));
    $ballot["pClose"] = htmlspecialchars(strip_tags($_POST["pClose"]));
    $ballot["pCloseComments"] = htmlspecialchars(strip_tags($_POST["pCloseComments"]));
    $ballot["dClose"] = htmlspecialchars(strip_tags($_POST["dClose"]));
    $ballot["dCloseComments"] = htmlspecialchars(strip_tags($_POST["dCloseComments"]));
    $ballot["aty1"] = htmlspecialchars(strip_tags($_POST["aty1"]));
    $ballot["aty2"] = htmlspecialchars(strip_tags($_POST["aty2"]));
    $ballot["aty3"] = htmlspecialchars(strip_tags($_POST["aty3"]));
    $ballot["aty4"] = htmlspecialchars(strip_tags($_POST["aty4"]));
    $ballot["wit1"] = htmlspecialchars(strip_tags($_POST["wit1"]));
    $ballot["wit2"] = htmlspecialchars(strip_tags($_POST["wit2"]));
    $ballot["wit3"] = htmlspecialchars(strip_tags($_POST["wit3"]));
    $ballot["wit4"] = htmlspecialchars(strip_tags($_POST["wit4"]));
    $ballot["url"] = htmlspecialchars(strip_tags($_POST["url"]));

    if (lockBallot($ballot)) {
        // set response code - 201 created
        http_response_code(201);

        // tell the user
        echo json_encode(array("message" => 0));
    } else {

        // set response code - 503 service unavailable
        http_response_code(503);

        // tell the user
        echo json_encode(array("message" => "Unable to create impermissible."));
    }
} else {

    // set response code - 400 bad request
    http_response_code(400);

    // tell the user
    echo json_encode(array("message" => "Unable to update ballot. Data is incomplete."));
}
