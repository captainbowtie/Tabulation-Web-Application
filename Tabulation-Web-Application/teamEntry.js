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

});

$(document).on("change", ".existingUserName", function () {
    var postString = '{"id":' + $(this).attr('competitor') + ',"field":"' + "name" + '","value":"' + this.value + '"}';
    $.ajax({

        // The URL for the request
        url: "/postCompetitor.php",

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

            })
});

$(document).on("change", ".existingUserRole", function () {
    var flag;
    if (this.checked) {
        flag = 1;
    } else {
        flag = 0;
    }
    var postString = '{"id":' + $(this).attr('competitor') + ',"field":"' + $(this).attr("role") + '","value":"' + flag + '"}';
    $.ajax({

        // The URL for the request
        url: "/postCompetitor.php",

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

            })
});

$(document).on("submit", "#addCompetitor", function (e) {
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
                populateTeam();
                $('#addCompetitor').trigger("reset");
            })
});

$(document).on("submit", "#newTeamForm", function (e) {
    e.preventDefault();
    var coachId = $("#newTeamCoach option:selected").attr("id");
    var postString = '{"teamNumber":' + $("#newTeamNumber").val() +
            ',"teamName":"' + $("#newTeamName").val() +
            '","coach":' + $("#newTeamCoach option:selected").attr("id").substring(5) + '}';
    var postData = JSON.parse(postString);
    $.ajax({

        // The URL for the request
        url: "/postTeam.php",

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
                $('#teamSelect').append($('<option>', {
                    id: $("#newTeamNumber").val(),
                    text: $("#newTeamNumber").val() + ": " + $("#newTeamName").val()
                }));
                $("#teamSelect option:selected").attr("selected","false");
                $("#"+$("#newTeamNumber").val()).attr("selected","true");
                $('#newTeamForm').trigger("reset");
                populateTeam();
            })


});

$(document).on("submit", "#addConflict", function (e) {
    e.preventDefault();
    var conflictNumber = $("#conflictSelect option:selected").attr('id').substring(8, 12);
    var teamNumber = $("#teamSelect option:selected").attr("id");
    var postString = '{"team1":' + conflictNumber + ',"team2":' + teamNumber + '}';
    var postData = JSON.parse(postString);
    $.ajax({

        // The URL for the request
        url: "/postConflict.php",

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
                populateTeam();
            })

});

$(document).on("change", "#teamSelect", function () {
    populateTeam();
});

function populateTeam() {
    var selectedTeam = $("#teamSelect option:selected").attr('id');
    var getString = '{"teamNumber":' + selectedTeam + '}';
    $.ajax({

        // The URL for the request
        url: "/getTeam.php",

        // The data to send (will be converted to a query string)
        data: JSON.parse(getString),

        // Whether this is a POST or GET request
        type: "GET",

        // The type of data we expect back
        dataType: "json",
    })
            // Code to run if the request succeeds (is done);
            // The response is passed to the function
            .done(function (team) {
                //Change team number and team name
                $("#teamNumber").html(team.teamNumber);
                $("#teamName").html(team.teamName);

                //Change team conflicts
                var conflictHTML = "";
                for (a = 0; a < team.schoolConflicts.length; a++) {
                    if (a == 0) {
                        conflictHTML += "School Conflicts: ";
                    }
                    conflictHTML += team.schoolConflicts[a]["teamNumber"] + " ";
                    conflictHTML += team.schoolConflicts[a]["teamName"];
                    if (!a == team.schoolConflicts.length - 1) {
                        conflictHTML += ";";
                    }
                }
                $("#conflictList").html(conflictHTML);

                //Change list of competitors
                $("#competitorForm").empty();

                var content = "<table>"
                for (a = 0; a < team.competitors.length; a++) {
                    var id = team.competitors[a]["id"];
                    content += "<tr><td><input role='name' " +
                            "competitor='" + id + "' " +
                            "value='" + team.competitors[a]["name"] + "' " +
                            "class='existingUserName'></td>";
                    content += "<td><label><input type=checkbox role='pAtty' " +
                            "competitor='" + id + "' class='existingUserRole'";
                    if (team.competitors[a]["pAtty"] == 1) {
                        content += " checked"
                    }
                    content += ">Plaintiff Attorney</label></td>";
                    content += "<td><label><input type=checkbox role='pWit' " +
                            "competitor='" + id + "' class='existingUserRole'";
                    if (team.competitors[a]["pWit"] == 1) {
                        content += " checked"
                    }
                    content += ">Plaintiff Witness</label></td>";
                    content += "<td><label><input type=checkbox role='dAtty' " +
                            "competitor='" + id + "' class='existingUserRole'";
                    if (team.competitors[a]["dAtty"] == 1) {
                        content += " checked"
                    }
                    content += ">Defense Attorney</label></td>";
                    content += "<td><label><input type=checkbox role='dWit' " +
                            "competitor='" + id + "' class='existingUserRole'";
                    if (team.competitors[a]["dWit"] == 1) {
                        content += " checked"
                    }
                    content += ">Defense Witness</label></td></tr>";
                }
                content += "</table>"

                $('#competitorForm').append(content);

            })
}