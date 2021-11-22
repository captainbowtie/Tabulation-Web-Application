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
    require_once SITE_ROOT . '/objects/ballot.php';

    $data = json_decode(file_get_contents("php://input"));

    if (
            isset($data->id) &&
            isset($data->pOpen) &&
            isset($data->dOpen) &&
            isset($data->pDx1) &&
            isset($data->pDx2) &&
            isset($data->pDx3) &&
            isset($data->pWDx1) &&
            isset($data->pWDx2) &&
            isset($data->pWDx3) &&
            isset($data->pWCx1) &&
            isset($data->pWCx2) &&
            isset($data->pWCx3) &&
            isset($data->pCx1) &&
            isset($data->pCx2) &&
            isset($data->pCx3) &&
            isset($data->dDx1) &&
            isset($data->dDx2) &&
            isset($data->dDx3) &&
            isset($data->dWDx1) &&
            isset($data->dWDx2) &&
            isset($data->dWDx3) &&
            isset($data->dWCx1) &&
            isset($data->dWCx2) &&
            isset($data->dWCx3) &&
            isset($data->dCx1) &&
            isset($data->dCx2) &&
            isset($data->dCx3) &&
            isset($data->pClose) &&
            isset($data->dClose)
    ) {
        $ballot = [];
        $ballot["id"] = htmlspecialchars(strip_tags($data->id));
        $ballot["pOpen"] = htmlspecialchars(strip_tags($data->pOpen));
        $ballot["dOpen"] = htmlspecialchars(strip_tags($data->dOpen));
        $ballot["pDx1"] = htmlspecialchars(strip_tags($data->pDx1));
        $ballot["pDx2"] = htmlspecialchars(strip_tags($data->pDx2));
        $ballot["pDx3"] = htmlspecialchars(strip_tags($data->pDx3));
        $ballot["pWDx1"] = htmlspecialchars(strip_tags($data->pWDx1));
        $ballot["pWDx2"] = htmlspecialchars(strip_tags($data->pWDx2));
        $ballot["pWDx3"] = htmlspecialchars(strip_tags($data->pWDx3));
        $ballot["pWCx1"] = htmlspecialchars(strip_tags($data->pWCx1));
        $ballot["pWCx2"] = htmlspecialchars(strip_tags($data->pWCx2));
        $ballot["pWCx3"] = htmlspecialchars(strip_tags($data->pWCx3));
        $ballot["pCx1"] = htmlspecialchars(strip_tags($data->pCx1));
        $ballot["pCx2"] = htmlspecialchars(strip_tags($data->pCx2));
        $ballot["pCx3"] = htmlspecialchars(strip_tags($data->pCx3));
        $ballot["dDx1"] = htmlspecialchars(strip_tags($data->dDx1));
        $ballot["dDx2"] = htmlspecialchars(strip_tags($data->dDx2));
        $ballot["dDx3"] = htmlspecialchars(strip_tags($data->dDx3));
        $ballot["dWDx1"] = htmlspecialchars(strip_tags($data->dWDx1));
        $ballot["dWDx2"] = htmlspecialchars(strip_tags($data->dWDx2));
        $ballot["dWDx3"] = htmlspecialchars(strip_tags($data->dWDx3));
        $ballot["dWCx1"] = htmlspecialchars(strip_tags($data->dWCx1));
        $ballot["dWCx2"] = htmlspecialchars(strip_tags($data->dWCx2));
        $ballot["dWCx3"] = htmlspecialchars(strip_tags($data->dWCx3));
        $ballot["dCx1"] = htmlspecialchars(strip_tags($data->dCx1));
        $ballot["dCx2"] = htmlspecialchars(strip_tags($data->dCx2));
        $ballot["dCx3"] = htmlspecialchars(strip_tags($data->dCx3));
        $ballot["pClose"] = htmlspecialchars(strip_tags($data->pClose));
        $ballot["dClose"] = htmlspecialchars(strip_tags($data->dClose));
        if (updateBallot($ballot)) {
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
} else {
    http_response_code(401);
}
