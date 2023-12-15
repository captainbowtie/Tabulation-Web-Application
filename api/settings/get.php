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
    require_once SITE_ROOT . '/objects/settings.php';

    $data = json_decode(file_get_contents("php://input"));

    if (
            isset($data->setting)
    ) {
        $setting = htmlspecialchars(strip_tags($data->setting));
        echo getSetting($setting);
    }
} else {
    die("Access denied.");
}