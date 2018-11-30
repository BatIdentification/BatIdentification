function signout_google(redirect){
    var auth2 = gapi.auth2.getAuthInstance();
    auth2.disconnect();
    auth2.signOut().then(function(){
      if(redirect == true){
        window.location.href="login.php";
      }
    });
}
