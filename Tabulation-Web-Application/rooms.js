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

$(document).ready(function () {
    populateRooms();
});

$(document).on("submit", "#roomForm", function (e) {
    e.preventDefault();
    //Check which rounds room is available
    var round1 = 0;
    var round2 = 0;
    var round3 = 0;
    var round4 = 0;
    if ($("#round1").prop("checked")) {
        round1 = 1;
    }
    if ($("#round2").prop("checked")) {
        round2 = 1;
    }
    if ($("#round3").prop("checked")) {
        round3 = 1;
    }
    if ($("#round4").prop("checked")) {
        round4 = 1;
    }

    //Create string to post
    var postString = '{"building":"' + $("#newBuilding").val() +
            '","number":"' + $("#newNumber").val() +
            '","round1":' + round1 +
            ',"round2":' + round2 +
            ',"round3":' + round3 +
            ',"round4":' + round4 +
            ',"roomQuality":' + $("#quality option:selected").val() + '}';

    //Send room data to server
    $.ajax({

        // The URL for the request
        url: "/postRoom.php",

        // The data to send (will be converted to a query string)
        data: JSON.parse(postString),

        // Whether this is a POST or GET request
        type: "POST",

        // The type of data we expect back
        dataType: "text",
    })
            // Code to run if the request succeeds (is done);
            // The response is passed to the function
            .done(function (response) {
                populateRooms();
                $('#roomForm').trigger("reset");
            })
});

$(document).on("change", ".existing", function () {
    var room = $(this).attr("room");
    var field = $(this).attr("field");
    var value;
    switch (field){
        case "building":
            break;
            case "number":
            break;
            case "round1":
            break;
            case "round2":
            break;
            case "round3":
            break;
            case "round4":
            break;
            case "roomQuality":
            break;
    }
});

function populateRooms(){
    $("#existingRooms").empty();
    $.ajax({

        // The URL for the request
        url: "/getRooms.php",

        // Whether this is a POST or GET request
        type: "GET",

        // The type of data we expect back
        dataType: "html",
    })
            // Code to run if the request succeeds (is done);
            // The response is passed to the function
            .done(function (table) {
                $('#existingRooms').append(table);
            })
}
