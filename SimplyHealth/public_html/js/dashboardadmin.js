/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function loadAdminDashboard() {
    var userName = getFullName();
    alert(userName);
    document.getElementById('hellotag').innerHTML = 'Hello ' + userName + '!';
}

function getFullName() {
    var userName = "";
    var getUserURL = "php/UserFunctions.php?action=getFullName";
    var result = true;
    $.ajax({
        url:getUserURL,
        async: false,
        success: function (response) {
            var json = JSON.parse(response);
            if(json.success == 1) {
                result = true;
                userName = json.message;
            }
            else {
                result = false;
            }
        },
        error: function () {
            alert("Error: Unable to get loggedin user!");
            result = false;
        }
    });
    if(userName == "") {
        alert("Error: User does not logged-in!");
    }
    return userName;
};


