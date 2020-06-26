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
var selectOptions = [];

$(document).ready(function () {
    //Pull all data from server
    updateData().then(data => {
        let currentRound = 1;
        for(var a=0;a<pairings.length;a++){
            if(pairings[a].round>currentRound){
                currentRound = pairings[a].round;
            }
        }
        $("#round").val(`Round ${currentRound}`);
        fillPairingSelect();
    });
});

function fillPairingSelect(){
    //determine which round's pairings should be listed
    let pairingOptionsHTML = [];
    let selectedRound = Number($("#round").val().substring(6,7));
    
    //create option HTML
    for(var a = 0;a<pairings.length;a++){
        if(pairings[a].round===selectedRound){
            pairingOptionsHTML.push(`<option>${pairings[a].plaintiff} v. ${pairings[a].defense}</option>\n`);
        }
    }
    
    //fill the select with the options
    $("#pairing").empty();
    pairingOptionsHTML.forEach(function(option){$("#pairing").append(option);});
    fillBallotSelect();
};

function fillBallotSelect(){
    //determine which pairing should have its ballots listed
    let ballotOptionsHTML = [];
    let plaintiff = Number($("#pairing").val().substring(0,4));
    let defense = Number($("#pairing").val().substring(8,13));
    let pairingId = 0;
    for(var a = 0;a<pairings.length;a++){
        if(pairings[a].plaintiff===plaintiff && pairings[a].defense===defense){
            pairingId = pairings[a].id;
        }
    }
    
    //create option HTML
    for(var a=0;a<ballots.length;a++){
        if(ballots[a].pairing===pairingId){
            ballotOptionsHTML.push(`<option>Ballot ${a}</option>\n`);
        }
    }
    
    //put the options into the select
    $("#ballot").empty();
    ballotOptionsHTML.forEach(function(option){$("#ballot").append(option);});
}

function updateData() {
    return new Promise(function (resolve, reject) {
        Promise.all([getTeams(), getPairings(), getBallots()]).then(data => {
            teams = data[0];
            pairings = data[1];
            ballots = data[2];
            resolve();
        });
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
