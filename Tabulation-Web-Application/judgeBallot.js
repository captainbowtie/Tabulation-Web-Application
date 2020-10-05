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

$("input[type='number']").on("change", function () {
    if (validateScore($(this).val())) {
        updateScore($(this).attr("id"),$(this).val());
    } else {
        let part = determinePart($(this).attr("id"));
        alert("Error: " + part + " must be a number between 0 and 10.");
    }
});

function updateScore(part,score){
    let updateData = `{"part":"${part}","score":${score},"url":"${url}"}`;

    $.ajax({
            url: "../api/judgeBallot/updateScore.php",
            method: "POST",
            data: updateData,
            dataType: "json"
        }).then(response => {
            if (response.message === 0) {
                
            } else {
                alert(response.message);
            }
        });
}

function validateScore(score) {
    if (!score) {
        return false;
    } else if (score === "") {
        return false
    } else if (isNaN(score)) {
        return false;
    } else if (!Number.isInteger(score * 1)) {
        return false;
    } else if (score < 0) {
        return false;
    } else if (score > 10) {
        return false;
    } else {
        return true;
    }
}

function determinePart(part) {
    switch (part) {
        case "pOpen":
            return "Plaintiff Open";
            break;
        case "dOpen":
            return "Defense Open";
            break;
        case "pDx1":
            return "Plaintiff Directing Attorney 1";
            break;
        case "pDx2":
            return "Plaintiff Directing Attorney 2";
            break;
        case "pDx3":
            return "Plaintiff Directing Attorney 3";
            break;
        case "pCx1":
            return "Plaintiff Crossing Attorney 1";
            break;
        case "pCx2":
            return "Plaintiff Crossing Attorney 2";
            break;
        case "pCx3":
            return "Plaintiff Crossing Attorney 3";
            break;
        case "dDx1":
            return "Defense Directing Attorney 1";
            break;
        case "dDx2":
            return "Defense Directing Attorney 2";
            break;
        case "dDx3":
            return "Defense Directing Attorney 3";
            break;
        case "dCx1":
            return "Defense Crossing Attorney 1";
            break;
        case "dCx2":
            return "Defense Crossing Attorney 2";
            break;
        case "dCx3":
            return "Defense Crossing Attorney 3";
            break;
        case "dWDx1":
            return "Defense Witness Direct 1";
            break;
        case "dWDx2":
            return "Defense Witness Direct 2";
            break;
        case "dWDx3":
            return "Defense Witness Direct 3";
            break;
        case "dWCx1":
            return "Defense Witness Cross 1";
            break;
        case "dWCx2":
            return "Defense Witness Cross 2";
            break;
        case "dWCx3":
            return "Defense Witness Cross 3";
            break;
        case "pWDx1":
            return "Plaintiff Witness Direct 1";
            break;
        case "pWDx2":
            return "Plaintiff Witness Direct 2";
            break;
        case "pWDx3":
            return "Plaintiff Witness Direct 3";
            break;
        case "pWCx1":
            return "Plaintiff Witness Cross 1";
            break;
        case "pWCx2":
            return "Plaintiff Witness Cross 2";
            break;
        case "pWCx3":
            return "Plaintiff Witness Cross 3";
            break;
        case "pClose":
            return "Plaintiff Close";
            break;
        case "dClose":
            return "Defense Close";
            break;

    }
}
