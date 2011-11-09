<?php

if (!eregi("index.php", $_SERVER['PHP_SELF'])) {
	die();
}

if (isset($_REQUEST['tab']) && $_REQUEST['tab'] == "dne") {
	$UserQuery = $aobs->query("SELECT * FROM ".$aobs->tbl['user']." WHERE user_name='".$_POST['username']."'");
	if ($aobs->num_rows($UserQuery) > 0) {
		$aobs_tag = '<meta http-equiv="refresh" content="5; url=javascript:history.go(-1)" />';
		$MSG_Title = "Username already exists!";
		$MSG_Content =	$aobs_msg['reg_username'];
		eval ("\$LAYOUT_CONTENT = \"".import_tpl("tpl_message")."\";");
	} else {
		if (!empty($_POST['agree'])) {
			if ((!empty($_POST['username'])) && (!empty($_POST['email'])) && (!empty($_POST['password'])) && (!empty($_POST['veripass'])) && (!empty($_POST['firstname'])) && (!empty($_POST['lastname'])) && (!empty($_POST['address'])) && (!empty($_POST['city'])) && (!empty($_POST['province'])) && (!empty($_POST['postal']))) {
				
				if ($_POST['password'] == $_POST['veripass']) {
					$ExtQuery = $aobs->query("SELECT user_ext FROM ".$aobs->tbl['user']." WHERE user_ext > 5000 ORDER BY user_ext DESC LIMIT 1");
					$Ext = $aobs->fetch_array($ExtQuery);
					if ($aobs->num_rows($ExtQuery) > 0) { $unique_ext = $Ext['user_ext'] + 1; } else { $unique_ext = "5001"; }
					$aobs->query("INSERT INTO ".$aobs->tbl['user']." ".
								"(user_name, user_pass, user_email, user_ext, firstname, lastname, address, city, province, postal) ".
								"VALUES ('".$_POST['username']."', '".$_POST['password']."', '".$_POST['email']."', '".$unique_ext."', ".
								"'".$_POST['firstname']."', '".$_POST['lastname']."', '".$_POST['address']."', '".$_POST['city']."', ".
								"'".$_POST['province']."', '".$_POST['postal']."')");
					$aobs_tag = '<meta http-equiv="refresh" content="5; url=?aobs=auth&amp;tab=" />';
					$MSG_Title = "Successfully registered!";
					$MSG_Content =	$aobs_msg['reg_done'];
					eval ("\$LAYOUT_CONTENT = \"".import_tpl("tpl_message")."\";");
					// Trigger perl script
	
				} else {
					$aobs_tag = '<meta http-equiv="refresh" content="5; url=javascript:history.go(-1)" />';
					$MSG_Title = "Password not matched!";
					$MSG_Content =	$aobs_msg['reg_password'];
					eval ("\$LAYOUT_CONTENT = \"".import_tpl("tpl_message")."\";");
				}
			} else {
				$aobs_tag = '<meta http-equiv="refresh" content="5; url=javascript:history.go(-1)" />';
				$MSG_Title = "All fields must be filled!";
				$MSG_Content =	$aobs_msg['reg_fields'];
				eval ("\$LAYOUT_CONTENT = \"".import_tpl("tpl_message")."\";");
			}
		} else {
			$aobs_tag = '<meta http-equiv="refresh" content="5; url=javascript:history.go(-1)" />';
			$MSG_Title = "You must agree to our terms!";
			$MSG_Content =	$aobs_msg['reg_terms'];
			eval ("\$LAYOUT_CONTENT = \"".import_tpl("tpl_message")."\";");
		}
	}	
} else { 
	eval ("\$LAYOUT_CONTENT = \"".import_tpl("tpl_register")."\";");
}
?>