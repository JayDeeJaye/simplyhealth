/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$( document ).ajaxError(function( event, jqxhr, settings, thrownError ) {
    $( "#div_error" ).html( settings.url + ": "+thrownError + " <br> " + jqxhr.responseJSON.error);
});

var staffData = new Object(); 
var staffForm;
var userData = new Object();

$("form").submit(function(e) {
    e.preventDefault();

    staffForm = this;

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

    staffData.firstName = $("#inputFirstName").val();
    staffData.lastName = $("#inputLastName").val();
    staffData.email = $("#inputEmail").val();
    staffData.phone = $("#inputPhone").val();
    staffData.address1 = $("#inputAddress1").val();
    staffData.address2 = $("#inputAddress2").val();
    staffData.city = $("#inputCity").val();
    staffData.state = $("#inputState").val();
    staffData.zip = $("#inputZipcode").val();
    var roleName = $('input[name=role]:checked').val();

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
    /*
    var url = "api/roles.php/" + roleName;
    $.getJSON(url,
    function(data) {
        userData.roleId = data.id;
    })
    .fail(function(jqxhr, textStatus, error) {
        var err = textStatus + ", " + error;
        $( "#div_error" ).html( err + " <br> ");
    });
    */

    // create the user first, we'll need the id
    userData.username = userName;
    userData.password = pwd;
    $.post("api/users.php",
        JSON.stringify(userData),
        userSuccess,
        "json");
        
});

function userSuccess (data) {
//    $("#div_info").text("Returned from the users api call: "+JSON.stringify(data));
    // Add the staff info for the user.
    staffData.userId = data.id;

    $.post("api/staffs.php",
        JSON.stringify(staffData),
        staffSuccess,
        "json");
}

function staffSuccess (data) {
    staffForm.submit();
}
