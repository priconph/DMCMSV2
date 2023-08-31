<?php
	/*
	 * Script:    DataTables server-side script for PHP and MySQL
	 * Copyright: 2010 - Allan Jardine
	 * License:   GPL v2 or BSD (3-point)
	 */


	function convertTime($dec){
		// start by converting to seconds
		$seconds = ($dec * 3600);
		// we're given hours, so let's get those the easy way
		$hours = floor($dec);
		// since we've "calculated" hours, let's remove them from the seconds variable
		$seconds -= $hours * 3600;
		// calculate minutes left
		$minutes = floor($seconds / 60);
		// remove those from seconds as well
		$seconds -= $minutes * 60;

		$milliseconds = (floor($seconds) / 100) * 1000;

		// return the time formatted HH:MM:SS
		// return lz($hours).":".lz($minutes).":".lz((int)$seconds).":".sprintf("%03d",$milliseconds);
		return lz($hours).":".lz($minutes);
	}

	// lz = leading zero
	function lz($num){
		return (strlen($num) < 2) ? "0{$num}" : $num;
	}




	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * Easy set variables
	 */
	
	/* Array of database columns which should be read and sent back to DataTables. Use a space where
	 * you want to insert a non-database field (for example a counter or static image)
	 */
	$aColumns = array(  
			'`t_joined`.`d_id`',
			'`t_joined`.`s_id`',
		 	'`t_joined`.`s_date`',
			'`t_joined`.`s_count`',
			'`t_joined`.`s_actual_cavty`',
			'`t_joined`.`s_machine`',
			'`t_joined`.`s_operator`',
			'`t_joined`.`s_remarks`',
			'`t_joined`.`s_adate`',
			'`t_joined`.`s_status`',
			'`t_joined`.`logdel`'
		);
	
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = "s_id";
	
	/* DB table to use */
	$sTable = "
		(
			select
				d_id,
				s_id,
				s_date,
				s_count,
				s_actual_cavty,
				s_machine,
				s_operator,
				s_remarks,
				s_adate,
				s_status,
				logdel

			from t_shots
			left join db_pps.t_device
			on t_device.d_id = t_shots.s_d_id
		) t_joined
	";


	require_once('../db_config/config.php');
	/* Database connection information */
	$gaSql['user']       = $username;
	$gaSql['password']   = $password;
	$gaSql['db']         = $db_name;
	$gaSql['server']     = $server;
	
	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * If you just want to use the basic configuration for DataTables with PHP server-side, there is
	 * no need to edit below this line
	 */
	
	/* 
	 * MySQL connection
	 */
	$gaSql['link'] =  mysql_pconnect( $gaSql['server'], $gaSql['user'], $gaSql['password']  ) or
		die( 'Could not open connection to server' );
	
	mysql_select_db( $gaSql['db'], $gaSql['link'] ) or 
		die( 'Could not select database '. $gaSql['db'] );
	
	
	/* 
	 * Paging
	 */
	$sLimit = "";
	if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
	{
		$sLimit = "LIMIT ".mysql_real_escape_string( $_GET['iDisplayStart'] ).", ".
			mysql_real_escape_string( $_GET['iDisplayLength'] );
	}
	
	
	/*
	 * Ordering
	 */
	if ( isset( $_GET['iSortCol_0'] ) )
	{
		$sOrder = "ORDER BY  ";
		for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
		{
			if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
			{
				$sOrder .= $aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."
				 	".mysql_real_escape_string( $_GET['sSortDir_'.$i] ) .", ";
			}
		}
		
		$sOrder = substr_replace( $sOrder, "", -2 );
		if ( $sOrder == "ORDER BY" )
		{
			$sOrder = "";
		}
	}
	
	
	/* 
	 * Filtering
	 * NOTE this does not match the built-in DataTables filtering which does it
	 * word by word on any field. It's possible to do here, but concerned about efficiency
	 * on very large tables, and MySQL's regex functionality is very limited
	 */
	$sWhere = "";


	// $device_name = $_GET['sr_name'];
	// $m_cycle 	 = $_GET['sr_m'];
	// $cavty 	 	 = $_GET['sr_c'];
	// $drawing 	 = $_GET['sr_d'];
	// $l_update 	 = $_GET['sr_l'];	

	// if($device_name != ""){
	// 	$device_name_query = ' `v_dmcms_main_page`.`d_name` LIKE "%'.$device_name.'%"';
	// 	$sWhere = $device_name_query;
	// }

	// if($m_cycle != ""){
	// 	$m_cycle_query = ' `v_dmcms_main_page`.`dc_maintenance_cycle` = "'.$m_cycle.'"';
	// 	$sWhere = $m_cycle_query;
	// }

	// if($cavty != ""){
	// 	$cavty_query = ' `v_dmcms_main_page`.`d_cavty` = "'.$cavty.'"';
	// 	$sWhere = $cavty_query;
	// }

	// if($drawing != ""){
	// 	$drawing_query = ' `v_dmcms_main_page`.`d_drawing` = "'.$drawing.'"';
	// 	$sWhere = $drawing_query;
	// }

	// if($l_update != ""){
	// 	$l_update_query = ' `v_dmcms_main_page`.`dc_aupdate` = "'.$l_update.'"';
	// 	$sWhere = $l_update_query;
	// }

	if ( $_GET['sSearch'] != "" )
	{
		$sWhere = "WHERE (";
		for ( $i=0 ; $i<count($aColumns) ; $i++ )
		{
			$sWhere .= $aColumns[$i]." LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR ";
		}
		$sWhere = substr_replace( $sWhere, "", -3 );
		$sWhere .= ')';
	}
	
	/* Individual column filtering */
	for ( $i=0 ; $i<count($aColumns) ; $i++ )
	{
		if ( $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
		{
			if ( $sWhere == "" )
			{
				$sWhere = "WHERE ";
			}
			else
			{
				$sWhere .= " AND ";
			}
			$sWhere .= $aColumns[$i]." LIKE '%".mysql_real_escape_string($_GET['sSearch_'.$i])."%' ";
		}
	}
	


	//====== CUSTOM

	if($sWhere == ""){
		$sWhere .= " WHERE logdel !=1";
	}else{
		$sWhere .= " AND logdel !=1";
	}


	if ( isset( $_GET['d_id'] ) )
	{
		$sWhere .= " AND d_id = '".mysql_real_escape_string( $_GET['d_id'] )."' ";
	}
	else{
		$sWhere .= " AND d_id = 'search this to return nothing' ";
	}

	
	// $sOrder = "ORDER BY fk_batch_number ASC, if(record_number1 = '' or record_number1 is null,1,0), record_number1 ASC, record_number ASC";
	$sOrder = "ORDER BY s_date DESC, s_adate DESC";//both should DESC


	//======/CUSTOM


	
	/*
	 * SQL queries
	 * Get data to display
	 */
	$sQuery = "
		SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))."
		FROM   $sTable
		$sWhere
		$sOrder
		$sLimit
	";
	$rResult = mysql_query( $sQuery, $gaSql['link'] ) or die(mysql_error());
	
	/* Data set length after filtering */
	$sQuery = "
		SELECT FOUND_ROWS()
	";
	$rResultFilterTotal = mysql_query( $sQuery, $gaSql['link'] ) or die(mysql_error());
	$aResultFilterTotal = mysql_fetch_array($rResultFilterTotal);
	$iFilteredTotal = $aResultFilterTotal[0];
	
	/* Total data set length */
	$sQuery = "
		SELECT COUNT(".$sIndexColumn.")
		FROM   $sTable
	";
	$rResultTotal = mysql_query( $sQuery, $gaSql['link'] ) or die(mysql_error());
	$aResultTotal = mysql_fetch_array($rResultTotal);
	$iTotal = $aResultTotal[0];
	
	
	/*
	 * Output
	 */
	$output = array(
		"sEcho" => intval($_GET['sEcho']),
		"iTotalRecords" => $iTotal,
		"iTotalDisplayRecords" => $iFilteredTotal,
		"aaData" => array()
	);

	include_once('../handler/handler.php');	

	$shot_accum = 0;
	$num = 0;

	$items = array();
	while ( $aRow = mysql_fetch_array( $rResult ) ){
		$items[] = $aRow;
	}

	$items = array_reverse($items ,true);

	$aRow = array();
	foreach($items as $aRow)
	{
		$row = array();
		unset($row);

		$num ++;
		if($num == 1){
			$shot_accum = select_prev_accum_shots($aRow['d_id'],$aRow['s_id']);
		}
		$row[] = date("M d, Y", strtotime($aRow['s_date'])).'<span class="span_status" hidden>'.$aRow['s_status'].'</span>';
		$pas_badge = '';
		if( $aRow['s_status'] == 'past' ){
			$pas_badge = '<span class="badge badge-info float-left" title="Previous Accumulated Shots">PAS</span>';
		}

		$row[] = $pas_badge.number_format($aRow['s_count']);
		if( $aRow['s_status'] != 'past' ){
			if( $aRow['s_status'] != 'reset' ){
				$shot_accum += $aRow['s_count'];
				$row[] = number_format($shot_accum);//accum
			}
			else{
				$shot_accum = 0;
				$row[] = 0;//accum
			}

		}
		else{
			$row[] = '';//accum
		}

		$row[] = $aRow['s_actual_cavty'];
		$row[] = $aRow['s_machine'];
		$row[] = $aRow['s_operator'];
		$row[] = $aRow['s_remarks'];


		if($aRow['s_adate']=='0000-00-00'||$aRow['s_adate']==''){
			$row[] = '';
		}
		else{
			$row[] = date("M d, Y ", strtotime($aRow['s_adate'])).'<span class="text-secondary small"> &nbsp'.date("h:i A", strtotime($aRow['s_adate'])).'</span>';
		}

		array_push($output['aaData'],$row);
	}
	$output['aaData'] = array_reverse( $output['aaData'] );
	echo json_encode( $output );
?>