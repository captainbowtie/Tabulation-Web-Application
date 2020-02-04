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
    const getData = new Promise(function (resolve, reject) {
        var teams = getTeams();
        var impermissibles = getImpermissibles();
        resolve(teams);

    });

    getData.then(function(value){
        console.log("value");
    });


    //var pairings = shuffle(teams);
    //console.log(pairings);

}

function pair() {}
function error() {}

function getTeams() {
    var teams;
    $.ajax({
        url: "../api/teams/getAll.php",
        success: function (data) {
            teams = data;
        },
        dataType: "json"
    });
    return teams;
}

function getImpermissibles() {
    var impermissibles;
    $.ajax({
        url: "../api/impermissibles/getAll.php",
        success: function (data) {
            impermissibles = data;
        },
        dataType: "json"
    });
    return impermissibles;
}



//cc-by-sa CoolAJ86 https://stackoverflow.com/questions/2450954/how-to-randomize-shuffle-a-javascript-array
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