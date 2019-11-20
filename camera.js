(function() {
	var canvas = document.getElementById('canvas'),
		baseImg = canvas.getContext('2d'),
		canvasOverlay1 = document.getElementById('canvasOverlay1'),
		overLayImg1 = canvasOverlay1.getContext('2d'),
		overlay = document.getElementById('overlay'),
		pen = document.getElementById('pen'),
		dom = document.getElementById('dom'),
		ball = document.getElementById('ball'),
		fat = document.getElementById('fat'),
		video = document.getElementById('video'),
		post = document.getElementById('post'),
		vendorUrl = window.URL || window.webkitURL,
		captureBtn = document.getElementById('capture');
	
	captureBtn.addEventListener("click", Snap);
​
	navigator.getMedia = 	navigator.getUserMedia ||
							navigator.webkitGetUserMedia ||
							navigator.mozGetUserMedia ||
							navigator.msGetUserMedia ||
							navigator.oGetUserMedia;
	if (navigator.getUserMedia) {
			navigator.getUserMedia({video: true, audio: false}, 
				handleVideo, videoError);
		}	
		function handleVideo(stream) {
			video.srcObject = stream;
		}
		function videoError(error) {
			// An error occured
			// error.code
		}
​
	pen.addEventListener('click', function() {
		overlay.src = "../meme/meme1.png";
	});
​
	dom.addEventListener('click', function() {
		overlay.src = "../meme/meme2.png";
	});
​
	ball.addEventListener('click', function() {
		overlay.src = "../meme/meme3.png";
	});
​
	fat.addEventListener('click', function() {
		overlay.src = "../meme/meme4.png";
	});
​
​
		
	function Snap() {
		overLayImg1.clearRect(0, 0, 75, 75);
		baseImg.drawImage(video, 0, 0, canvas.width, canvas.height);
		overLayImg1.drawImage(overlay, 0, 0, 75, 75);
​
	}
​
	post.addEventListener('click', function () {
		
		var L1 = canvas.toDataURL();
​
		const url = "imgProcessor.php";
		var	xhttp = new XMLHttpRequest();
		var	contents = "baseImg="+L1;
		xhttp.open("POST", url, true);
		xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xhttp.onreadystatechange = function() {
			if (xhttp.readyState == 4 && xhttp.status == 200) {
				var response = xhttp.responseText;
				console.log(response);
			}
		}
		xhttp.send(contents);
	});
​
})();