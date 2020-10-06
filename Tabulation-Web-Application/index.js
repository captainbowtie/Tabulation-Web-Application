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
$("#submitJudgesPerRound").on("click", function (e) {
    e.preventDefault();
    let judgesPerRound = Number.parseInt($("#judgesPerRound").val());
    if (Number.isInteger(judgesPerRound) && judgesPerRound > 0) {
        setJudgesPerRound(judgesPerRound);
    } else {
        alert("Invalid entry for number of judges per round");
    }
});

function setJudgesPerRound(judgesPerRound) {
    $.ajax({
        url: "../api/settings/setJudgesPerRound.php",
        method: "POST",
        data: `{"judgesPerRound":${judgesPerRound}}`,
        dataType: "json"
    }).then(response => {
        if (response.message === 0) {
            window.location.reload();
        } else {
            alert(response.message);
        }
    });
}