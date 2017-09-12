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

$(".existingUserName").on("change", function () {
    var postString = '{"id":' + $(this).attr('competitor') + ',"field":"' + "name" + '","value":"' + this.value + '"}';
    console.log(postString);
    var postData = JSON.parse(postString);
    $.ajax({

        // The URL for the request
        url: "/postCompetitor.php",

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

$("#addCompetitor").on("submit", function (e) {
    e.preventDefault();
    var competitor = $("#addCompetitor").serialize();
    competitor += "&team=" + $("#teamSelect option:selected").attr("id");
    $.ajax({

        // The URL for the request
        url: "/postCompetitor.php",

        // The data to send (will be converted to a query string)
        data: competitor,

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
