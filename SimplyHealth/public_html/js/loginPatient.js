/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var LoginForm;

$( document ).ready(function() {
    $( document ).ajaxError(function( event, jqxhr, settings, thrownError ) {
        alert( settings.url + ": "+thrownError );
    });
    
    $("#formLogin").submit( function(e) {
        e.preventDefault();
        LoginForm = this;

        var userData = new Object();
        userData.username = $("#inputUserName").val();
        userData.pwd = $("#inputPassword").val();

        $.post(
            "/php/myLogin.php",
            JSON.stringify(userData),
            function(response) {
               LoginForm.submit();
            });
    });
 });
