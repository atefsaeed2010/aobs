<?php


// Server Information - Local
$aobs_db['dbHost'] = "localhost";
$aobs_db['dbName'] = "asterisk"; // Database Name
$aobs_db['dbUser'] = "root"; // username to access the database
$aobs_db['dbPass'] = "lukesulek"; // password to access the database

// MySQL Database tables
$aobs_tbl['user'] 	= "users";
$aobs_tbl['cdr'] 	= "cdr";
$aobs_tbl['admin'] 	= "admins";
$aobs_tbl['tickets'] 	= "ticket";
$aobs_tbl['ticket_msgs'] 	= "ticket_convo";


// Minute charge
$aobs_chr['LDC'] = "0.25";	// Long distance
$aobs_chr['INT'] = "0.00";	// Interal
$aobs_chr['ICM'] = "0.00";	// Incoming
$aobs_chr['CNF'] = "0.00";	// Conference


// Misc settings
$aobs_dtl['title'] 	= "Asterisk";
$aobs_dtl['folder']	= "hQ";
$aobs_dtl['source'] 	= "html";
$aobs_dtl['intrim']	= "5";
$aobs_dtl['project'] = "Asterisk by Sam & Luke";
$aobs_dtl['cookies']	= 3600; // Set cookies time for user
$aobs_dtl['display'] = 5; // This is the number of record display per page


// System messages, used globally
$aobs_msg['Ticket_Submitted'] = 	"Your request for support has been received. For further inquiries regarding this request, please reference ticket# $uTicket_ID.";
$aobs_msg['auth_login'] = 	"Congratulation! You have successfully entered client area as <b>".$_POST['username']."</b>. ".
							"Please stand by while the system is redirecting you to the main page";
$aobs_msg['auth_logout']	= 	"You have successfully loged out of the member area. Please keep in mind that you should never share ".
							"your login information with any other person. Please stand by while the system is redirecting you to the main page";
$aobs_msg['auth_password'] =	"Oops! You have entered an incorrct password for username <b>".$_POST['username']."</b>. ".
							"You must provide correct information in order to access the client area. ".
							"The system will redirect you back to the login page in 5 seconds to attempt another authentication process, ".
							"or click <a href=\"javascript:history.go(-1)\" class=\"url\">HERE</a> if you do not wish to wait";
$aobs_msg['auth_username'] =	"Oops! The username <b>".$_POST['username']."</b> you entered was not found in our database. ".
							"You must provide correct information in order to access the client area. ".
							"The system will redirect you back to the login page in 5 seconds to attempt another authentication process";
$aobs_msg['auth_cookies'] =	"Oops! Your session has been expired. For security purposes, you are required to login again. ".
							"The system will redirect you back to the login page in 5 seconds to attempt authentication process, ".
							"or click <a href=\"?aobs=auth&tab=\" class=\"url\">HERE</a> if you do not wish to wait";
$aobs_msg['reg_username'] =	"Oops! The username <b>".$_POST['username']."</b> you entered has been taken by another user. ".
							"The system will redirect you back to retry, or click <a href=\"javascript:history.go(-1)\" class=\"url\">HERE</a> ".
							"if you do not wish to wait. Come on, be creative!";
$aobs_msg['reg_password'] =	"Oops! The passwords you have entered do not match each other. The system will redirect you back to retry. ".
							"Or click <a href=\"javascript:history.go(-1)\" class=\"url\">HERE</a> if you do not wish to wait";
$aobs_msg['reg_terms'] =		"Oops! You have not agree to our terms of services. It's required that our customer to agree with our terms in order ".
							"to provide you the best services. Please click <a href=\"javascript:history.go(-1)\" class=\"url\">HERE</a> ".
							"to go back and review this";
$aobs_msg['reg_fields'] =	"Oops! You have not entered all the required fields. The system will redirect you back to retry. ".
							"Or click <a href=\"javascript:history.go(-1)\" class=\"url\">HERE</a> if you do not wish to wait. Come on, don't be lazy!";
$aobs_msg['reg_done'] =		"Confratulation! You have successfully register with new username of <b>".$_POST['username']."</b>. ".
							"Please stand by while the system is redirecting you to the login page";
$aobs_msg['change_agree'] =	"Oops! You have not confirm your request. It's required that our customer to confirm all the changes before".
							"we process them. Please click <a href=\"javascript:history.go(-1)\" class=\"url\">HERE</a> ".
							"to go back and review this";
$aobs_msg['change_done'] =	"Confratulation! You have successfully udpate information for username of <b>".$_POST['username']."</b>. ".
							"Please stand by while the system is redirecting you to the main page";
$aobs_msg['change_failed'] =	"Oops! We cannot process your request at the momen. Please go <a href=\"javascript:history.go(-1)\" class=\"url\">BACK</a> ".
							"and try again later";
$aobs_msg['invalid_url_ticket'] =	"You have typed in an invalid URL. Redirecting you back to your previous page. ";
$aobs_msg['Ticket_Updated'] =	"Your ticket has been updated. Refreshing.";
?>