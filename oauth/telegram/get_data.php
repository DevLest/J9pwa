<script>
	//Decode Base64 data from URL and pass it to callback.php through cookie
	var locationHash = '', re = /[#\?\&]tgAuthResult=([A-Za-z0-9\-_=]*)$/, match;
	try {
	  locationHash = location.hash.toString();
	  if (match = locationHash.match(re)) {
		location.hash = locationHash.replace(re, '');
		var data = match[1] || '';
		data = data.replace(/-/g, '+').replace(/_/g, '/');
		var pad = data.length % 4;
		if (pad > 1) {
		  data += new Array(5 - pad).join('=');
		}
		var user = window.atob(data);
		
		window.location.href = 'https://999j9azx.999game.online/j9pwa/oauth/telegram/callback.php?tg_data=' + user;
	  }
	} catch (e) {}
</script>