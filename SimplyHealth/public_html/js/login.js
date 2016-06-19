/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function userLogin() {
    var userData = new Object();
    userData.username = $("#inputUserName").val();
    userData.password = $("#inputPassword").val();

    $.ajax({
        method: "POST",
        url: "api/login.php",
        async: false,
        data: JSON.stringify(userData)
    })
    .done(function( data ) {
        var rolename = (JSON.parse(data)).rolename;
        switch (rolename) {
            case "Admin":
                document.getElementById("formLogin").action = "dashboardadmin.html";
                break;
            case "Patient":
                document.getElementById("formLogin").action = "dashboardpatient.html";
                break;
            case "Nurse":
                document.getElementById("formLogin").action = "dashboardnurse.html";
                break;
            case "Doctor":
                document.getElementById("formLogin").action = "dashboarddoctor.html";
                break;
        }
        return true;
    })
    .fail(function( jqXHR, textStatus ) {
        alert((typeof jqxhr.responseJSON) === "undefined" ? jqxhr.responseText : jqxhr.responseJSON.error, true);
        return false;
    });

/*
    $.post(
        "api/login.php",
        JSON.stringify(userData))
        .success(goToDashboard)
        .fail(showAjaxError);
        */
};

function goToDashboard(data) {
    var rolename = (JSON.parse(data)).rolename;

    switch (rolename) {
        case "Admin":
            document.getElementById("formLogin").action = "dashboardadmin.html";
            break;
        case "Patient":
            document.getElementById("formLogin").action = "dashboardpatient.html";
            break;
        case "Nurse":
            document.getElementById("formLogin").action = "dashboardnurse.html";
            break;
        case "Doctor":
            document.getElementById("formLogin").action = "dashboarddoctor.html";
            break;
    }
}

function showAjaxError (jqxhr, textStatus, thrownError) {
    alert((typeof jqxhr.responseJSON) === "undefined" ? jqxhr.responseText : jqxhr.responseJSON.error, true);
    return false;
}
