/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var adminForm;
var todayApptData = new Object();
var curApptData = new Object();
var pendingApptData = new Object();
var curPendingApptData = new Object();

$(document).ready(function() {
    // generic ajax error handler
//    $( document ).ajaxError(function( event, jqxhr, settings, thrownError ) {
//       alert( settings.url + ": "+thrownError + " : " + ((typeof jqxhr.responseJSON) === "undefined" ? jqxhr.responseText : jqxhr.responseJSON.error));
//    });

    $("#refreshTodayAppts").click(getTodayAppts);
    $("#refreshPendingAppts").click(getPendingAppts);
    $("#confirmAppt").click(confirmAppt);
    $("#inputDate").val('');
    $("#inputPatientName").val('');
    $("#inputDoctorName").val('');
    $("#inputReason").val('');

    var staffData = new Object();
    $.getJSON("api/login.php/whoami",
    function(data) {
        staffData.id = data.staff.id;
        staffData.firstName = data.staff.firstName;
        $("#pGreeting").text("Hello " + staffData.firstName + "!");
        getTodayAppts();
        getPendingAppts();
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

function getTodayAppts(event) {
    $('#todayApptTable tr').slice(1).remove();
    $.getJSON("api/appts.php/today",
    function(data) {
        todayApptData = JSON.parse(JSON.stringify(data));
        for(var i = 0; i < todayApptData.length; i++) {
            curApptData = todayApptData[i];
            addRowIntoTodayTable(i);
        }
    })
    .fail(showAjaxError);
    $("#todayApptTable").on("click", ".BtnCheckIn", updateCheckInInfo);
    $("#todayApptTable").on("click", ".BtnCheckOut", updateCheckOutInfo);
}

function addRowIntoTodayTable(i) {
    var checkinHtml = '<td width="10%" class="textCheckIn"><input type="button" class="btn btn-primary BtnCheckIn" value="Check-in" /></td>';
    var checkoutHtml = '<td width="10%" class="textCheckOut"><input type="button" class="btn btn-primary BtnCheckOut" value="Check-out" disabled /></td>';
    if(curApptData.check_in != null) {
        checkinHtml = '<td width="15%">' + curApptData.check_in + '</td>';
        checkoutHtml = '<td width="10%" class="textCheckOut"><input type="button" class="btn btn-primary BtnCheckOut" value="Check-out"/></td>';
    }
    if(curApptData.check_out != null) {
        checkoutHtml = '<td width="15%">' + curApptData.check_out + '</td>';
    }
    var html = '<tr>' +
                '<td width="15%">' + curApptData.date + '</td>' +
                '<td width="20%">' + curApptData.patient_name + '</td>' +
                '<td width="20%">' + curApptData.doctor_name + '</td>' +
                '<td width="25%">' + curApptData.reason + '</td>' +
                checkinHtml + 
                checkoutHtml +
                '</tr>';
    $(html).appendTo($("#todayApptTable"));
};

function getPendingAppts(event) {
    $("#confirmAppt").prop("disabled", true);
    $('#pendingApptTable tr').slice(1).remove();
    $.getJSON("api/appts.php/pending",
    function(data) {
        pendingApptData = JSON.parse(JSON.stringify(data));
        for(var i = 0; i < pendingApptData.length; i++) {
            curPendingApptData = pendingApptData[i];
            addRowIntoPendingTable();
        }
    })
    .fail(showAjaxError);
    $("#pendingApptTable").on("click", ".BtnConfirm", confirmPendingAppt);
    $('.form_datetime').datetimepicker({
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        forceParse: 0,
        showMeridian: 1
    });
    
}

function addRowIntoPendingTable() {
    var html = '<tr>' +
                '<td width="15%" class="ids hide" >' + curPendingApptData.appt_id + '</td>' +
                '<td width="15%" class=dates>' + curPendingApptData.date + '</td>' +
                '<td width="20%" class=pname>' + curPendingApptData.patient_name + '</td>' +
                '<td width="20%" class=dname>' + curPendingApptData.doctor_name + '</td>' +
                '<td width="25%" class=reason>' + curPendingApptData.reason + '</td>' +
                '<td width="10%" class="textConfirm"><input type="button" class="btn btn-primary BtnConfirm" value="Confirm" /></td>'; + 
                '</tr>';
    $(html).appendTo($("#pendingApptTable"));
};

function updateCheckInInfo() {

    var curTime = new Date($.now());
    var date = curTime.getFullYear() + ":" + (curTime.getMonth()+1) + ":" + curTime.getDate();
    var hours = curTime.getHours() < 10 ? '0' + curTime.getHours() : curTime.getHours();
    var minutes = curTime.getMinutes() < 10 ? '0' + curTime.getMinutes() : curTime.getMinutes();
    var seconds = curTime.getSeconds() < 10 ? '0' + curTime.getSeconds() : curTime.getSeconds();
    var time = hours + ":" + minutes + ":" + seconds;
    var innerHtml = '<td width="15%">' + time + '</td>';
    $(".textCheckIn").html(innerHtml);
    var checkoutHtml = '<td width="10%" class="textCheckOut"><input type="button" class="btn btn-primary BtnCheckOut" value="Check-out"/></td>';
    $(".textCheckOut").html(checkoutHtml);
    $(".BtnCheckOut").click(updateCheckOutInfo);

    curApptData.check_in = date + " " + time;
    $.ajax({
        method: "PUT",
        url: "api/appts.php/" + curApptData.appt_id,
        async: false,
        data: JSON.stringify(curApptData)
    })
    .done(function( data ) {
        //showAlert("Appointment Check-In has been updated",false);
        alert("Check-In time has been updated for the appointment!")
    })
    .fail(function( jqXHR, textStatus ) {
        showAlert((typeof jqxhr.responseJSON) === "undefined" ? jqxhr.responseText : jqxhr.responseJSON.error, true);
    });
}

function updateCheckOutInfo() {
    var curTime = new Date($.now());
    var date = curTime.getFullYear() + ":" + (curTime.getMonth()+1) + ":" + curTime.getDate();
    var hours = curTime.getHours() < 10 ? '0' + curTime.getHours() : curTime.getHours();
    var minutes = curTime.getMinutes() < 10 ? '0' + curTime.getMinutes() : curTime.getMinutes();
    var seconds = curTime.getSeconds() < 10 ? '0' + curTime.getSeconds() : curTime.getSeconds();
    var time = hours + ":" + minutes + ":" + seconds;
    var innerHtml = '<td width="15%">' + time + '</td>';
    $(".textCheckOut").html(innerHtml);

    curApptData.check_out = date + " " + time;
    $.ajax({
        method: "PUT",
        url: "api/appts.php/" + curApptData.appt_id,
        async: false,
        data: JSON.stringify(curApptData)
    })
    .done(function( data ) {
        //showAlert("Appointment Check-In has been updated",false);
        alert("Check-Out time has been updated for the appointment!")
    })
    .fail(function( jqXHR, textStatus ) {
        showAlert((typeof jqxhr.responseJSON) === "undefined" ? jqxhr.responseText : jqxhr.responseJSON.error, true);
    });
    
}

function confirmPendingAppt() {
    curPendingApptData.appt_id = $(this).parent().siblings('.ids').text();
    curPendingApptData.date = $(this).parent().siblings('.dates').text();
    curPendingApptData.patient_name = $(this).parent().siblings('.pname').text();
    curPendingApptData.doctor_name = $(this).parent().siblings('.dname').text();
    curPendingApptData.reason = $(this).parent().siblings('.reason').text();
    
    $("#confirmAppt").prop("disabled", false);
    var innerHtml = '<td width="10%" class="textConfirm"><input type="button" class="btn btn-primary BtnConfirm" value="Confirm" disabled /></td>';
    //$(".textConfirm").html(innerHtml);
    $(this).parent().html(innerHtml);

    $("#inputDate").val(curPendingApptData.date);
    $("#inputPatientName").val(curPendingApptData.patient_name);
    $("#inputDoctorName").val(curPendingApptData.doctor_name);
    $("#inputReason").val(curPendingApptData.reason);

    //var innerHtml = '<td width="15%">' + time + '</td>';
    //$(".textCheckIn").html(innerHtml);
}

function confirmAppt() {
    /*
    var curTime = new Date($.now());
    var date = curTime.getFullYear() + ":" + (curTime.getMonth()+1) + ":" + curTime.getDate();
    var hours = curTime.getHours() < 10 ? '0' + curTime.getHours() : curTime.getHours();
    var minutes = curTime.getMinutes() < 10 ? '0' + curTime.getMinutes() : curTime.getMinutes();
    var seconds = curTime.getSeconds() < 10 ? '0' + curTime.getSeconds() : curTime.getSeconds();
    var time = hours + ":" + minutes + ":" + seconds;
    var innerHtml = '<td width="15%">' + time + '</td>';
    $(".textCheckOut").html(innerHtml);

    curApptData.check_out = date + " " + time;
    $.ajax({
        method: "PUT",
        url: "api/appts.php/" + curApptData.appt_id,
        async: false,
        data: JSON.stringify(curApptData)
    })
    .done(function( data ) {
        //showAlert("Appointment Check-In has been updated",false);
        alert("Check-Out time has been updated for the appointment!")
    })
    .fail(function( jqXHR, textStatus ) {
        showAlert((typeof jqxhr.responseJSON) === "undefined" ? jqxhr.responseText : jqxhr.responseJSON.error, true);
    });
    */
    
}
