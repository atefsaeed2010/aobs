<?
if (!eregi("index.php", $_SERVER['PHP_SELF'])) {
	die();
}
/*If cooke is currently set*/
if(isset($_COOKIE['User_Name']) && isset($_COOKIE['User_Pass']) && isset($_COOKIE['User_Exist'])) {
	if ($_REQUEST['tab'] == "bill") {
		// This whole part is to get the total cost from billing page into fron page on the left side
		// It should be handled better way
		$BillQuery = $aobs->query(get_page("SELECT * FROM ".$aobs->tbl['cdr']." WHERE src = '".$_COOKIE['User_Name']."' OR dst = '".$uext."' ORDER BY calldate ASC", $aobs_dtl['display'], $_SERVER['REQUEST_URI']));
		if ($aobs->num_rows($BillQuery) > 0) {
			if(empty($_GET['p'])) $stt = 1; else $stt = 1+(($_GET['p']-1)*$aobs_dtl['display']); $totalcharge = 0;
			$callin = 0; $callout = 0; $calllong = 0; $callcon = 0;
			$timein = 0; $timeout = 0; $timelong = 0; $timecon = 0;
			$costin = 0; $costout = 0; $costlong = 0; $costcon = 0;
			$CALL_LIST = '<table class="Tickets" cellspacing="0">';
			$CALL_LIST .= '<tr class="TopR">';
			$CALL_LIST .= '<td class="TableH">#</td>';
			$CALL_LIST .= '<td class="TableH">Date</td>';
			$CALL_LIST .= '<td class="TableH">Time</td>';
			$CALL_LIST .= '<td class="TableH">Destination</td>';
			$CALL_LIST .= '<td class="TableH">Rate</td>';
			$CALL_LIST .= '<td class="TableH">Duration (sec)</td>';
			$CALL_LIST .= '<td class="TableH">Charge</td>';
			$CALL_LIST .= '</tr>';
			while ($Usage = $aobs->fetch_array($BillQuery)) {
				$count		= $stt++;
				$date		= substr($Usage['calldate'], 0, 10);
				$time		= substr($Usage['calldate'], -8);
				$number		= get_number($Usage['dst']);
				$rate		= get_rate($number, $uext);
				$duration	= $Usage['duration'];
				$billsec 	= $Usage['billsec'];
				$charge		= get_charge($rate, $billsec);
				if ($rate == "ICM") { $callin++; $timein = $timein + $billsec; $costin = $costin + $charge; }
				else if ($rate == "INT") { $callout++; $timeout = $timeout + $billsec; $costout = $costout + $charge; }
				else if ($rate == "CNF") { $callcon++; $timecon = $timecon + $billsec; $costcon = $costcon + $charge; }
				else if ($rate == "LDC") { $calllong++; $timelong = $timelong + $billsec; $costlong = $costlong + $charge; }
				$chargein	= get_format($costin);
				$chargeout	= get_format($costout);
				$chargelong	= get_format($costlong);
				$chargecon	= get_format($costcon);
				$chargettl	= get_format($costin + $costout + $costlong + $costcon);
				$chargetax	= get_format(($costin + $costout + $costlong + $costcon) * 0.13);
				$chargefnal	= get_format(($costin + $costout + $costlong + $costcon) * 1.13);
	
				
				if ($stt % 2) $Table_Class = "TableB"; else $Table_Class = "TableA";
				$CALL_LIST .= '<tr align="center">
								<td class="'.$Table_Class.'">'.$count.'</td>
								<td class="'.$Table_Class.'">'.$date.'</td>
								<td class="'.$Table_Class.'">'.$time.'</td>
								<td class="'.$Table_Class.'">'.$number.'</td>
								<td class="'.$Table_Class.'">'.$rate.'</td>
								<td class="'.$Table_Class.'">'.$billsec.'</td>
								<td class="'.$Table_Class.'">$'.$charge.'</td>
							  </tr>';
			}
			$CALL_LIST .='</table>';
			$billing = "class='active'";
			eval ("\$USER_Info = \"".import_tpl("user_bill")."\";");
		}
	}
}
?>