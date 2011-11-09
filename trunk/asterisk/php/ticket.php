<?
if (!eregi("index.php", $_SERVER['PHP_SELF'])) {
	die();
}
/*If cooke is currently set*/
if(isset($_COOKIE['User_Name']) && isset($_COOKIE['User_Pass']) && isset($_COOKIE['User_Exist'])) {
	if ($_REQUEST['tab'] == "support") {
		$User_Tickets = $aobs->query("SELECT * FROM ".$aobs->tbl['tickets']." WHERE user_poster='".$user."' ORDER BY ticket_id ASC");
		if ($aobs->num_rows($User_Tickets) > 0) {
			$Display_Tickets = '<table class="Tickets" cellspacing="0">';
			$Display_Tickets .= '<tr class="TopR">';
			$Display_Tickets .= '<td class="TableH">Ticket#</td>';
			$Display_Tickets .= '<td class="TableH">Subject</td>';
			$Display_Tickets .= '<td class="TableH">Category</td>';
			$Display_Tickets .= '<td class="TableH">Date Added</td>';
			$Display_Tickets .= '</tr>';
			$Ticket_Count = 0;
			while ($This_Ticket = $aobs->fetch_array($User_Tickets)) {
				$Ticket_Count++;
				$Ticket_ID = $This_Ticket['ticket_id'];
				$Ticket_Subject = $This_Ticket['ticket_subject'];
				if($This_Ticket['ticket_category'] == "Tech_Issues"){$Ticket_Category = "Technical Issues";}
				else if($This_Ticket['ticket_category'] == "Bill_Issues"){$Ticket_Category = "Bill Dispute";}
				else if($This_Ticket['ticket_category'] == "General_Question"){$Ticket_Category = "General Question";}
				else if($This_Ticket['ticket_category'] == "Other"){$Ticket_Category = "Other";}
				$Date_Added = $This_Ticket['ticket_time'];
				if ($Ticket_Count % 2 != 0) {
					$Tick_Class = "TableA";
				} else {
					$Tick_Class = "TableB";
				}
				$Display_Tickets .= '<tr align="center">
					<td class="'.$Tick_Class.'"><a href="?aobs=user&amp;tab=view_ticket&amp;ticket_view='.$Ticket_ID.'">'.$Ticket_ID.'</a></td>
					<td class="'.$Tick_Class.'">'.$Ticket_Subject.'</td>
					<td class="'.$Tick_Class.'">'.$Ticket_Category.'</td>
					<td class="'.$Tick_Class.'">'.$Date_Added.'</td>
					</tr>';		
			}
			$Display_Tickets .='</table>';
		} else {
			$Display_Tickets = "You currently have no open tickets.";	
		}
		$support = "class='active'";
		eval ("\$USER_Info = \"".import_tpl("support")."\";");
	} else if ($_REQUEST['tab'] == "view_ticket" && $_REQUEST['ticket_view'] != NULL) {
		$This_Ticket_view = $aobs->query("SELECT * FROM ".$aobs->tbl['tickets']." WHERE ticket_id = '".$_REQUEST['ticket_view']."' AND user_poster = '".$user."'");
		if ($aobs->num_rows($This_Ticket_view) > 0) {
			$This_Ticket_view = $aobs->query("SELECT * FROM ".$aobs->tbl['ticket_msgs']." WHERE ticket_id = '".$_REQUEST['ticket_view']."' ORDER BY convo_id ASC");
			$Tick_ID = $_REQUEST['ticket_view'];
			if ($aobs->num_rows($This_Ticket_view) > 0) {
				$message_num = 0;
				$Display_Ticket_Info = '<table class="Tickets" cellspacing="0">';
				while ($This_Ticket = $aobs->fetch_array($This_Ticket_view)){
					$message_num++;
					$last_poster = $This_Ticket['user_poster'];
					$message_time = $This_Ticket['convo_time'];
					$ticket_message = $This_Ticket['ticket_message'];
					$Display_Ticket_Info .= '<tr>
						<td class="'.$Tick_Class.'">  
						<tr>
						<td class="TableH">#'.$message_num.'</td>
						<td class="TableH">Poster: '.$last_poster.'</td>
						<td class="TableH">Date Updated: '.$message_time.'</td>
						</tr>
						<tr>
						<td class="TableA" colspan="3">'.$ticket_message.'</td>
						</tr>
						</td>
						</tr>';
				}				
			} else {
				$aobs_tag = '<meta http-equiv="refresh" content="5; url=javascript:history.go(-1)" />';
				$MSG_Title = "Invalid URL!";
				$MSG_Content =	$aobs_msg['invalid_url_ticket'];
				eval ("\$USER_Info = \"".import_tpl("tpl_message")."\";");
			}
			$Display_Ticket_Info .= '</table>';
			$support = "class='active'";
			eval ("\$USER_Info = \"".import_tpl("ticket_info")."\";");
		} else {
			$aobs_tag = '<meta http-equiv="refresh" content="5; url=javascript:history.go(-1)" />';
			$MSG_Title = "Invalid URL!";
			$MSG_Content =	$aobs_msg['invalid_url_ticket'];
			eval ("\$USER_Info = \"".import_tpl("tpl_message")."\";");
		}
	} else if ($_REQUEST['tab'] == "update_ticket" && $_REQUEST['ticket'] != NULL) {
		if ((!empty($_POST['Tickmsg']))) {
			$TicketUpdateQuery = $aobs->query("SELECT * FROM ".$aobs->tbl['tickets']." WHERE ticket_id = '".$_REQUEST['ticket']."' AND user_poster = '".$user."'");
			$Tick_ID = $_REQUEST['ticket'];
			if ($aobs->num_rows($TicketUpdateQuery) > 0) {
				$Current_TimeStamp = date('Y-m-d H:i:s');
				$aobs->query("INSERT INTO ".$aobs->tbl['ticket_msgs']." "."(user_poster, ticket_id, ticket_message, convo_time) ".
				"VALUES ('".$user."', '".$_REQUEST['ticket']."', '".$_POST['Tickmsg']."', '".$Current_TimeStamp."')");
				$aobs_tag = '<meta http-equiv="refresh" content="0; url=?aobs=user&amp;tab=view_ticket&amp;ticket_view='.$Tick_ID.'" />';
			} else {
				$aobs_tag = '<meta http-equiv="refresh" content="5; url=javascript:history.go(-1)" />';
				$MSG_Title = "Invalid URL!";
				$MSG_Content =	$aobs_msg['invalid_url_ticket'];
				eval ("\$USER_Info = \"".import_tpl("tpl_message")."\";");
			}		
		} else {
			$aobs_tag = '<meta http-equiv="refresh" content="5; url=javascript:history.go(-1)" />';
			$MSG_Title = "Invalid URL!";
			$MSG_Content =	$aobs_msg['invalid_url_ticket'];
			eval ("\$USER_Info = \"".import_tpl("tpl_message")."\";");
		}
		$Display_Ticket_Info .= '</table>';
		$support = "class='active'";
		eval ("\$USER_Info = \"".import_tpl("ticket_info")."\";");
	} else if ($_REQUEST['tab'] == "submit_ticket") {
		if ((!empty($_POST['ticket_subject'])) && (!empty($_POST['ticket_category'])) && (!empty($_POST['Init_Tickmsg']))) {
			$TicketQuery = $aobs->query("SELECT ticket_id FROM ".$aobs->tbl['tickets']." WHERE ticket_id > 0 ORDER BY ticket_id DESC");
			$Ticket = $aobs->fetch_array($TicketQuery);
			$Current_TimeStamp = date('Y-m-d H:i:s');
			if($aobs->num_rows($TicketQuery) > 0){
				$uTicket_ID = $Ticket['ticket_id'] + 1;
			} else {
					$uTicket_ID = "1";
			}
			$aobs->query("INSERT INTO ".$aobs->tbl['tickets']." "."(ticket_id, ticket_subject, ticket_category, user_poster, ticket_time) ".
				"VALUES ('".$uTicket_ID."', '".$_POST['ticket_subject']."', '".$_POST['ticket_category']."', '".$user."', '".$Current_TimeStamp."')");
			$aobs->query("INSERT INTO ".$aobs->tbl['ticket_msgs']." "."(user_poster, ticket_id, ticket_message, convo_time) ".
				"VALUES ('".$user."', '".$uTicket_ID."', '".$_POST['Init_Tickmsg']."', '".$Current_TimeStamp."')");
			
			eval ("\$USER_Info = \"".import_tpl("ticket_submitted")."\";");
			/*$aobs_tag = '<meta http-equiv="refresh" content="5; url=?aobs=user&amp;tab=ticket_submitted" />';
			$MSG_Title = "Your support request has been submitted.";
			$MSG_Content =	$aobs_msg['Ticket_Submitted'];
			eval ("\$USER_Info = \"".import_tpl("tpl_message")."\";");*/
		} else {
			$aobs_tag = '<meta http-equiv="refresh" content="5; url=javascript:history.go(-1)" />';
			$MSG_Title = "All fields must be filled!";
			$MSG_Content =	$aobs_msg['reg_fields'];
			eval ("\$USER_Info = \"".import_tpl("tpl_message")."\";");
		}
	} 
}
?>