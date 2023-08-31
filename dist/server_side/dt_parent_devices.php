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
	$aColumns = array('md_id',
					  'md_device_name',
					  'md_drawing_number',
					  'md_cavity_qty',
					  'md_maintenance_cycle',
					  'md_created_by',
					  'md_last_update_by',
					  'md_logdel',
					  'md_adate');
	
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = "md_id";
	
	/* DB table to use */
	$sTable = "

t_mother_devices

	



	";


/*
	// select
	// 	md_id,
	// 	md_device_name,
	// 	md_drawing_number,
	// 	md_cavity_qty,
	// 	md_maintenance_cycle,
	// 	md_created_by,
	// 	md_last_update_by,
	// 	md_logdel,
	// 	md_adate,
	// 	1 as is_parent
	// from t_mother_devices
	// union
	select * from
	(
		select 
			`t_dieset_cycle`.`dc_idd` AS `md_id`,
			`t_device`.`d_name` AS `md_device_name`,
			`t_device`.`d_drawing` AS `md_drawing_number`,
			`t_device`.`d_cavty` AS `md_cavity_qty`,
			`t_dieset_cycle`.`dc_maintenance_cycle` AS `md_maintenance_cycle`,
			'' AS md_created_by,
			'' AS md_last_update_by,
			0 AS md_logdel,
			`t_shots`.`s_adate` AS `md_adate`,
			0 as is_parent

		from `t_dieset_cycle`
		join `db_pps`.`t_device`
		on `t_dieset_cycle`.`dc_d_id` = `t_device`.`d_id` and 
			(`t_device`.`d_id` not in 
				(
					'44','119','119','122','125','126','127','164','172','173','175','259','260','305','306','316','319','349','365','368','376','378','382','390','391','463','488','489','493'
				)
			)
		left join `t_shots`
		on `t_shots`.`s_d_id` = `t_dieset_cycle`.`dc_d_id`
		left join 
			(
				select `t_shots`.`s_d_id`, sum(`t_shots`.`s_count`) AS `ongoing_accumulated_shots`
				from `t_shots` 
				where `t_shots`.`s_status` = 'ongoing'
				group by `t_shots`.`s_d_id`
			) t_shots_sum
		on t_shots_sum.`s_d_id` = `t_dieset_cycle`.`dc_d_id`
		where `t_dieset_cycle`.`dc_status` = 'ongoing' and `t_dieset_cycle`.`logdel` = 0
		group by `t_dieset_cycle`.`dc_d_id`
	) v_dmcms_main_page

*/

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
		$sWhere .= " WHERE md_logdel !=1";
	}else{
		$sWhere .= " AND md_logdel !=1";
	}


	// if ( isset( $_GET['d_id'] ) )
	// {
	// 	$sWhere .= " AND d_id = '".mysql_real_escape_string( $_GET['d_id'] )."' ";
	// }
	// else{
	// 	$sWhere .= " AND d_id = 'search this to return nothing' ";
	// }

	
	// $sOrder = "ORDER BY fk_batch_number ASC, if(record_number1 = '' or record_number1 is null,1,0), record_number1 ASC, record_number ASC";
	$sOrder = "";//both should DESC


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

	require_once('../handler/handler.php');
	while ($aRow=mysql_fetch_array($rResult)){
		$row = array();
		unset($row);
		$row[] = '<input type="hidden" value="'.$aRow['md_id'].'">'.$aRow['md_device_name'];
		$row[] = $aRow['md_drawing_number'];
		$row[] = $aRow['md_cavity_qty'];
		$row[] = number_format($aRow['md_maintenance_cycle']);

		$total_accum_shots_arr = select_children_total_data($aRow['md_id']);
		$total_accum_shots = $total_accum_shots_arr['total_accum_shots'];
		$total_remaining_shots = number_format($aRow['md_maintenance_cycle']-$total_accum_shots);
		//-----
		$accum_percent = ( $total_accum_shots/$aRow['md_maintenance_cycle'] )*100;
		$total_accum_shots = number_format($total_accum_shots);
		if ($accum_percent>=100){
			$total_accum_shots = '	<span><i class="text-danger float-left fa fa-exclamation-circle"></i> '.$total_accum_shots.'</span>';
			$total_remaining_shots = '<span class="small text-center 100_percent">Maintenance Cycle Reached</div>';
		} 
		else if ($accum_percent>=90){
			$total_accum_shots = '	<span><i class="text-warning float-left fa fa-exclamation-circle"></i> '.$total_accum_shots.'</span>';
			$total_remaining_shots = '<span class="small 90_percent">'.$total_remaining_shots.'</div>';
		}


		//-----
		$row[] = $total_accum_shots;
		$row[] = $total_remaining_shots;
		array_push($output['aaData'],$row);
	}
	echo json_encode($output);
?>