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
    		$.ajax({
			    type: "GET",
				url: "authorize.php",
				cache: false,
				success: function(response){
				  	response = $.parseJSON(response);
				  	window.open(response.info.url)

		    		$.ajax({
					    type: "GET",
						url: "callback.php",
						data: {auth_id: response.info.id},
						cache: false,
						success: function(response){
							console.log(response);
						}
					});
				}
			});
    	}
    	
    </script>


</body>
</html>