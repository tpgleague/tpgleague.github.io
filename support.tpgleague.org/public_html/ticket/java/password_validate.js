function checkPw(form) {

	password = form.password.value;
	password2 = form.password2.value;

	if (password != password2) {

		alert ("Passwords do not match. Please try again.")
		return false;

	}

	if (form.handle.value == "") {

		alert ("Please provide a user handle.");
		form.handle.focus();
		return false;

	}

	if (form.email.value == "") {

		alert ("Please provide a user email address.");
		form.email.focus();
		return false;

	}	

	else return true;

	}	

