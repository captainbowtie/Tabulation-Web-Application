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
    getImpermissibles().then(impermissibles => console.log(impermissibles));
});

function pair1() {
    getTeams().then(teamData => {
        //Assign team data to variables
        var teams = teamData;
        var noConflicts = false;

        while (noConflicts === false) {
            //Shuffle the list of teams, assign to plaintiff and defense
            var shuffledTeams = shuffle(teams);
            var plaintiffTeams = [];
            var defenseTeams = [];
            for (var a = 0; a < shuffledTeams.length / 2; a++) {
                plaintiffTeams[a] = shuffledTeams[a * 2];
                defenseTeams[a] = shuffledTeams[a * 2 + 1];
            }
            //check for pairing conflicts
            var noConflicts = !pairingsHaveConflicts(plaintiffTeams, defenseTeams);
        }

        //display pairings for approval
        for (var a = 0; a < plaintiffTeams.length; a++) {
            console.log(plaintiffTeams[a].number + " " + defenseTeams[a].number);
        }
    });


}

function pair2() {

}

function pair3() {

}

function pair4() {

}

function pairingsHaveConflicts(plaintiffTeams, defenseTeams) {
    var noConflicts = true;
    getImpermissibles().then(impermissibles => {
        for (var a = 0; a < plaintiffTeams.length; a++) {
            for (var b = 0; b < impermissibles.length; b++) {
                if ((plaintiffTeams[a].number === impermissibles[b].team0 ||
                        plaintiffTeams[a].number === impermissibles[b].team1) &&
                        (defenseTeams[a].number === impermissibles[b].team0 ||
                                defenseTeams[a].number === impermissibles[b].team1)) {
                    noConflicts = false;
                }
            }
        }
        return noConflicts;
    });
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
        //get list of team impermissibles
        var impermissibles = new Promise(function (resolveImpermissibles, rejectImpermissibles) {
            $.ajax({
                url: "../api/impermissibles/getAll.php",
                dataType: "json"
            }).then(impermissibles => {
                resolveImpermissibles(impermissibles);
            });
        });
        //get pairing data (to add to impermissibles)
        var pairings = new Promise(function (resolvePairings, rejectPairings) {
            $.ajax({
                url: "../api/pairings/getAll.php",
                dataType: "json"
            }).then(pairings => {
                resolvePairings(pairings);
            });
        });
        //combine pairings with team impermissibles to get all impermissibles
        Promise.all([impermissibles, pairings]).then(data => {
            var impermissibles = data[0];
            var pairings = data[1];
            var numberOfImpermissibles = impermissibles.length;
            for (var a = 0; a < pairings.length; a++) {
                impermissibles.push({"team0": 0, "team1": 0});
                impermissibles[numberOfImpermissibles + a].team0 = pairings[a].plaintiff;
                impermissibles[numberOfImpermissibles + a].team1 = pairings[a].defense;
            }
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