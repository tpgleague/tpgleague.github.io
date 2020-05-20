function check(form) {

	if (form.ticket_email.value == "") {

		alert ("Please provide your email address.");
		form.ticket_email.focus();
		return false;

	}

	var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
	
	if (! form.ticket_email.value.match(re)) {
	
		alert("Please use a valid email address");
		form.ticket_email.focus();
		form.ticket_email.select();
		return (false);
	
	}

	if (form.ticket_subject.value == "") {

		alert ("Please include a subject for your support request.");
		form.ticket_subject.focus();
		return false;

	}

	if (form.ticket_desc.value == "") {

		alert ("Please include a description of your support request.");
		form.ticket_desc.focus();
		return false;

	}

	else return true;

	}	

