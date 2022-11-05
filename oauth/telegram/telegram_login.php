<!DOCTYPE html>
<html>
<head>
	<title>Login using telegram</title>
</head>
<body onclick="return login_telegram()">
	<!-- <button id="btn" onclick="return login_telegram()" style="visibility: hidden;"></button> -->

	<script type="text/javascript" src="https://telegram.org/js/telegram-widget.js"></script>
	<script type="text/javascript">
		login_telegram()

		function login_telegram() {
    		window.Telegram.Login.auth(
			  { bot_id: '5664886742', request_access: true },
			  (data) => {
			    if (!data) {
			      alert("Unauthorize");
			      window.close();
			    }
			    else {
			    	window.location.href = 'https://999j9azx.u2d8899.com/j9pwa/oauth/telegram/callback.php?tg_data=' + JSON.stringify(data);
			    }
			  }
			);
    	}
	</script>
</body>
</html>