
function updateNotification() {
	// console.log('notify');
	$.post('header_update.php',
	{
	}).done(function(result) {
		$("#notifications").html(result);
	})
}

$(document).ready( function() {
	setInterval(updateNotification, 1000);
});