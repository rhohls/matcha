
var canvas = document.getElementById("edit_canvas");
var canvas_context = canvas.getContext("2d");


function clearImage() {
canvas_context.clearRect(1, 1, 400, 300);
} 



function drawSticker(event){

	// console.log("draw the sicker");
	// console.log(event.target.src);
	var sticker_source = event.target.src;
	sticker_source = sticker_source.split("/");
	sticker_source = sticker_source[sticker_source.length - 1];


	// console.log(sticker_source);

	var sticker = new Image();
	sticker.src = "stickers/" + sticker_source;
	// context.drawImage(img,sx,sy,swidth,sheight,x,y,width,height);
	canvas_context.drawImage(sticker, 0,0, 400,300);
}

function saveEdit(baseimg_src) {
	baseimg_src = baseimg_src.trim();

	if (baseimg_src !== "noimage"){
		var XHR = new XMLHttpRequest();
		var canvas = document.getElementById('edit_canvas');
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
		XHR.send("edit=" + img_data +"&"+ "base=" + baseimg_src);
	}
	else {
		alert("please select an image to edit");

	}

};