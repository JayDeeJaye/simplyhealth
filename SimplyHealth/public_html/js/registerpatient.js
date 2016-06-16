/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$( document ).ajaxError(function( event, jqxhr, settings, thrownError ) {
    $( "#div_error" ).html( settings.url + ": "+thrownError + " <br> " + jqxhr.responseJSON.error);
});

var patientData = new Object(); 
var patientForm;

$("form").submit(function(e) {
    e.preventDefault();

    patientForm = this;

    var userName = $("#inputUserName").val();
    var pwd = $("#inputPassword").val();
    var confirmpwd =$("#inputConfirmPassword").val();

    patientData.firstName = $("#inputFirstName").val();
    patientData.lastName = $("#inputLastName").val();
    patientData.email = $("#inputEmail").val();
    patientData.phone = $("#inputPhone").val();
    patientData.address1 = $("#inputAddress1").val();
    patientData.address2 = $("#inputAddress2").val();
    patientData.city = $("#inputCity").val();
    patientData.state = $("#inputState").val();
    patientData.zip = $("#inputZipcode").val();

    // create the user first, we'll need the id
   var userData = { username: userName, password: pwd };
   $.post("/api/users.php",
        JSON.stringify(userData),
        userSuccess,
        "json");
    
});

function userSuccess (data) {
    $("#div_info").text("Returned from the users api call: "+JSON.stringify(data));
    // Add the patient info for the user.
    patientData.userId = data.id;

    $.post("/api/patients.php",
        JSON.stringify(patientData),
        patientSuccess,
        "json");
}

function patientSuccess (data) {
    patientForm.submit();
}