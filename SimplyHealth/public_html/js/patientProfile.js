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
    $("#updBiographic").click(function() {
        // Update the patientData object from the form
        patientData.firstName   = $("#inputFirstName").val();
        patientData.lastName    = $("#inputLastName").val();
        patientData.email       = $("#inputEmail").val();
        patientData.address1    = $("#inputAddress1").val();
        patientData.address2    = $("#inputAddress2").val();
        patientData.city        = $("#inputCity").val();
        patientData.state       = $("#inputState").val();
        patientData.zipCode     = $("#inputZipCode").val();
        alert(JSON.stringify(patientData));
        $.ajax({
            type: "PUT",
            url: "/api/patients.php/"+patientData.id,
            data: JSON.stringify(patientData),
            success: function(data,status,jqxhr) {
                $("#statusBiographic").toggleClass("alert-danger",false);
                $("#statusBiographic").toggleClass("alert-info",true);
                $("#statusBiographic").html("Your profile has been updated");
            },
            error: function(jqxhr, status, error) {
                $("#statusBiographic").toggleClass("alert-danger",true);
                $("#statusBiographic").toggleClass("alert-info",false);
                $("#statusBiographic").html("Error updating your profile: "+error);                
            },
            dataType: "json"
        });

    });

    $.getJSON("/api/users.php/me",
      function(data) {
          patientData.id = data.patient.id;
          patientData.firstName = data.patient.firstName;
          $("#pGreeting").text("Hello "+patientData.firstName);
          getMyPatientData();
      });
});

function getMyPatientData () {
    $.getJSON("/api/patients.php/"+patientData.id,
    function(data) {
        //$("#spanAlert").html("Data retrievied for "+data.firstName + " " + data.lastName+"<br/>"+JSON.stringify(data));
        patientData = JSON.parse(JSON.stringify(data));
        // Load the profile form
        $("#inputFirstName").val(patientData.firstName);
        $("#inputLastName").val(patientData.lastName);
        $("#inputEmail").val(patientData.email);
        $("#inputAddress1").val(patientData.address1);
        $("#inputAddress2").val(patientData.address2);
        $("#inputCity").val(patientData.city);
        $("#inputState").val(patientData.state);
        $("#inputZipCode").val(patientData.zipCode);
    });
}

