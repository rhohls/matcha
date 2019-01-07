
function getUrlVars() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
        vars[key] = value;
    });
	return vars;
}

function updateChat() {
	var id = parseInt(getUrlVars()['usr_id']);

	$.post('chat_update.php?usr_id=' + id,
	{
	}).done(function(result) {
		// console.log(result);
		$("#chat-window").html(result);
	})
}

$(document).ready( function() {
	setInterval(updateChat, 1000);
});