
function updateChat() {
	$.post('chat_update.php',
	{
	}).done(function(result) {
		$("#chat-window").html(result);
	})
}

$(document).ready( function() {
	setInterval(updateChat, 1000);
});