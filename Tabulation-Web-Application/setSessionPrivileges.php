<?php

/* 
 * Copyright (C) 2017 allen
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

require_once 'dblogin.php';

$isTab = FALSE;
$isJudge = FALSE;
$isCoach = FALSE;
$id = $_SESSION['id'];

if (isset($_SESSION['id'])) {
    $id = $_SESSION['id'];
    $connection = new mysqli(dbhost, dbuser, dbpass, dbname);
    $userQuery = "SELECT isTab,isJudge,isCoach FROM users WHERE id=$id";
    $userResult = $connection->query($userQuery);
    $userResult->data_seek(0);
    $roleArray = $userResult->fetch_array(MYSQLI_ASSOC);
    $userResult->close();
    if($roleArray['isTab']==1){
        $isTab=TRUE;
    }
    if($roleArray['isJudge']==1){
        $isJudge=TRUE;
    }
    if($roleArray['isCoach']==1){
        $isCoach=TRUE;
    }
}