/* 
 * Copyright (C) 2024 allen
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

$(document).ready(() => {
	fillBody();
});

function fillBody() {
	getRoster().then((roster) => {
		let bodyHTML = "<div style='display: grid;'>";
		bodyHTML += "<div style='grid-column: 1 / span 1; grid-row: 1 / span 1;'>Name</div>";
		//add row for each name
		for (let a = 0; a < roster.length; a++) {
			let rowHTML = "";
			rowHTML += `<div style='grid-column: 1 / span 1; grid-row: ${a + 2} / span 1;'><input class='name' student='${roster[a].id}' value='${roster[a].student}' /></div>`;
			rowHTML += `<div style='grid-column: 2 / span 1; grid-row: ${a + 2} / span 1;'><button class='delete' student='${roster[a].id}'>Delete</button></div>`;
			bodyHTML += rowHTML;
		}
		//add form for new names
		bodyHTML += `<div style='grid-column: 1 / span 1; grid-row: ${roster.length + 2} / span 1;'><input id='newName' /></div>`;
		bodyHTML += `<div style='grid-column: 2 / span 1; grid-row: ${roster.length + 2} / span 1;'><button id='add'>Add Student</button></div>`;
		bodyHTML += "</div>";
		$("#body").html(bodyHTML);

		attachListeners();

		function attachListeners() {
			$("#add").click(() => {
				let name = $("#newName").val();
				if (name != "") {
					addStudent(name);
				}
			});

			$(".delete").click(function () {
				let studentId = $(this).attr("student");
				removeStudent(studentId);
			});

			$(".name").on("input", function () {
				let id = $(this).attr("student");
				let name = $(this).val();
				updateName(id, name);
			});
		}
	});
}

function updateName(id, name) {
	let data = { id: id, name: name };
	$.post("api/rosters/updateName.php", data, () => {
	}, "json").fail(() => {
		handleSessionExpiration();
	});
}

function removeStudent(studentId) {
	let data = { id: studentId };
	$.post("api/rosters/removeStudent.php", data, () => {
		fillBody();
	}, "json").fail(() => {
		handleSessionExpiration();
	});
}

function addStudent(name) {
	let data = { name: name };
	$.post("api/rosters/addStudent.php", data, () => {
		fillBody();
	}, "json").fail(() => {
		handleSessionExpiration();
	});;
}

function getRoster() {
	return new Promise((resolve, reject) => {
		$.get(
			"api/rosters/getTeam.php",
			(roster) => {
				resolve(roster);
			},
			"json").fail(() => {
				handleSessionExpiration();
			});
	})
}

function handleSessionExpiration() {
	let html = "User session expired. Please login again using your team link."
	$("body").html(html);
};