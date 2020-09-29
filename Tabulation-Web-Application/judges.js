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

var conflictJudge;

$(".name").on("change", function () {
    let editRow = $(this).closest("tr");
    let judgeId = (editRow[0].id.substring(5));
    updateJudge(judgeId, "name", $(this).val());
});

$(".category").on("change", function () {
    let editRow = $(this).closest("tr");
    let judgeId = (editRow[0].id.substring(5));
    updateJudge(judgeId, "category", $(this).val());
});

$(".round1").on("change", function () {
    let editRow = $(this).closest("tr");
    let judgeId = (editRow[0].id.substring(5));
    updateJudge(judgeId, "round1", $(this).prop("checked"));
});

$(".round2").on("change", function () {
    let editRow = $(this).closest("tr");
    let judgeId = (editRow[0].id.substring(5));
    updateJudge(judgeId, "round2", $(this).prop("checked"));
});

$(".round3").on("change", function () {
    let editRow = $(this).closest("tr");
    let judgeId = (editRow[0].id.substring(5));
    updateJudge(judgeId, "round3", $(this).prop("checked"));
});

$(".round4").on("change", function () {
    let editRow = $(this).closest("tr");
    let judgeId = (editRow[0].id.substring(5));
    updateJudge(judgeId, "round4", $(this).prop("checked"));
});

$(".conflictsButton").on("click", function () {
    let editRow = $(this).closest("tr");
    conflictJudge = (editRow[0].id.substring(5));
    $("#conflictsModal").modal();
});

function updateJudge(id, field, value) {
    let updateData = `{
    "id":${id},
    "field":"${field}",
    "value":"${value}"
    }`;
    $.ajax({
        url: "../api/judges/update.php",
        method: "POST",
        data: updateData,
        dataType: "json"
    }).then(response => {
        if (response.message != 0) {
            warningModal(response.message);
        }
    });
}

function warningModal(warning) {
    alert(warning);
}

$("#newJudge").on("click", function () {
    $("#name").val("");
    $("#category").val("3");
    $("#round1").prop("checked", false);
    $("#round2").prop("checked", false);
    $("#round3").prop("checked", false);
    $("#round4").prop("checked", false);
    $("#newJudgeModal").modal();
});

$("#addJudge").on("click", function () {
    let judgeData = `{
    "name":"${$("#name").val()}",
    "category":"${$("#category").val()}",
    "round1":"${$("#round1").prop("checked")}",
    "round2":"${$("#round2").prop("checked")}",
    "round3":"${$("#round3").prop("checked")}",
    "round4":"${$("#round4").prop("checked")}"
    }`;
    $.ajax({
        url: "../api/judges/create.php",
        method: "POST",
        data: judgeData,
        dataType: "json"
    }).then(response => {
        if (response.message === 0) {
            window.location.reload();
        } else {
            warningModal(response.message);
        }
    });
});