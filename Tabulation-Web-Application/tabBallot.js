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

var round1pairings = "";
var round2pairings = "";
var round3pairings = "";
var round4pairings = "";

$(document).ready(function () {
    //Edit pairing list to only have current round pairings
    var select = $("#pairing option");
    for (a = 0; a < select.length; a++) {
        var id = $(select[a]).attr('value');
        var text = $(select[a]).text();
        if ($(select[a]).attr("round") == 1) {
            if(round1pairings==""){
                round1pairings += "<option id='" + id + "' selected>" + text + "</option>\n";
            }else{
                round1pairings += "<option id='" + id + "'>" + text + "</option>\n";
            }
        } else if ($(select[a]).attr("round") == 2) {
            if(round2pairings==""){
                round2pairings += "<option id='" + id + "' selected>" + text + "</option>\n";
            }else{
                round2pairings += "<option id='" + id + "'>" + text + "</option>\n";
            }
        } else if ($(select[a]).attr("round") == 3) {
            if(round3pairings==""){
                round3pairings += "<option id='" + id + "' selected>" + text + "</option>\n";
            }else{
                round3pairings += "<option id='" + id + "'>" + text + "</option>\n";
            }
        } else if ($(select[a]).attr("round") == 4) {
            if(round4pairings==""){
                round4pairings += "<option id='" + id + "' selected>" + text + "</option>\n";
            }else{
                round4pairings += "<option id='" + id + "'>" + text + "</option>\n";
            }
        }
    }
    var currentRound = $("#round option:selected").attr("value");
    if (currentRound == "round1") {
        $("#pairing").empty().append(round1pairings);
    } else if (currentRound == "round2") {
        $("#pairing").empty().append(round2pairings);
    } else if (currentRound == "round3") {
        $("#pairing").empty().append(round3pairings);
    } else if (currentRound == "round4") {
        $("#pairing").empty().append(round4pairings);
    }
    populateBallot();
});

$(".rankSelect").on("change", function () {
    var ballotId = $("#pairing option:selected").attr('value');
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

$("input").on("change", function () {
    var ballotId = $("#pairing option:selected").attr('value');
    var role = this.name;
    var score = this.value;
    var postString = '{"id":' + ballotId + ',"field":"' + role + '","value":' + score + '}';
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
    var ballotId = $("#pairing option:selected").attr('value');
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

$("#pairing").on("change", function () {
    populateBallot();
});

$("#round").on("change", function () {
    var currentRound = $("#round option:selected").attr("value");
    if (currentRound == "round1") {
        $("#pairing").empty().append(round1pairings);
    } else if (currentRound == "round2") {
        $("#pairing").empty().append(round2pairings);
    } else if (currentRound == "round3") {
        $("#pairing").empty().append(round3pairings);
    } else if (currentRound == "round4") {
        $("#pairing").empty().append(round4pairings);
    }
    populateBallot();
});

function populateBallot() {
    var ballotId = $("#pairing option:selected").attr('id');
    var getString = '{"id":' + ballotId + '}';
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
                //Create competitor option list
                var competitorOptions = "<option value='0'>N/A</option>\n";
                for (var key in response.competitors) {
                    if (response.competitors.hasOwnProperty(key)) {
                        competitorOptions += "<option value='" + key + "'>" + response.competitors[key] + "</option>\n";
                    }
                }
                //Set selected value on each competitor option list
                var attyRank1 = competitorOptions.replace("'" + response.attyRank1 + "'", "'" + response.attyRank1 + "' selected");
                var attyRank2 = competitorOptions.replace("'" + response.attyRank2 + "'", "'" + response.attyRank2 + "' selected");
                var attyRank3 = competitorOptions.replace("'" + response.attyRank3 + "'", "'" + response.attyRank3 + "' selected");
                var attyRank4 = competitorOptions.replace("'" + response.attyRank4 + "'", "'" + response.attyRank4 + "' selected");
                var attyRank5 = competitorOptions.replace("'" + response.attyRank5 + "'", "'" + response.attyRank5 + "' selected");
                var attyRank6 = competitorOptions.replace("'" + response.attyRank6 + "'", "'" + response.attyRank6 + "' selected");
                var witRank1 = competitorOptions.replace("'" + response.witRank1 + "'", "'" + response.witRank1 + "' selected");
                var witRank2 = competitorOptions.replace("'" + response.witRank2 + "'", "'" + response.witRank2 + "' selected");
                var witRank3 = competitorOptions.replace("'" + response.witRank3 + "'", "'" + response.witRank3 + "' selected");
                var witRank4 = competitorOptions.replace("'" + response.witRank4 + "'", "'" + response.witRank4 + "' selected");
                var witRank5 = competitorOptions.replace("'" + response.witRank5 + "'", "'" + response.witRank5 + "' selected");
                var witRank6 = competitorOptions.replace("'" + response.witRank6 + "'", "'" + response.witRank6 + "' selected");


                //Replace values
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
                $("#attyRank1").empty().append(attyRank1);
                $("#attyRank2").empty().append(attyRank2);
                $("#attyRank3").empty().append(attyRank3);
                $("#attyRank4").empty().append(attyRank4);
                $("#attyRank5").empty().append(attyRank5);
                $("#attyRank6").empty().append(attyRank6);
                $("#witRank1").empty().append(witRank1);
                $("#witRank2").empty().append(witRank2);
                $("#witRank3").empty().append(witRank3);
                $("#witRank4").empty().append(witRank4);
                $("#witRank5").empty().append(witRank5);
                $("#witRank6").empty().append(witRank6);
            })
}