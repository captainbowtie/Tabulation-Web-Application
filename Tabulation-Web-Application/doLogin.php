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
require_once __DIR__ . "/config.php";
require_once SITE_ROOT . "/database.php";
require_once SITE_ROOT . "/objects/user.php";
session_start();

$data = json_decode(file_get_contents("php://input"));

if (
        isset($data->email) &&
        isset($data->password)
) {
    $email = htmlspecialchars(strip_tags($data->email));
    $password = htmlspecialchars(strip_tags($data->password));
    $user = getUserByEmail($email);
    if ($user["email"] === $email &&
            password_verify($password, $user["password"])) {
        $_SESSION["id"] = $user["id"];
        $_SESSION["user"] = $email;
        $_SESSION["isAdmin"] = $user["isAdmin"];
        $_SESSION["isCoach"] = $user["isCoach"];
        // set response code - 200 ok
        http_response_code(200);
        //tell the user
        echo json_encode(array("message" => 0));
    } else {
        // tell the user
        echo json_encode(array("message" => "Unable to login. Incorrect information."));
    }
} else if (
        isset($_GET['url'])
) {
    $url = htmlspecialchars($_GET['url']);
    $user = getUserByURL($url);
    $_SESSION["id"] = $user["id"];
        $_SESSION["user"] = $user["email"];
        $_SESSION["isAdmin"] = $user["isAdmin"];
        $_SESSION["isCoach"] = $user["isCoach"];
        header("Location: index.php");
} else {
// tell the user
    echo json_encode(array("message" => "Unable to login. Data is incomplete."));
}
