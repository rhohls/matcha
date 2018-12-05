function logOut(){
	var response = confirm("You are about to be logged out");
	if (response == true) {
		window.location.replace("logout.php");
	} else {
		window.location.href = "index.php";
	}
}

function replaceImage(src){
	var context = document.getElementById('edit_canvas').getContext("2d");
		
	var img = new Image();
	img.onload = function () {
		context.drawImage(img, 0, 0, 400, 300);
	}
	img.src = src;
}

function deleteImage(img_id){
	var response = confirm("Are you sure you want to delete this image?");
	if (response == true) {

		var XHR = new XMLHttpRequest();
		XHR.addEventListener('load', function(event) {
			if (this.response)
				alert(this.response);
			else
				alert("Deleted");
		});
		XHR.addEventListener('error', function(event) {
		alert('Oops! Something went wrong.');
		});
		XHR.open('POST', 'deleteimage.php');
		XHR.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		XHR.send("id=" + img_id);

		location.reload();
	}
}

function getLocation(){
	console.log("getting loca");
	if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);
    } else { 
        alert("Geolocation is not supported by this browser.");
	}
}

function showPosition(position) {
	console.log("setting pos");

	document.getElementById("lat").value = (position.coords.latitude).toFixed(4);
	document.getElementById("long").value = (position.coords.longitude).toFixed(4);
}
