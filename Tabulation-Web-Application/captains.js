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

$("document").ready(function () {
    $(".pInput").prop("disabled", true);
    $(".dInput").prop("disabled", true);
    $("button").prop("disabled", true);
    $("#pRadio").prop("disabled", false);
    $("#dRadio").prop("disabled", false);
});

$("#pRadio").on("click", function () {
    $(".pInput").prop("disabled", false);
    $(".dInput").prop("disabled", true);
    $("button").prop("disabled", false);
});

$("#dRadio").on("click", function () {
    $(".pInput").prop("disabled", true);
    $(".dInput").prop("disabled", false);
    $("button").prop("disabled", false);
});
$("#submit").on("click", function (e) {
    e.preventDefault();
    if ($("#pRadio").prop("checked")) {
        var roles = {"side": "plaintiff"};
        roles.pOpen = $("#pOpen").val().replace(/'/g, '’');
        roles.pDx1 = $("#pDx1").val().replace(/'/g, '’');
        roles.pDx2 = $("#pDx2").val().replace(/'/g, '’');
        roles.pDx3 = $("#pDx3").val().replace(/'/g, '’');
        roles.pWDx1 = $("#pWDx1").val().replace(/'/g, '’');
        roles.pWDx2 = $("#pWDx2").val().replace(/'/g, '’');
        roles.pWDx3 = $("#pWDx3").val().replace(/'/g, '’');
        roles.pCx1 = $("#pCx1").val().replace(/'/g, '’');
        roles.pCx2 = $("#pCx2").val().replace(/'/g, '’');
        roles.pCx3 = $("#pCx3").val().replace(/'/g, '’');
        roles.pClose = $("#pClose").val().replace(/'/g, '’');
        roles.wit1 = $("#wit1").val().replace(/'/g, '’');
        roles.wit2 = $("#wit2").val().replace(/'/g, '’');
        roles.wit3 = $("#wit3").val().replace(/'/g, '’');
    } else if ($("#dRadio").prop("checked")) {
        var roles = {"side": "defense"};
        roles.dOpen = $("#dOpen").val().replace(/'/g, '’');
        roles.dDx1 = $("#dDx1").val().replace(/'/g, '’');
        roles.dDx2 = $("#dDx2").val().replace(/'/g, '’');
        roles.dDx3 = $("#dDx3").val().replace(/'/g, '’');
        roles.dWDx1 = $("#dWDx1").val().replace(/'/g, '’');
        roles.dWDx2 = $("#dWDx2").val().replace(/'/g, '’');
        roles.dWDx3 = $("#dWDx3").val().replace(/'/g, '’');
        roles.dCx1 = $("#dCx1").val().replace(/'/g, '’');
        roles.dCx2 = $("#dCx2").val().replace(/'/g, '’');
        roles.dCx3 = $("#dCx3").val().replace(/'/g, '’');
        roles.dClose = $("#dClose").val().replace(/'/g, '’');
        roles.wit4 = $("#wit4").val().replace(/'/g, '’');
        roles.wit5 = $("#wit5").val().replace(/'/g, '’');
        roles.wit6 = $("#wit6").val().replace(/'/g, '’');
    } else {
        alert("Submission error. Neither side has been selected at top.");

    }
    roles.url = url;

    $.ajax({
        url: "../api/pairings/submitCaptains.php",
        method: "POST",
        data: roles,
        dataType: "json"
    }).then(response => {
        if (response.message == 0) {
            alert("Roles successfully submitted.");
        } else {
            alert(response.message);
        }
    });
});

