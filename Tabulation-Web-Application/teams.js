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

var existingNumber;

$("#addTeam").on("click", function (e) {
    e.preventDefault();
    newTeamHandler();
});

$(".edit").on("click", function (e) {
    e.preventDefault();
    existingNumber = $(this).attr("id").substring(4);
    let name = $("#" + existingNumber + "name").html();
    teamUpdateModal(name);
});

$("#updateTeam").on("click", function (e) {
    e.preventDefault();
    updateTeamHandler();
});

function newTeamHandler() {
    let number = $("#number").val();
    let name = $("#name").val();
    if (!validateNumber(number)) {
        warningModal("Invalid team number: " + number);
    } else if (!validateName(name)) {
        warningModal("Please enter a team name");
    } else {
        createTeam(number, name);
    }
}

function updateTeamHandler() {
    let newNumber = $("#updateNumber").val();
    let name = $("#updateName").val();
    if (!validateNumber(newNumber)) {
        warningModal("Invalid team number: " + newNumber);
    } else if (!validateName(name)) {
        warningModal("Please enter a team name");
    } else {
        updateTeam(newNumber, name);
    }
}

function createTeam(number, name) {
    let teamData = '{"number":' + number + ',"name":"' + name + '"}';
    console.log(teamData);
    $.ajax({
        url: "../api/teams/create.php",
        method: "POST",
        data: teamData,
        dataType: "json"
    }).then(response => {
        if (response.message === 0) {
            window.location.reload();
        } else {
            warningModal(response.message);
        }
    });
}

function updateTeam(newNumber, name) {
    let teamData = '{"existingNumber":' + existingNumber +
            ',"newNumber":' + newNumber +
            ',"name":"' + name + '"}';
    $.ajax({
        url: "../api/teams/update.php",
        method: "POST",
        data: teamData,
        dataType: "json"
    }).then(response => {
        if (response.message === 0) {
            window.location.reload();
        } else {
            warningModal(response.message);
        }
    });
}

function warningModal(warning) {
    $("#warningModalText").text(warning);
    $("#warningModal").modal();
}

function teamUpdateModal(name) {
    $("#updateNumber").val(existingNumber);
    $("#updateName").val(name);
    $("#teamUpdateModal").modal();
}

function validateNumber(number) {
    return /^-{0,1}\d+$/.test(number);
}

function validateName(name) {
    if (name.length > 0) {
        return true;
    } else {
        return false;
    }
}