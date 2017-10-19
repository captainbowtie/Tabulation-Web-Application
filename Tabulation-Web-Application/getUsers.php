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
session_start();

require_once "dblogin.php";
require_once "setSessionPrivileges.php";

if (!isset($_SESSION['id'])) {
    echo "You must be logged in to view this page";
//Web page if logged in, but not as a coach or tabulation director
} else if (!$isTab) {
    echo "You do not have permission to access this page";
//Code if logged in as tabulation director
} else {
    echo "<table id='userTable' name='userTable'>\n";
    echo "<tr><td>Name</td><td>Email</td><td>Password</td><td>Tab</td><td>Coach</td><td>Judge</td>"
    . "<td>Round 1</td><td>Round 2</td><td>Round 3</td><td>Round 4</td><td>Quality</td><tr>\n";
    $connection = new mysqli(dbhost, dbuser, dbpass, dbname);
    if ($connection->connect_error) {
        die($connection->connect_error);
    }
    $userQuery = "SELECT * FROM users";
    $userResult = $connection->query($userQuery);
    $connection->close();
    for($a = 0;$a<$userResult->num_rows;$a++){
        $userResult->data_seek($a);
        $user = $userResult->fetch_array(MYSQLI_ASSOC);
        $id = $user['id'];
        $name = $user['name'];
        $email = $user['email'];
        $tab = $user['isTab'];
        $coach = $user['isCoach'];
        $judge = $user['isJudge'];
        $round1 = $user['canJudgeRound1'];
        $round2 = $user['canJudgeRound2'];
        $round3 = $user['canJudgeRound3'];
        $round4 = $user['canJudgeRound4'];
        $quality = $user['judgeQuality'];
        echo "<tr>";
        echo "<td><input class='existingUser' user='$id' field='name' value='$name'></td>\n";
        echo "<td><input class='existingUser' user='$id' field='email' value='$email'></td>\n";
        echo "<td><button>Reset Password</button></td>\n";
        echo "<td><input type=checkbox class='existingUser' user='$id' field='isTab'";
        if($tab==1){
            echo " checked";
        }
        echo "></td>\n";
        echo "<td><input type=checkbox class='existingUser' user='$id' field='isCoach'";
        if($coach==1){
            echo " checked";
        }
        echo "></td>\n";
        echo "<td><input type=checkbox class='existingUser' user='$id' field='isJudge'";
        if($judge==1){
            echo " checked";
        }
        echo "></td>\n";
        echo "<td><input type=checkbox id='round1$id' class='existingUser' user='$id' field='round1'";
        if($judge==0){
            echo " disabled";
        }
        if($round1==1){
            echo " checked";
        }
        echo "></td>\n";
        echo "<td><input type=checkbox id='round2$id' class='existingUser' user='$id' field='round2'";
        if($judge==0){
            echo " disabled";
        }
        if($round2==1){
            echo " checked";
        }
        echo "></td>\n";
        echo "<td><input type=checkbox id='round3$id' class='existingUser' user='$id' field='round3'";
        if($judge==0){
            echo " disabled";
        }
        if($round3==1){
            echo " checked";
        }
        echo "></td>\n";
        echo "<td><input type=checkbox id='round4$id' class='existingUser' user='$id' field='round4'";
        if($judge==0){
            echo " disabled";
        }
        if($round4==1){
            echo " checked";
        }
        echo "></td>\n";
        echo "<td><select id='judgeQuality$id' class='existingUser' user='$id' field='judgeQuality'";
        if($judge==0){
            echo " disabled";
        }
        echo ">\n";
        switch ($quality){
            case 1:
                echo "<option selected>1</option>\n<option>2</option>\n<option>3</option>\n";
                break;
            case 2:
                echo "<option>1</option>\n<option selected>2</option>\n<option>3</option>\n";
                break;
            case 3:
                echo "<option>1</option>\n<option>2</option>\n<option selected>3</option>\n";
                break;
            default:
                echo "<option>1</option>\n<option>2</option>\n<option>3</option>\n";
                break;
        }
        
        echo "</select></td></tr>\n";
    }
    echo "<tr>\n";
    echo "<td><input id='newUserName'></td>\n";
    echo "<td><input id='newUserEmail'></td>\n";
    echo "<td><input id='newUserPassword'></td>\n";
    echo "<td><input type=checkbox id='isTab'></td>\n";
    echo "<td><input type=checkbox id='isCoach'></td>\n";
    echo "<td><input type=checkbox id='isJudge'></td>\n";
    echo "<td><input type=checkbox id='round1' disabled></td>\n";
    echo "<td><input type=checkbox id='round2' disabled></td>\n";
    echo "<td><input type=checkbox id='round3' disabled ></td>\n";
    echo "<td><input type=checkbox id='round4' disabled></td>\n";
    echo "<td><select id='judgeQuality' disabled>\n";
    echo "<option>1</option>\n<option>2</option>\n<option>3</option>\n";
    echo "</select></td>\n";
    echo "<td><button id='addUser'>Add User</button></td>\n";
    echo "</tr>\n";
    echo "</table>\n";
    $userResult->close();
}
