/* 
	Document   : JS
	Author     : PROEDAYAN
	Template   : Basmil Responsive HTML Template
	URL        :
*************************************************************/
	  
/*
Call to rlightbox 
******************************************/
jQuery(document).ready(function() {
	

/*
Converts top menu to a dropdown list for small screens
******************************************/
domready(function(){
			
		selectnav('nav', {
			label: 'Navigate',
			nested: true,
			indent: '-'
		});
				
	});
	
/*	
*	Footer contact widget
***********************************************/
$("#contact-form-widget").click(function() {
	var result = true;
	
	// Collecting input values
	var name = $('input[name=widget-contact-name]');
	var email = $('input[name=widget-contact-email]');
	var message = $('textarea[name=widget-contact-message]');
	
	 // Email validation
	if(!emailValidation(email, /^([0-9a-zA-Z]([-\.\w]*[0-9a-zA-Z])*@([0-9a-zA-Z][-\w]*[0-9a-zA-Z]\.)+[a-zA-Z]{2,9})$/)) 
		result = false;
	// Message Validateion	
	 if(!messageValidation(message)) 
		result = false;
			
	 if(result == false) 
		return false;
			
	// Organize data for sending
	var data = 'name=' + name.val() + '&email=' + email.val() + '&message='  + encodeURIComponent(message.val());
		
	// Disable contact form fields
	$('.contact-fields').attr('disabled','true');
	
	// Sending icon
	$('.sending_widget').show();		
	
	// Sending email
	$.ajax({
	
		// PHP process script for sending actual email
		url: "contact.php",	
		type: "POST",
		data: data,		
		cache: false,
		success: function (response) {
			// contact.php returns either 1 or 0
			if (response == 1) {
				// Sending icon
				$('.sending_widget').fadeOut('slow');
				// Enable input fields
				$('.contact-fields').removeAttr("disabled", "disabled");
				// Response message
				alert("Thank you! You message has been sent.");
				// Clear input fields
				$('#widget-form').find("input[type=text], textarea").val("");

				
			} else {
				// Sending icon
				$('.sending_widget').fadeOut('slow');
				alert("Error: Email message not sent, pleaes try again.");			
		   }
		}		
	});

	// Function to validate email	
	function emailValidation(email,regex) {
		if (email.val() == '') {
			$('#widget-email-label').text(" Email* (Required)");	
			return false;
		} else if (!regex.test(email.val())) {
			$('#widget-email-label').text(" Email* (Invalid)");	
			return false;
		}
		else {
			$('#widget-email-label').text(" Email*");
			return true;
		}
	}
	// Function to validate message		 
	function messageValidation(message) {
		if (message.val() == '') {
			$('#widget-message-label').text(" Message* (Required)");	
			return false;
		} else {
			$('#widget-message-label').text(" Message*");
			return true;
		}
	}
		return false;

});
	
	
});

