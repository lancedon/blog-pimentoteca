const FBappId = '506814939705954';
const FBaccessToken = '57532cae9b1cb2f2938da5791df1c238';


 		 var FB_status;
       	 var iniciar_teste;

 		 //$( document ).ready(function() {
       	  
		  window.fbAsyncInit = function() {
		    FB.init({
		      appId      : FBappId,
		      xfbml      : true,
		      version    : 'v2.8'
		    });

		
		    FB.getLoginStatus(function(response) {

			    FB_status = response.status; 
			    console.log(response);
			    if (FB_status === "connected"){

			    	document.cookie = FBappId+'='+response.authResponse.userID+"; path=/";
					document.cookie = FBaccessToken+'='+response.authResponse.accessToken+"; path=/";

			    }
			});    

			 iniciar_teste = function(destino){

			 		console.log(FB_status);
			 		if (FB_status === "connected"){

			 				//console.log(document.cookie);
			 				//alert(document.cookie);

			 				 window.location.href=destino;

			      	}else{

						 FB.login( function(response){
							
						    if (response.authResponse) {
						        document.cookie = FBappId+'='+response.authResponse.userID+"; path=/";
						        document.cookie = FBaccessToken+'='+response.authResponse.accessToken+"; path=/";

						        window.location.href = destino;
						    }
						    
						}, {scope: 'user_photos'});  //publish_actions

			      	}
			};

		   };
		//});