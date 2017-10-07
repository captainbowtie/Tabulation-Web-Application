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
    switch (getCurrentRound()) {
        case 0:
            randomPairing();
            break;
        case 1:
            break;
        case 2:
            break;
        case 3:
            break;
        case 4:
            break;
    }
});

$("#pairingMethodSelect").on("change", function () {
    if ($("#pairingMethodSelect option:selected").attr("id") == "challenge") {
        challengePairing();
    } else if ($("#pairingMethodSelect option:selected").attr("id") == "random") {
        randomPairing();
    }
});

function randomPairing() {
    var getString = JSON.parse('{"roundNumber":' + getCurrentRound() + '}');
    $.ajax({

        // The URL for the request
        url: "/getPairings.php",

        // The data to send (will be converted to a query string)
        data: getString,

        // Whether this is a POST or GET request
        type: "GET",

        // The type of data we expect back
        dataType: "json",
    })
            // Code to run if the request succeeds (is done);
            // The response is passed to the function
            .done(function (pairingReturn) {
                setPairings(pairingReturn);

            })
}

function challengePairing() {
    //TODO: challenge pairing
}

function getCurrentRound() {
    var submitString = $("#sumbitPairingsButton").attr("value");
    var currentRound = submitString.substring(11) - 1;
    return currentRound;
}

function setPairings(pairingReturn) {
    var pairings = JSON.parse(pairingReturn);
    for (var a = 0; a < Object.keys(pairings).length; a++) {
        $("#p" + a).val(pairings[a]["p"]);
        $("#d" + a).val(pairings[a]["d"]);
    }
}

$("#sumbitPairingsButton").on("click", function (e) {
    e.preventDefault();
    var numberOfTeams = $('#p0 option').length - 1;
    var pairingsString = "{";
    for (var a = 0; a < numberOfTeams/2; a++) {
        var pTeamNumber = $("#p"+a+" option:selected").attr("id");
        var dTeamNumber = $("#d"+a+" option:selected").attr("id");
        pairingsString +='"p'+a+'":'+pTeamNumber+',"d'+a+'":'+dTeamNumber;
        if(a !== numberOfTeams/2-1){
            pairingsString += ",";
        }
    }
    pairingsString += "}";
    var pairings = JSON.parse(pairingsString);
    $.ajax({

        // The URL for the request
        url: "/postPairings.php",

        // The data to send (will be converted to a query string)
        data: pairings,

        // Whether this is a POST or GET request
        type: "POST",

        // The type of data we expect back
        dataType: "json",
    })
            // Code to run if the request succeeds (is done);
            // The response is passed to the function
            .done(function (r) {
                if(r["exitCode"]==1){
                    //TODO: highlight error using CSS
                    alert(r["errorMessage"])
                }
            })
});