/* 
 * Copyright (C) 2017 allen
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

$(document).ready(function () {
    populateUsers();
});

$(document).on("change", ".existingUser", function () {
    //Set values for POST data
    var id = $(this).attr("user");
    var field = $(this).attr("field");
    if (field == "name" || field == "email") {
        var value = $(this).val();
    } else if (field == "isTab" || field == "isCoach" || field == "isJudge" ||
            field == "round1" || field == "round2" || field == "round3" || field == "round4") {
        if ($(this).prop("checked")) {
            var value = 1;
        } else {
            var value = 0;
        }
    } else if (field == "judgeQuality") {
        var value = $(this).val();
    }
    
    //Enable/disable round availabilitiy and quality depending on judge status
    if(field == "isJudge"){
        if ($(this).prop("checked")) {
            $("#round1"+id).prop( "disabled", false );
            $("#round2"+id).prop( "disabled", false );
            $("#round3"+id).prop( "disabled", false );
            $("#round4"+id).prop( "disabled", false );
            $("#judgeQuality"+id).prop( "disabled", false );
        } else {
            $("#round1"+id).prop( "disabled", true );
            $("#round2"+id).prop( "disabled", true );
            $("#round3"+id).prop( "disabled", true );
            $("#round4"+id).prop( "disabled", true );
            $("#judgeQuality"+id).prop( "disabled", true );
        }
    }

    var postString = '{"id":' + id + ',"field":"' + field + '","value":"' + value + '"}';
    $.ajax({

        // The URL for the request
        url: "/postUser.php",

        // The data to send (will be converted to a query string)
        data: JSON.parse(postString),

        // Whether this is a POST or GET request
        type: "POST",

        // The type of data we expect back
        dataType: "text",
    })
            // Code to run if the request succeeds (is done);
            // The response is passed to the function
            .done(function (response) {
                //TODO: error handling
            })
})

//Change the disabled property of round checkboxes and quality select in the new
//user form based on whether or not the new user is a judge
$(document).on("change", "#isJudge", function () {
    if ($(this).prop("checked")) {
            $("#round1").prop( "disabled", false );
            $("#round2").prop( "disabled", false );
            $("#round3").prop( "disabled", false );
            $("#round4").prop( "disabled", false );
            $("#judgeQuality").prop( "disabled", false );
        } else {
            $("#round1").prop( "disabled", true );
            $("#round2").prop( "disabled", true );
            $("#round3").prop( "disabled", true );
            $("#round4").prop( "disabled", true );
            $("#judgeQuality").prop( "disabled", true );
        }
})

$(document).on("click", "#addUser", function (e) {
    e.preventDefault();
    var postArray = {};
    postArray["name"] = $("#newUserName").val();
    postArray["email"] = $("#newUserEmail").val();
    postArray["password"] = $("#newUserPassword").val();
    postArray["isTab"] = 0;
    if ($("#isTab").prop("checked")) {
        postArray["isTab"] = 1;
    }
    postArray["isCoach"] = 0;
    if ($("#isCoach").prop("checked")) {
        postArray["isCoach"] = 1;
    }
    postArray["isJudge"] = 0;
    if ($("#isJudge").prop("checked")) {
        postArray["isJudge"] = 1;
    }
    postArray["round1"] = 0;
    if ($("#round1").prop("checked")) {
        postArray["round1"] = 1;
    }
    postArray["round2"] = 0;
    if ($("#round2").prop("checked")) {
        postArray["round2"] = 1;
    }
    postArray["round3"] = 0;
    if ($("#round3").prop("checked")) {
        postArray["round3"] = 1;
    }
    postArray["round4"] = 0;
    if ($("#round4").prop("checked")) {
        postArray["round4"] = 1;
    }
    postArray["judgeQuality"] = $("#judgeQuality").val();
    $.ajax({

        // The URL for the request
        url: "/postUser.php",

        // The data to send (will be converted to a query string)
        data: JSON.parse(JSON.stringify(postArray)),

        // Whether this is a POST or GET request
        type: "POST",

        // The type of data we expect back
        dataType: "text",
    })
            // Code to run if the request succeeds (is done);
            // The response is passed to the function
            .done(function (response) {
                //TODO: error handling
                populateUsers();
            })

})

function populateUsers() {
    $("#userForm").empty();
    $.ajax({

        // The URL for the request
        url: "/userTable.php",

        // Whether this is a POST or GET request
        type: "GET",

        // The type of data we expect back
        dataType: "html",
    })
            // Code to run if the request succeeds (is done);
            // The response is passed to the function
            .done(function (table) {
                $('#userForm').append(table);
            })
}

function validateName() {
    if ($('#name').val() != '') {
        return true;
    } else {
        return false;
    }
}

function validateEmail() {
    var regEx = /[A-z0-9._%+-]+@[A-z0-9.-]+\.[A-z]{2,}/;
    return regEx.test($('#email').val());
}