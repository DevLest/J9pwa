<script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>
<script type="text/javascript">
	var arr = [];
	$.getJSON("not_playable_games2.json", function(data) {
		var count = 0;
		$.each(data, function(i, val) {
			
			$.ajax({
			    type: "POST",
				url: "https://999j9azx.u2d8899.com/j9pwa/request.php",
				data: {
					type: "play_game",
					gameCode: val.code,
					gameCodeAlias: val.alias_code,
					jackpot_amount: 0,
					currency: "USD",
					auth: "40b372d1d7706ab9528f39a0b434edec",
					username_email: "test@emailgmail.com",
					password: 123456
				},
				cache: false,
				success: function(response){
					var data = $.parseJSON(response);
					arr.push({
						"name": val.name,
						"platform": val.platform,
						"status": "Not playable",
						"URL": data.info
					});
					count = count + 1;
					console.log(count)
					if(count > 37) {
						console.log(arr)
					}
				}
			});
		});
		

	}).fail(function () {
		console.log("Error!");
	});

	
</script>