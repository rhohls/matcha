(function(){
	var video = document.getElementById("video_player");
	var vendorURL = window.URL || window.webkitURL;
	var canvas = document.getElementById('canvas');
	var context = canvas.getContext('2d');

	navigator.getMedia =	navigator.getUserMedia ||
							navigator.webkitGetUserMedia ||
							navigator.mozGetUserMedia ||
							navigator.msGetUserMedia;

	navigator.getMedia(
	{
		video: true,
		audio: false
	}, function (stream){
		video.srcObject = stream;
		video.play();
	},function (error){
		
	});

	document.getElementById('capture').addEventListener('click', function() {
	context.drawImage(video, 0, 0, 400, 300);
	var btn = document.getElementById("save_button");
	btn.disabled = false;
	});

	// document.getElementById('save_edit').addEventListener('click', function() {
	// context.drawImage(video, 0, 0, 400, 300);
	// });
})()

function sendData() {
	var XHR = new XMLHttpRequest();
	var canvas = document.getElementById('canvas');
	var img_data = canvas.toDataURL("image/png");

	

	XHR.addEventListener('load', function(event) {
		if (this.response)
			alert(this.response);
		else
			alert("Uploaded");
	});
	XHR.addEventListener('error', function(event) {
	alert('Oops! Something went wrong.');
	});
	XHR.open('POST', 'save_pic.php');
	XHR.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	XHR.send("img=" + img_data);
};

