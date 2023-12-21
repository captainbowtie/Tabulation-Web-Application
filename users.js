$(document).ready(() => {
	fillHeader();
	fillBody();
});

function fillHeader() {
	$.get("header.php", (headerHTML) => {
		$("#header").html(headerHTML);
	});
}

function fillBody() {
	let bodyHTML = "<div id='userTable'></div>";
	bodyHTML += "<div><input id='newUsername' placeholder='New Username'><button id='createUser'>Create User</button></div>"
	$("#body").html(bodyHTML);
	fillUserTable();

};

function fillUserTable() {
	generateUserTable().then((tableHTML) => {
		$("#userTable").html(tableHTML);
		attachListeners();
	});
}

function generateUserTable() {
	return new Promise((resolve, reject) => {
		let tableHTML = "<div style='display: grid;'>";
		tableHTML += "<div style='grid-column: 1 / span 1; grid-row: 1 / span 1;'>Username</div><div style='grid-column: 2 / span 1; grid-row: 1 / span 1;'>Edit Teams</div><div style='grid-column: 3 / span 1; grid-row: 1 / span 1;'>Admin?</div><div style='grid-column: 4 / span 1; grid-row: 1 / span 1;'>Login Link</div><div  style='grid-column: 5 / span 1; grid-row: 1 / span 1;'>Reset Link</div>"
		getUsers().then((users) => {
			for (let a = 0; a < users.length; a++) {
				tableHTML += generateUserRow(users[a], a + 2);
			}
			tableHTML += "</div>"
			resolve(tableHTML);
		});

		function generateUserRow(user, row) {
			let rowHTML = `<div style='grid-column: 1 / span 1; grid-row: ${row} / span 1;'><input class='username' userid='${user.id}' value='${user.username}'></div>`
			rowHTML += `<div style='grid-column: 2 / span 1; grid-row: ${row} / span 1;'><button class='editTeams' userid='${user.id}'>Edit Teams</button></div>`
			rowHTML += `<div style='grid-column: 3 / span 1; grid-row: ${row} / span 1;'><input class='isAdmin' userid='${user.id}' type='checkbox' ${user.isAdmin ? 'checked' : ''}></div>`
			rowHTML += `<div style='grid-column: 4 / span 1; grid-row: ${row} / span 1;'><a class='loginLink' href='${user.url}'>Link</a></div>`
			rowHTML += `<div  style='grid-column: 5 / span 1; grid-row: ${row} / span 1;'><button class='linkReset' userid='${user.id}'>Reset</button></div>`;
			return rowHTML;
		}
	});
}

function attachListeners() {
	$(".username").on("input", function () {
		let id = $(this).attr("userid");
		let username = $(this).val();
		updateUsername(id, username);
	});
	$(".editTeams").click(() => { });
	$(".isAdmin").on("change", function () {
		let id = $(this).attr("userid");
		let adminStatus = 0;
		if ($(this).prop("checked")) {
			adminStatus = 1;
		}
		updateIsAdmin(id, adminStatus);
	});
	$(".linkReset").click(function () {
		let id = $(this).attr("userid");
		resetURL(id);
	});
}

function getUsers() {
	return new Promise((resolve, reject) => {
		$.get(
			"api/users/getAll.php",
			(users) => {
				if (users.message == -1) {
					reject(handleSessionExpiration());
				} else {
					resolve(users);
				}
			},
			"json");
	});
}

function updateUsername(id, username) {
	let updateData = { id: id, username: username };
	$.post(
		"api/users/updateUsername.php",
		updateData,
		function (response) {
			if (response.message == -1) {
				handleSessionExpiration();
			}
		},
		"json");
}

function updateIsAdmin(id, adminStatus) {
	let updateData = { id: id, isAdmin: adminStatus };
	$.post(
		"api/users/updateIsAdmin.php",
		updateData,
		function (response) {
			if (response.message == -1) {
				handleSessionExpiration();
			}
		},
		"json");
}

function resetURL(id) {
	let updateData = { id: id };
	$.post(
		"api/users/resetURL.php",
		updateData,
		function (response) {
			if (response.message == -1) {
				handleSessionExpiration();
			}
		},
		"json");
}


function handleSessionExpiration() {
	let html = "User session expired. Please login again using your login link."
	$("body").html(html);
};

