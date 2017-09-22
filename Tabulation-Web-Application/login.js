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

$("#login").on("submit",function(e){
    e.preventDefault();
    $.ajax({

        // The URL for the request
        url: "/postLogin.php",

        // The data to send (will be converted to a query string)
        data: $("#login").serializeArray(),

        // Whether this is a POST or GET request
        type: "POST",

        // The type of data we expect back
        dataType: "text",
    })
            // Code to run if the request succeeds (is done);
            // The response is passed to the function
            .done(function (response) {
                if(response==0){
                    window.location = "/index.php";
                }else{
                    var alertHTML = "<span>Incorrect email or password</span>";
                    $("#login").append(alertHTML);
                }
            })
   

})
