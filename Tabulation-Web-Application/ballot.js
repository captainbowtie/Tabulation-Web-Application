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
    var judgeId = $("#judge").attr('judgeId');
    var round = $("#round").text();
    var role = this.name;
    var score = this.value;
    var postString = '{"judgeId":' + judgeId + ',"round":' + round + ',"role":"' + role + '","score":' + score + '}';
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
    console.log(this.name + " " + this.value);
});

$("select").on("change", function () {
    var judgeId = $("#judge").attr('judgeId');
    var round = $("#round").text();
    var rankSlot = this.id;
    var competitor = this.value;
    var postString = '{"judgeId":' + judgeId + ',"round":' + round + ',"field":"' + rankSlot + '","value":' + competitor + '}';
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
    console.log(this.name + " " + this.value);
});