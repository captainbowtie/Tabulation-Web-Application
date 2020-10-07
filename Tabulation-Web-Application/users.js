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

var passwordId;
var coaches =[];
var coachUser;

$(document).ready(function () {
    updateCoaches();
});

$(".email").on("change", function () {
    let editRow = $(this).closest("tr");
    let userId = (editRow[0].id.substring(4));
    updateUser(userId, "email", $(this).val());
});

$(".isAdmin").on("change", function () {
    let editRow = $(this).closest("tr");
    let userId = (editRow[0].id.substring(4));
    updateUser(userId, "isAdmin", $(this).prop("checked"));
});

$(".isCoach").on("change", function () {
    let editRow = $(this).closest("tr");
    let userId = (editRow[0].id.substring(4));
    updateUser(userId, "isCoach", $(this).prop("checked"));
});

$(".passwordButton").on("click", function () {
    let editRow = $(this).closest("tr");
    passwordId = (editRow[0].id.substring(4));
    passwordModal();
});

$("#changePassword").on("click", function () {
    let password = $("#newPassword").val();
    updateUser(passwordId, "password", password);
});

$(".teamsButton").on("click", function () {
    coachUser = parseInt($(this).attr("data-user"));
    fillTeamsModal(coachUser);
    $("#teamsModal").modal();
});

$(".teamCheckbox").on("click", function () {
    let teamNumber = parseInt($(this).attr("data-number"));
    let coachData = `{
    "user":${coachUser},
    "team":${teamNumber}
    }`;
    if ($(this).prop("checked")) {
        $.ajax({
            url: "../api/coaches/create.php",
            method: "POST",
            data: coachData,
            dataType: "json"
        }).then(response => {
            if (response.message != 0) {
                warningModal(response.message);
            }
            updateCoaches();
        });
    } else {
        $.ajax({
            url: "../api/coaches/delete.php",
            method: "POST",
            data: coachData,
            dataType: "json"
        }).then(response => {
            if (response.message != 0) {
                warningModal(response.message);
            }
            updateCoaches();
        });
    }
});

$("#newUser").on("click", function () {
    $("#email").val("");
    $("#password").val("");
    $("#isAdmin").prop("checked", false);
    $("#isCoach").prop("checked", false);
    $("#newUserModal").modal();
});

$("#addUser").on("click", function () {
    let userData = `{
    "email":"${$("#email").val()}",
    "password":"${$("#password").val()}",
    "isAdmin":"${$("#isAdmin").prop("checked")}",
    "isCoach":"${$("#isCoach").prop("checked")}"
    }`;
    $.ajax({
        url: "../api/users/create.php",
        method: "POST",
        data: userData,
        dataType: "json"
    }).then(response => {
        if (response.message === 0) {
            window.location.reload();
        } else {
            warningModal(response.message);
        }
    });
});

function fillTeamsModal(userID) {
    //start by unchecking all checkboxes
    $(".teamCheckbox").prop("checked", false);

    //then check the ones with conflicts
    for (var a = 0; a < coaches.length; a++) {
        if (coaches[a].user === userID) {
            $(`#${coaches[a].team}checkbox`).prop("checked", true);
        }
    }
}

function passwordModal() {
    $("#newPassword").val("");
    $("#passwordModal").modal();
}

function updateUser(id, field, value) {
    let updateData = `{
    "id":${id},
    "field":"${field}",
    "value":"${value}"
    }`;
    $.ajax({
        url: "../api/users/update.php",
        method: "POST",
        data: updateData,
        dataType: "json"
    }).then(response => {
        if (response.message != 0) {
            warningModal(response.message);
        }
    });
}

function warningModal(warning) {
    alert(warning);
}

function updateCoaches() {
    $.ajax({
        url: "../api/coaches/getAll.php",
        dataType: "json"
    }).then(data => {
        coaches = data;
    });
}

