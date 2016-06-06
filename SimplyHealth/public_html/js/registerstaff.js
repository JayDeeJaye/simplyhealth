/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function registerstaff() {
    var fname = document.getElementById('inputFirstName').value;
    var lname = document.getElementById('inputLastName').value;
    var email = document.getElementById('inputEmail').value;
    var username = document.getElementById('inputUserName').value;
    var pwd = document.getElementById('inputPassword').value;
    var confirmpwd = document.getElementById('inputConfirmPassword').value;
    var address1 = document.getElementById('inputAddress1').value;
    var address2 = document.getElementById('inputAddress2').value;
    var city = document.getElementById('inputCity').value;
    var state = document.getElementById('inputState').value;
    var zipcode = document.getElementById('inputZipcode').value;
    var role = "Admin";
    
    var passwordField = $("#inputPassword");
    var conifrmPasswordField = $("#inputConfirmPassword");

    if(pwd !== confirmpwd) {
        passwordField.val('');
        conifrmPasswordField.val('');

        alert("Error: Password does not match!");
//        showError("errorDiv", "block", "Error: Password does not match!");
        return false;
    }
    else {
        var createUserURL = "php/UserFunctions.php?fname=" + fname + "&lname=" + lname + "&email=" + email + "&username=" + username + 
            "&password=" + pwd + "&address1=" + address1 + "&address2=" + address2 + "&city=" + city + "&state=" + state + 
            "&zipcode=" + zipcode + "&role=" + role + "&action=create";
        var result = true;
        var message = "";
        $.ajax({
            url:createUserURL,
            async: false,
            success: function (response) {
                var json = JSON.parse(response);
                if(json.success == 0) {
                    result = true;
                }
                else {
                    result = false;
                }
                message = json.message;
            },
            error: function () {
                alert("Error: Unable to register the user!");
//                showError("errorDiv", "block", "Error: Unable to register the user!");
                result = false;
            }
        });
        alert(message);
        if(result == true) {
            return true;        
        } else {
//            showError("errorDiv", "block", message);
            return false;
        }
    }
};



