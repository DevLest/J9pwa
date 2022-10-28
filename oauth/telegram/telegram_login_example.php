<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
</head>
<body>
	<button id="telegram" onclick="return login_telegram()">telegram</button>

	<script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://telegram.org/js/telegram-widget.js"></script>
    <script type="text/javascript">
    	function login_telegram() {
    		window.Telegram.Login.auth(
			  { bot_id: '5664886742', request_access: true },
			  (data) => {
			    if (!data) {
			      alert("Unauthorize");
			    }
			    // console.log(data)
			    $.ajax({
			      type: "GET",
				  url: "authorize.php",
				  data: data,
				  cache: false,
				  success: function(response){
					response = jQuery.parseJSON(response);  
					  
				  	$.ajax({
				  	type: "GET",
					 	url: response.info.url,
					 	data: {
					 		auth_id: response.info.auth_id
					 	},
					 	cache: false,
					 	success: function(response){
					 		console.log(response)
					 	}
					});
					
				  }
				});
			  }
			);
    	}
    	
    </script>


</body>
</html>