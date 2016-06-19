/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var patientData = new Object();
var patientHistoryData = new Object();
var patientForm;
var noHistory;

$(document).ready(function() {
    // generic ajax error handler
//    $( document ).ajaxError(function( event, jqxhr, settings, thrownError ) {
//       alert( settings.url + ": "+thrownError + " : " + ((typeof jqxhr.responseJSON) === "undefined" ? jqxhr.responseText : jqxhr.responseJSON.error));
//    });

    // Section update control handlers
    $("#updBiographic").click(putPatientData);
    $("#updEmergencyContact").click(putPatientData);
    $("#updHistory").click(putPatientHistory);

    $.getJSON("api/users.php/me",
      function(data) {
          patientData.id = data.patient.id;
          patientData.firstName = data.patient.firstName;
          $("#pGreeting").text("Hello "+patientData.firstName);
          getMyPatientData();
          getMyHistoryData();
      })
      .fail(showAjaxError);
});

function showAjaxError (jqxhr, textStatus, thrownError) {
    showAlert((typeof jqxhr.responseJSON) === "undefined" ? jqxhr.responseText : jqxhr.responseJSON.error, true);
}

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
    $.getJSON(
        "api/patients.php/"+patientData.id,
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
        })
        .fail(showAjaxError);
}

function getMyHistoryData (event) {
    $.ajax({
        type: "GET",
        url: "api/patient_history.php/"+patientData.id,
        success: function(data) {
            //$("#spanAlert").html("Data retrievied for "+data.firstName + " " + data.lastName+"<br/>"+JSON.stringify(data));
            patientHistoryData = JSON.parse(JSON.stringify(data));
            // Load the profile form
            $("input[name=eczemaInd_Self][value="+patientHistoryData.eczemaSelfInd+"]").prop("checked",true);
            $("input[name=highCholInd_Self][value="+patientHistoryData.highCholSelfInd+"]").prop("checked",true);
            $("input[name=highBpInd_Self][value="+patientHistoryData.highBpSelfInd+"]").prop("checked",true);
            $("input[name=mentalInd_Self][value="+patientHistoryData.mentalSelfInd+"]").prop("checked",true);
            $("input[name=obesityInd_Self][value="+patientHistoryData.obesitySelfInd+"]").prop("checked",true);
        },
        dataType: "json"
    })
        .fail(function(jqxhr, status, thrownError) {
            if (thrownError !== "Not Found") {
                showAjaxError(jqxhr, status, thrownError);
            };
        })
    ;
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
        url: "api/patients.php/"+patientData.id,
        data: JSON.stringify(patientData),
        success: function(data,status,jqxhr) {
            showAlert("Your profile has been updated",false);
        },
        error: function(jqxhr, status, error) {
            showAlert("Error updating your profile: "+error,true);                
        },
        dataType: "json"
    });
}

function putPatientHistory (event) {
    // Update the patient history data from the form
    // First time history is POST; existing history is PUT
    var method = $.isEmptyObject(patientHistoryData) ? "POST" : "PUT";
    patientHistoryData.patientId        = patientData.id;
    patientHistoryData.eczemaSelfInd    = $('input[name=eczemaInd_Self]:checked').val();
    patientHistoryData.highCholSelfInd  = $('input[name=highCholInd_Self]:checked').val();
    patientHistoryData.highBpSelfInd    = $('input[name=highBpInd_Self]:checked').val();
    patientHistoryData.mentalSelfInd    = $('input[name=mentalInd_Self]:checked').val();
    patientHistoryData.obesitySelfInd   = $('input[name=obesityInd_Self]:checked').val();

    var ajaxSettings = new Object();
    ajaxSettings.type = method;
    ajaxSettings.url = "api/patient_history.php" + (method === "PUT" ? "/" + patientData.id : "");
    ajaxSettings.data = JSON.stringify(patientHistoryData);
    
    $.ajax(ajaxSettings)
        .done(function(data,status,jqxhr) {
        showAlert("Your profile has been updated",false);
    })
            .fail(function(jqxhr, status, error) {
        showAlert("Error updating your profile: "+error,true);                
    });
}
