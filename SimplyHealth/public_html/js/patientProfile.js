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

    $.getJSON("/api/users.php/me",
      function(data) {
          patientData.id = data.patient.id;
          patientData.firstName = data.patient.firstName;
          $("#pGreeting").text("Hello "+patientData.firstName);
          getMyPatientData();
      });
});

function getMyPatientData () {
    $.getJSON("/api/patients.php/id/"+patientData.id,
    function(data) {
        $("#spanAlert").html("Data retrievied for "+data.firstName + " " + data.lastName+"<br/>"+JSON.stringify(data));
    });
}

