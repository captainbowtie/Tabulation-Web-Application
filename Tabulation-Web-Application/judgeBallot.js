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

$("input").on("change", function () {
    var ballotId = $("#judge").attr('ballotId');
    var role = this.name;
    var score = this.value;
    var postString = '{"id":' + ballotId + ',"field":' + role + '","value":' + score + '}';
    var postData = JSON.parse(postString);

    $.ajax({

        // The URL for the request
        url: "/postBallot.php",

        // The data to send (will be converted to a query string)
        data: postData,

        // Whether this is a POST or GET request
        type: "POST",

        // The type of data we expect back
        dataType: "text",
    })
            // Code to run if the request succeeds (is done);
            // The response is passed to the function
            .done(function (response) {

            })
});

$(".rankSelect").on("change", function () {
    var ballotId = $("#judge").attr('ballotId');
    var rankSlot = this.id;
    var competitor = this.value;
    var postString = '{"id":' + ballotId + ',"field":"' + rankSlot + '","value":' + competitor + '}';
    var postData = JSON.parse(postString);

    $.ajax({

        // The URL for the request
        url: "/postBallot.php",

        // The data to send (will be converted to a query string)
        data: postData,

        // Whether this is a POST or GET request
        type: "POST",

        // The type of data we expect back
        dataType: "text",
    })
            // Code to run if the request succeeds (is done);
            // The response is passed to the function
            .done(function (response) {

            })
});

$('#ballot').submit(function (e) {
    e.preventDefault();
    var ballotId = $("#judge").attr('ballotId');
    var ballot = $("#ballot").serializeArray();
    ballot[28] = {name: "attyRank1",value: $("#attyRank1 option:selected").attr("value")};
    ballot[29] = {name: "attyRank2",value: $("#attyRank2 option:selected").attr("value")};
    ballot[30] = {name: "attyRank3",value: $("#attyRank3 option:selected").attr("value")};
    ballot[31] = {name: "attyRank4",value: $("#attyRank4 option:selected").attr("value")};
    ballot[32] = {name: "attyRank5",value: $("#attyRank5 option:selected").attr("value")};
    ballot[33] = {name: "attyRank6",value: $("#attyRank6 option:selected").attr("value")};
    ballot[34] = {name: "witRank1",value: $("#witRank1 option:selected").attr("value")};
    ballot[35] = {name: "witRank2",value: $("#witRank2 option:selected").attr("value")};
    ballot[36] = {name: "witRank3",value: $("#witRank3 option:selected").attr("value")};
    ballot[37] = {name: "witRank4",value: $("#witRank4 option:selected").attr("value")};
    ballot[38] = {name: "witRank5",value: $("#witRank5 option:selected").attr("value")};
    ballot[39] = {name: "witRank6",value: $("#witRank6 option:selected").attr("value")};
    ballot[40] = {name: "id", value: ballotId};
    ballot[41] = {name: "finalized",value : "yes"};


    $.ajax({

        // The URL for the request
        url: "/postBallot.php",

        // The data to send (will be converted to a query string)
        data: ballot,

        // Whether this is a POST or GET request
        type: "POST",

        // The type of data we expect back
        dataType: "text",
    })
            // Code to run if the request succeeds (is done);
            // The response is passed to the function
            .done(function (response) {

            })

});