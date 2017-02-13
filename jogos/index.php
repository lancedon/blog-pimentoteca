

<div>
<input type="button" id="bt-facebook" value='face test'>   
</div>

<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '701376056703205',
      xfbml      : true,
      version    : 'v2.3'
    });
  };
 
  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
</script>

<script>
$('#bt-facebook').click( function(event){
  console.log('aqui');

    event.preventDefault();    
    destino = '/blog/jogos/teste.php';
 
    FB.login( function(response){
        if (response.authResponse) {
            document.cookie = '701376056703205='+response.authResponse.userID;
            document.cookie = 'fc34b10471c3e19d03e1c7c80bbbe3fd='+response.authResponse.accessToken;
            window.location.href = destino;
        }else{
            // console.log('O usuário Cancelou o login ou não autozirou.');
        }
    }, {scope: 'user_photos, publish_actions'});    
});

</script>
