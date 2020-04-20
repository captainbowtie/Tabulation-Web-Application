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

var teams;
var pairings;
var ballots;
var impermissibles;

function fillData(number) {
    updateData().then(data => {
        fillTabCard();
        fillImpermissibles();

        function fillTabCard() {
            //update number and name
            $("#number").html(number);
            for (var a = 0; a < teams.length; a++) {
                if (teams[a].number === number) {
                    $("#name").html(teams[a].name);
                }
            }
            //check that a pairing exists for particular round, if so, update as much of column as possible
            for (var a = 0; a < pairings.length; a++) {
                if (pairings[a].plaintiff === number) {//checks if pairing exists
                    //if pairing exists on plaintiff side, update with side and opponent
                    $("#round" + pairings[a].round + "Pairing").html("π v " + pairings[a].defense);

                    //then, check if any ballots exist for the round
                    var roundBallots = [];
                    for (var b = 0; b < ballots.length; b++) {
                        if (ballots[b].pairing === pairings[a].id) {
                            roundBallots.push(ballots[b]);
                        }
                    }
                    //if ballots do exist, use them to fill remaining rows of the round column
                    //Start with the ballots row, creating a table within the cell showing W,L,T and PD
                    var ballotHTML = "<table>\n";
                    if (roundBallots.length > 0) {
                        ballotHTML += "<tr>\n";//W,L, or T row
                    }
                    for (var b = 0; b < roundBallots.length; b++) {
                        ballotHTML += "<td>";
                        if (roundBallots[b].plaintiffPD > 0) {
                            ballotHTML += "W";
                        } else if (roundBallots[b].plaintiffPD === 0) {
                            ballotHTML += "T";
                        } else {
                            ballotHTML += "L";
                        }
                        ballotHTML += "</td>\n";
                    }
                    if (roundBallots.length > 0) {
                        ballotHTML += "</tr>\n<tr>";//PD row
                    }
                    for (var b = 0; b < roundBallots.length; b++) {
                        ballotHTML += "<td>" + roundBallots[b].plaintiffPD + "</td>\n";
                    }
                    if (roundBallots.length > 0) {
                        ballotHTML += "</tr>\n";
                    }
                    ballotHTML += "</table>\n";
                    $("#round" + pairings[a].round + "Ballots").html(ballotHTML);

                } else if (pairings[a].defense === number) {//checks if pairing exists
                    //if pairing exists on defense side, update with side and opponent
                    $("#round" + pairings[a].round + "Pairing").html("∆ v " + pairings[a].plaintiff);
                }
            }
        }
        function fillImpermissibles(){
            var teamImpermissibles = [];
            for(var a = 0;a<impermissibles.length;a++){
                if(impermissibles[a].team0 === number){
                    teamImpermissibles.push(impermissibles[a].team1);
                }else if(impermissibles[a].team1 === number){
                    teamImpermissibles.push(impermissibles[a].team0);
                }
            }
            if(teamImpermissibles.length>0){
                var impermissibleHTML = "<table>\n";
                for(var a = 0;a<teamImpermissibles.length;a++){
                    impermissibleHTML += "<tr>\n<td>";
                    for(var b = 0;b<teams.length;b++){
                        if(teams[b].number === teamImpermissibles[a]){
                            impermissibleHTML += teams[b].number+ "</td>\n<td>" + teams[b].name + "</td>\n";
                            impermissibleHTML += "<td><a href=''>Edit</a></td>\n<td><a href=''>Delete</a></td>\n";
                            b = teams.length;
                        }
                    }
                    impermissibleHTML += "</td>\n</tr>\n";
                }
                impermissibleHTML += "</table>\n";
                $("#impermissibles").html(impermissibleHTML);
            }
        }

    });

}

function getTeams() {
    return new Promise(function (resolve, reject) {
        $.ajax({
            url: "../api/teams/getAll.php",
            dataType: "json"
        }).then(teams => {
            resolve(teams);
        });
    });

}

function getPairings() {
    return new Promise(function (resolve, reject) {
        $.ajax({
            url: "../api/pairings/getAll.php",
            dataType: "json"
        }).then(pairings => {
            resolve(pairings);
        });
    });
}

function getBallots() {
    return new Promise(function (resolve, reject) {
        $.ajax({
            url: "../api/ballots/getAll.php",
            dataType: "json"
        }).then(ballots => {
            resolve(ballots);
        });
    });
}

function getImpermissibles() {
    return new Promise(function (resolve, reject) {
        $.ajax({
            url: "../api/impermissibles/getAll.php",
            dataType: "json"
        }).then(impermissibles => {
            resolve(impermissibles);
        });
    });
}

function updateData() {
    return new Promise(function (resolve, reject) {
        Promise.all([getTeams(), getPairings(), getImpermissibles(), getBallots()]).then(data => {
            teams = data[0];
            pairings = data[1];
            impermissibles = data[2];
            ballots = data[3];
            resolve();
        });
    });
}