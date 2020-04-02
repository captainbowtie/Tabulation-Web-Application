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


$("#test").on("click", function () {
    pair1();
});

function pair1() {
    getData().then(data => {
        //Assign team data to variables
        var teams = data[0];
        var impermissibles = data[1];
        var noConflicts = false;

        while (noConflicts===false) {
            //Shuffle the list of teams, assign to plaintiff and defense
            var shuffledTeams = shuffle(teams);
            var plaintiffTeams = [];
            var defenseTeams = [];
            for (var a = 0; a < shuffledTeams.length / 2; a++) {
                plaintiffTeams[a] = shuffledTeams[a * 2];
                defenseTeams[a] = shuffledTeams[a * 2 + 1];
            }
            //check for pairing conflicts
            var noConflicts = true;
            for (var a = 0; a < plaintiffTeams.length; a++) {
                for (var b = 0; b < impermissibles.length; b++) {
                    if ((plaintiffTeams[a] === impermissibles[b].team0 ||
                            plaintiffTeams[a] === impermissibles[b].team1) &&
                            (defenseTeams[a] === impermissibles[b].team0 ||
                                    defenseTeams[a] === impermissibles[b].team1)) {
                                noConflicts = false;
                    }
                }
            }
        }
        
        //write pairings to server
        for(var a = 0;a<plaintiffTeams.length;a++){
            console.log(plaintiffTeams[a].number+" "+defenseTeams[a].number);
        }
    });


}

function pair2() {

}

function pair3() {

}

function pair4() {

}

function getData() {
    return new Promise(function (resolve, reject) {
        var teams = getTeams();
        var impermissibles = getImpermissibles();
        Promise.all([teams, impermissibles]).then(data => resolve(data));
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



//cc-by-sa by CoolAJ86 https://stackoverflow.com/questions/2450954/how-to-randomize-shuffle-a-javascript-array
function shuffle(array) {
    var currentIndex = array.length, temporaryValue, randomIndex;

    // While there remain elements to shuffle...
    while (0 !== currentIndex) {

        // Pick a remaining element...
        randomIndex = Math.floor(Math.random() * currentIndex);
        currentIndex -= 1;

        // And swap it with the current element.
        temporaryValue = array[currentIndex];
        array[currentIndex] = array[randomIndex];
        array[randomIndex] = temporaryValue;
    }

    return array;
}