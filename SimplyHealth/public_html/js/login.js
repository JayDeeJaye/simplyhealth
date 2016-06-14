/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function userLogin() {
    var username = document.getElementById('inputUserName').value;
    var pwd = document.getElementById('inputPassword').value;

    var loginUserURL = "php/UserFunctions.php?username=" + username + "&password=" + pwd + "&action=login";
    var result = false;
    var message = "";
    $.ajax({
        url:loginUserURL,
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
            alert("Error: Unable to login the user!");
            result = false;
        }
    });
    alert(message);
    if(result == true) {
        location.href = "dashboardadmin.html?username=" + username;
        return true;
    } else {
        return false;
    }
};



