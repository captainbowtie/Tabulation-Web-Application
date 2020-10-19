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

$("#team").change(fillAwards);

function fillAwards() {
    //find the awards for the selected team
    let teamAwards = [];
    for (var a = 0; a < awards.length; a++) {
        if (awards[a].team === parseInt($("#team").val())) {
            teamAwards.push(awards[a]);
        }
    }

    //sort team awards into types
    let pAtyAwards = [];
    let pWitAwards = [];
    let dAtyAwards = [];
    let dWitAwards = [];
    for (var a = 0; a < teamAwards.length; a++) {
        if (teamAwards[a].isAttorney) {
            if (teamAwards[a].isPlaintiff) {
                pAtyAwards.push(teamAwards[a]);
            } else {
                dAtyAwards.push(teamAwards[a]);
            }
        } else {
            if (teamAwards[a].isPlaintiff) {
                pWitAwards.push(teamAwards[a]);
            } else {
                dWitAwards.push(teamAwards[a]);
            }
        }
    }
    //sort awards by name
    pAtyAwards.sort((a, b) => a.name.localeCompare(b.name));
    pWitAwards.sort((a, b) => a.name.localeCompare(b.name));
    dAtyAwards.sort((a, b) => a.name.localeCompare(b.name));
    dWitAwards.sort((a, b) => a.name.localeCompare(b.name));

    //fill in table
    let pAtyHTML = "";
    let pWitHTML = "";
    let dAtyHTML = "";
    let dWitHTML = "";
    for(var a =0;a<pAtyAwards.length;a++){
        pAtyHTML += `<div>${pAtyAwards[a].name+" "+pAtyAwards[a].ranks}</div>\n`;
    }
    for(var a =0;a<pWitAwards.length;a++){
        pWitHTML += `<div>${pWitAwards[a].name+" "+pWitAwards[a].ranks}</div>\n`;
    }
    for(var a =0;a<dAtyAwards.length;a++){
        dAtyHTML += `<div>${dAtyAwards[a].name+" "+dAtyAwards[a].ranks}</div>\n`;
    }
    for(var a =0;a<dWitAwards.length;a++){
        dWitHTML += `<div>${dWitAwards[a].name+" "+dWitAwards[a].ranks}</div>\n`;
    }
    $("#pAty").html(pAtyHTML);
    $("#pWit").html(pWitHTML);
    $("#dAty").html(dAtyHTML);
    $("#dWit").html(dWitHTML);
}
