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
var userData = new Object();

$("form").submit(function(e) {
    e.preventDefault();

    patientForm = this;

    var userName = $("#inputUserName").val();
    var pwd = $("#inputPassword").val();
    var confirmpwd =$("#inputConfirmPassword").val();

    if(pwd !== confirmpwd) {
        var passwordField = $("#inputPassword");
        var conifrmPasswordField = $("#inputConfirmPassword");
        passwordField.val('');
        conifrmPasswordField.val('');

        $( "#div_error" ).html( "Password does not match! <br> ");

        return false;
    } else if (pwd == "" || confirmpwd == "") {
        var passwordField = $("#inputPassword");
        var conifrmPasswordField = $("#inputConfirmPassword");
        passwordField.val('');
        conifrmPasswordField.val('');

        $( "#div_error" ).html( "Password cannot be NULL! <br> ");        
    }

    patientData.firstName = $("#inputFirstName").val();
    patientData.lastName = $("#inputLastName").val();
    patientData.email = $("#inputEmail").val();
    patientData.phone = $("#inputPhone").val();
    patientData.address1 = $("#inputAddress1").val();
    patientData.address2 = $("#inputAddress2").val();
    patientData.city = $("#inputCity").val();
    patientData.state = $("#inputState").val();
    patientData.zipCode = $("#inputZipcode").val();
    patientData.emergencyContactName = $("#inputEmergencyName").val();
    patientData.emergencyContactPhone = $("#inputEmergencyPhone").val();

    var roleName = "Patient";

    // get the roleid
    var url = "api/roles.php/" + roleName;
    $.ajax({
      url: url,
      dataType: 'json',
      async: false,
      data: null,
      success: function(data) {
        userData.roleId = data.id;
      }
    })
    .fail(function(jqxhr, textStatus, error) {
        var err = textStatus + ", " + error;
        $( "#div_error" ).html( err + " <br> ");
    });

    // create the user first, we'll need the id
    userData.userName = userName;
    userData.password = pwd;
    $.post("api/users.php",
        JSON.stringify(userData),
        userSuccess,
        "json");
            
});

function userSuccess (data) {
    $("#div_info").text("Returned from the users api call: "+JSON.stringify(data));
    // Add the patient info for the user.
    patientData.userId = data.id;

    $.post("api/patients.php",
        JSON.stringify(patientData),
        patientSuccess,
        "json");
}

function patientSuccess (data) {
    patientForm.submit();
}