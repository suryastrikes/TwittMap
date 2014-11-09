function initialize(array) {
			var latlng = new google.maps.LatLng(40.6923380,-73.9873420);
			var myOptions = {
				zoom: 4,
				center: latlng,
				mapTypeId: google.maps.MapTypeId.ROADMAP
			};

			var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
			for (var i = 0; i < array.length; ++i) {
				//var point = new google.maps.LatLng(array[i][0],array[i][1]);
				tweet = array[i][0];
				myLat = array[i][1];
				myLng = array[i][2];
				var marker = new google.maps.Marker({
			      position: {lat: myLat, lng: myLng},
			      map: map,
			      title: tweet
			  	});
			}
		}