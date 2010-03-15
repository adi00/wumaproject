function setRealStartTime(tid){
ajax.send('twtask.php', "action=srst&tid=" + tid,
	function() {
		document.location.reload();
	});
}

function setRealEndTime(tid){
ajax.send('twtask.php', "action=sret&tid=" + tid,
	function() {
		document.location.reload();
	});
}

function showEditStatus(){
	document.getElementById('txtStatus').style.display	= 'none';
	document.getElementById('selStatus').style.display	= 'block';
}

function resetStatus(){
	document.getElementById('txtStatus').style.display	= 'block';
	document.getElementById('selStatus').style.display	= 'none';
}

function setStatus(tid){
	ajax.send('twtask.php', "action=ss&tid=" + tid+"&status="+document.getElementById('status').value,
	function() {
		document.location.reload();
	});
}