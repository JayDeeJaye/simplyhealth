/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var patientData = new Object(); 
var patientForm;

$(document).ready(function() {
    // generic ajax error handler
    $( document ).ajaxError(function( event, jqxhr, settings, thrownError ) {
       alert( settings.url + ": "+thrownError + " : " + ((typeof jqxhr.responseJSON) === "undefined" ? jqxhr.responseText : jqxhr.responseJSON.error));
    });

    // Section update control handlers
    $("#updBiographic").click(putPatientData);
    $("#updEmergencyContact").click(putPatientData);

    $.getJSON("/api/users.php/me",
      function(data) {
          patientData.id = data.patient.id;
          patientData.firstName = data.patient.firstName;
          $("#pGreeting").text("Hello "+patientData.firstName);
          getMyPatientData();
      });
    
});

function showAlert (message,isError) {
    if ( ! $( "#divAlert" ).length ) {
        $("<div></div>", {
            "class": "alert",
            "role": "alert",
            id: "divAlert",
            html: '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><span id="spanAlert"></span>' 
        }).appendTo( "#divPopup");
    }
    $("#divAlert").toggleClass("alert-info",!isError);
    $("#divAlert").toggleClass("alert-danger",isError);
    $("#spanAlert").html(message);
}

function getMyPatientData (event) {
    $.getJSON("/api/patients.php/"+patientData.id,
    function(data) {
        //$("#spanAlert").html("Data retrievied for "+data.firstName + " " + data.lastName+"<br/>"+JSON.stringify(data));
        patientData = JSON.parse(JSON.stringify(data));
        // Load the profile form
        $("#inputFirstName").val(patientData.firstName);
        $("#inputLastName").val(patientData.lastName);
        $("#inputEmail").val(patientData.email);
        $("#inputPhone").val(patientData.phone);
        $("#inputAddress1").val(patientData.address1);
        $("#inputAddress2").val(patientData.address2);
        $("#inputCity").val(patientData.city);
        $("#inputState").val(patientData.state);
        $("#inputZipCode").val(patientData.zipCode);
        $("#ec_name").val(patientData.emergencyContactName);
        $("#ec_phone").val(patientData.emergencyContactPhone);
    });
}

function putPatientData (event) {
    // Update the patientData object from the form
    patientData.firstName               = $("#inputFirstName").val();
    patientData.lastName                = $("#inputLastName").val();
    patientData.email                   = $("#inputEmail").val();
    patientData.phone                   = $("#inputPhone").val();
    patientData.address1                = $("#inputAddress1").val();
    patientData.address2                = $("#inputAddress2").val();
    patientData.city                    = $("#inputCity").val();
    patientData.state                   = $("#inputState").val();
    patientData.zipCode                 = $("#inputZipCode").val();
    patientData.emergencyContactName    = $("#ec_name").val();
    patientData.emergencyContactPhone   = $("#ec_phone").val();
    //alert(JSON.stringify(patientData));
    $.ajax({
        type: "PUT",
        url: "/api/patients.php/"+patientData.id,
        data: JSON.stringify(patientData),
        success: function(data,status,jqxhr) {
//            $("#divAlert").toggleClass("hidden",false);
//            $("#divAlert").toggleClass("alert-danger",false);
//            $("#divAlert").toggleClass("alert-info",true);
            showAlert("Your profile has been updated",false);
        },
        error: function(jqxhr, status, error) {
//            $("#divAlert").toggleClass("hidden",false);
//            $("#divAlert").toggleClass("alert-danger",true);
//            $("#divAlert").toggleClass("alert-info",false);
            showAlert("Error updating your profile: "+error,true);                
        },
        dataType: "json"
    });
}

