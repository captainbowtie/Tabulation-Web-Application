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
        !empty($data->round) &&
        !empty($data->plaintiff) &&
        !empty($data->defense)
) {
    $round = htmlspecialchars(strip_tags($data->round));
    $plaintiff = htmlspecialchars(strip_tags($data->plaintiff));
    $defense = htmlspecialchars(strip_tags($data->defense));
    if(createPairing($round, $plaintiff, $defense)){
        // set response code - 201 created
        http_response_code(201);

        // tell the user
        echo json_encode(array("message" => "Pairing was created."));
    }else {

        // set response code - 503 service unavailable
        http_response_code(503);

        // tell the user
        echo json_encode(array("message" => "Unable to create pairing."));
    }
    
    
}
else {

    // set response code - 400 bad request
    http_response_code(400);

    // tell the user
    echo json_encode(array("message" => "Unable to create pairing. Data is incomplete."));
}