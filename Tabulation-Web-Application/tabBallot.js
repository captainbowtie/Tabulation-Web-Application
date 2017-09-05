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
    var id = $("#pairing option:selected").attr('value');
    var getString = '{"id":' + id + '}';
    $.ajax({

        // The URL for the request
        url: "/getBallot.php",

        // The data to send (will be converted to a query string)
        data: JSON.parse(getString),

        // Whether this is a POST or GET request
        type: "GET",

        // The type of data we expect back
        dataType: "json",
    })
            // Code to run if the request succeeds (is done);
            // The response is passed to the function
            .done(function (response) {
                $("#pOpen").val(response.pOpen);
                $("#pDirect1").val(response.pDirect1);
                $("#pWitDirect1").val(response.pWitDirect1);
                $("#pWitCross1").val(response.pWitCross1);
                $("#pDirect2").val(response.pDirect2);
                $("#pWitDirect2").val(response.pWitDirect2);
                $("#pWitCross2").val(response.pWitCross2);
                $("#pDirect3").val(response.pDirect3);
                $("#pWitDirect3").val(response.pWitDirect3);
                $("#pWitCross3").val(response.pWitCross3);
                $("#pCross1").val(response.pCross1);
                $("#pCross2").val(response.pCross2);
                $("#pCross3").val(response.pCross3);
                $("#pClose").val(response.pClose);
                $("#dOpen").val(response.dOpen);
                $("#dDirect1").val(response.dDirect1);
                $("#dWitDirect1").val(response.dWitDirect1);
                $("#dWitCross1").val(response.dWitCross1);
                $("#dDirect2").val(response.dDirect2);
                $("#dWitDirect2").val(response.dWitDirect2);
                $("#dWitCross2").val(response.dWitCross2);
                $("#dDirect3").val(response.dDirect3);
                $("#dWitDirect3").val(response.dWitDirect3);
                $("#dWitCross3").val(response.dWitCross3);
                $("#dCross1").val(response.dCross1);
                $("#dCross2").val(response.dCross2);
                $("#dCross3").val(response.dCross3);
                $("#dClose").val(response.dClose);
            })

});
