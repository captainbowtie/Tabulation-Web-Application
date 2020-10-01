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

/**
 * Description of impermissible
 *
 * @author allen
 */
require_once __DIR__ . "/../config.php";
require_once SITE_ROOT . "/database.php";

class Settings {
    
}

function setSetting($setting, $value) {
    $db = new Database();
    $conn = $db->getConnection();

    $settingUpdated = false;

    //update setting
    $query = "UPDATE settings SET $setting = $value";
    $conn->query($query);
    if ($conn->affected_rows == 1) {
        $settingUpdated = true;
    }
    $conn->close();
    return $settingUpdated;
}

function setAllSettings($settings) {
    $db = new Database();
    $conn = $db->getConnection();

    $settingsUpdated = false;

    //update setting
    $query = "UPDATE settings SET judgesPerRound = ".$settings["judgesPerRound"].", "
            . "lowerTeamIsHigherRank = ".$settings["lowerTeamIsHigherRank"].", "
            . "snakeStartsOnPlaintiff = ".$settings["snakeStartsOnPlaintiff"];
    $conn->query($query);
    if ($conn->affected_rows == 1) {
        $settingsUpdated = true;
    }
    $conn->close();
    return $settingsUpdated;
}

function getSetting($setting) {
    $query = "SELECT $setting FROM settings";
    $db = new Database();
    $conn = $db->getConnection();

    //get setting
    if ($result = $conn->query($query)) {
        $value = intval($row["$setting"]);
        /* free result set */
        $result->close();
        $conn->close();
        return $value;
    }
}

function getAllSettings() {
    $query = "SELECT * FROM settings";
    $db = new Database();
    $conn = $db->getConnection();
    if ($result = $conn->query($query)) {
        $row = $result->fetch_assoc();
        $settings["judgesPerRound"] = intval($row["judgesPerRound"]);
        $settings["lowerTeamIsHigherRank"] = intval($row["lowerTeamIsHigherRank"]);
        $settings["snakeStartsOnPlaintiff"] = intval($row["snakeStartsOnPlaintiff"]);

        /* free result set */
        $result->close();
    }
    $conn->close();

    return $settings;
}
