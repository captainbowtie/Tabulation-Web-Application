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
$("#submit").on("click", function (e) {
    e.preventDefault();
    let roles = {"pOpen": $("#pOpen").val()}
    roles.dOpen = $("#dOpen").val();
    roles.pDx1 = $("#pDx1").val();
    roles.pDx2 = $("#pDx2").val();
    roles.pDx3 = $("#pDx3").val();
    roles.pWDx1 = $("#pWDx1").val();
    roles.pWDx2 = $("#pWDx2").val();
    roles.pWDx3 = $("#pWDx3").val();
    roles.pCx1 = $("#pCx1").val();
    roles.pCx2 = $("#pCx2").val();
    roles.pCx3 = $("#pCx3").val();
    roles.dDx1 = $("#dDx1").val();
    roles.dDx2 = $("#dDx2").val();
    roles.dDx3 = $("#dDx3").val();
    roles.dWDx1 = $("#dWDx1").val();
    roles.dWDx2 = $("#dWDx2").val();
    roles.dWDx3 = $("#dWDx3").val();
    roles.dCx1 = $("#dCx1").val();
    roles.dCx2 = $("#dCx2").val();
    roles.dCx3 = $("#dCx3").val();
    roles.pClose = $("#pClose").val();
    roles.dClose = $("#dClose").val();
    roles.wit1 = $("#wit1").val();
    roles.wit2 = $("#wit2").val();
    roles.wit3 = $("#wit3").val();
    roles.wit4 = $("#wit4").val();
    roles.wit5 = $("#wit5").val();
    roles.wit6 = $("#wit6").val();
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

