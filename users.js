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

	$("#createUser").click(() => {
		let username = $("#newUsername").val();
		if (username != "") {
			let userData = { username: username, isAdmin: 0 }
			$.post(
				"api/users/create.php",
				userData,
				(result) => {
					if (result.message == -1) {
						handleSessionExpiration();
					} else {
						fillBody();
					}
				},
				"json");
		}
	});
};

function fillUserTable() {
	generateUserTable().then((tableHTML) => {
		$("#userTable").html(tableHTML);
		fillUserTeams();
		attachListeners();
	});
}

function generateUserTable() {
	return new Promise((resolve, reject) => {
		let tableHTML = "<div style='display: grid;'>";
		let headerHTML = "<div style='grid-column: 1 / span 1; grid-row: 1 / span 1;'>Username</div>";
		headerHTML += "<div style='grid-column: 2 / span 1; grid-row: 1 / span 1;'>Admin?</div>";
		headerHTML += "<div style='grid-column: 3 / span 1; grid-row: 1 / span 1;'>Login Link</div>";
		headerHTML += "<div  style='grid-column: 4 / span 1; grid-row: 1 / span 1;'>Reset Link</div>";

		Promise.all([getTeams(), getUsers()]).then((values) => {
			let teams = values[0];
			let users = values[1];

			//add a column to the table for each team
			for (let a = 0; a < teams.length; a++) {
				let teamHeaderHTML = `<div style='grid-column: ${a + 5} / span 1; grid-row: 1 / span 1;writing-mode: vertical-lr;'>${teams[a].number + " " + teams[a].name}</div>`;
				headerHTML += teamHeaderHTML;
			}
			tableHTML += headerHTML;

			//add a row to the table for each user
			for (let a = 0; a < users.length; a++) {
				tableHTML += generateUserRow(users[a], teams, a + 2);
			}
			tableHTML += "</div>"
			resolve(tableHTML);

		});

		function generateUserRow(user, teams, row) {
			let rowHTML = `<div style='grid-column: 1 / span 1; grid-row: ${row} / span 1;'><input class='username' userId='${user.id}' value='${user.username}'></div>`
			rowHTML += `<div style='grid-column: 2 / span 1; grid-row: ${row} / span 1;'><input class='isAdmin' userId='${user.id}' type='checkbox' ${user.isAdmin ? 'checked' : ''}></div>`
			rowHTML += `<div style='grid-column: 3 / span 1; grid-row: ${row} / span 1;'><a class='loginLink' href='login.php?url=${user.url}'>Link</a></div>`
			rowHTML += `<div style='grid-column: 4 / span 1; grid-row: ${row} / span 1;'><button class='linkReset' userId='${user.id}'>Reset</button></div>`;
			for (let a = 0; a < teams.length; a++) {
				rowHTML += `<div style='grid-column: ${a + 5} / span 1; grid-row: ${row} / span 1;'><input type="checkbox" class='userTeam' userId='${user.id}' teamId='${teams[a].id}'></div>`;
			}

			return rowHTML;
		}
	});
}

function fillUserTeams() {
	getUserTeams().then((userTeams) => {
		let teamCheckboxes = $(".userTeam");
		for (let a = 0; a < teamCheckboxes.length; a++) {
			$(teamCheckboxes[a]).prop("checked", false);
		}

		for (let a = 0; a < teamCheckboxes.length; a++) {
			let checkbox = $(teamCheckboxes[a]);
			let checkboxUser = checkbox.attr("userid");
			let checkboxTeam = checkbox.attr("teamid");
			for (let b = 0; b < userTeams.length; b++) {
				let user = userTeams[b].user;
				let team = userTeams[b].team;
				if (checkboxUser == user && checkboxTeam == team) {
					checkbox.prop("checked", true);
				}


			}
		}
	});
}

function attachListeners() {
	$(".username").on("input", function () {
		let id = $(this).attr("userid");
		let username = $(this).val();
		updateUsername(id, username);
	});
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
	$(".userTeam").on("change", function () {
		let user = $(this).attr("userid");
		let team = $(this).attr("teamid");
		if ($(this).prop("checked")) {
			addUserTeam(user, team);
		} else {
			removeUserTeam(user, team);
		}
	});
}

function getUsers() {
	return new Promise((resolve, reject) => {
		$.get(
			"api/users/getAll.php",
			(users) => {
				resolve(users);
			},
			"json").fail(() => {
				handleSessionExpiration();
			});;
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
		"json").fail(() => {
			handleSessionExpiration();
		});;
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
		"json").fail(() => {
			handleSessionExpiration();
		});;
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
		"json").fail(() => {
			handleSessionExpiration();
		});;
}

function getTeams() {
	return new Promise((resolve, reject) => {
		$.get(
			"api/teams/getAll.php",
			(teams) => {
				resolve(teams);
			},
			"json").fail(() => {
				handleSessionExpiration();
			});;
	})
}

function getUserTeams() {
	return new Promise((resolve, reject) => {
		$.get(
			"api/userTeams/getAll.php",
			(userTeams) => {
				resolve(userTeams);
			},
			"json").fail(() => {
				handleSessionExpiration();
			});;
	});
}

function addUserTeam(user, team) {
	let userTeamData = { user: user, team: team };
	$.post(
		"api/userTeams/add.php",
		userTeamData,
		function (response) {
			fillUserTeams();
		},
		"json").fail(() => {
			handleSessionExpiration();
		});
}

function removeUserTeam(user, team) {
	let userTeamData = { user: user, team: team };
	$.post(
		"api/userTeams/remove.php",
		userTeamData,
		function (response) {
			fillUserTeams();
		},
		"json").fail(() => {
			handleSessionExpiration();
		});
}

function handleSessionExpiration() {
	let html = "User session expired. Please login again using your login link."
	$("body").html(html);
};

