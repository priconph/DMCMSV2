<?php
	/*
	 * Script:    DataTables server-side script for PHP and MySQL
	 * Copyright: 2010 - Allan Jardine
	 * License:   GPL v2 or BSD (3-point)
	 */
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

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
			'`v_dmcms_main_page`.`d_id`',
		 	'`v_dmcms_main_page`.`d_name`',
			'`v_dmcms_main_page`.`dc_maintenance_cycle`',
			'`v_dmcms_main_page`.`dc_mstatus`',
			'`v_dmcms_main_page`.`d_cavty`',
			'`v_dmcms_main_page`.`d_drawing`',
			'`v_dmcms_main_page`.`d_cycle_time`',
			'`v_dmcms_main_page`.`dc_aupdate`',
			'`v_dmcms_main_page`.`dc_idd`',
			'`v_dmcms_main_page`.`ongoing_accumulated_shots`',
			'`v_dmcms_main_page`.`last_actual_cavity`',
			'`v_dmcms_main_page`.`accum_percent`'
		);
	
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = "d_id";
	
	/* DB table to use */
	$sTable = "
		(
			select 
				`t_dieset_cycle`.`dc_maintenance_cycle` AS `dc_maintenance_cycle`,
				`t_dieset_cycle`.`dc_mstatus` AS `dc_mstatus`,
				`t_dieset_cycle`.`dc_idd` AS `dc_idd`,
				`t_dieset_cycle`.`dc_status` AS `dc_status`,
				`t_device`.`d_id` AS `d_id`,
				`t_device`.`d_name` AS `d_name`,
				`t_device`.`d_cavty` AS `d_cavty`,
				`t_device`.`d_drawing` AS `d_drawing`,
				`t_device`.`d_cycle_time` AS `d_cycle_time`,

				`t_shots`.`s_adate` AS `dc_aupdate`,

				t_shots_sum.`ongoing_accumulated_shots`,

				max(`t_shots`.`s_actual_cavty`) AS `last_actual_cavity`,

				(( t_shots_sum.`ongoing_accumulated_shots` / `t_dieset_cycle`.`dc_maintenance_cycle`)*100) AS accum_percent

			from `t_dieset_cycle`
			join `db_pps`.`t_device`
			on `t_dieset_cycle`.`dc_d_id` = `t_device`.`d_id` and 
				(`t_device`.`d_id` not in 
					(
						'44','119','119','122','125','126','127','164','172','173','175','259','260','305','306','316','319','349','365','368','376','378','382','390','391','463','488','489'
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
		$sWhere .= " WHERE d_id != -1";
	}else{
		$sWhere .= " AND  d_id != -1";
	}


	if ( isset( $_GET['po_number_pkid'] ) ){
		// $sWhere .= " AND fk_po_number = '".mysql_real_escape_string( $_GET['po_number_pkid'] )."' ";
	}
	else{
		// $sWhere .= " AND fk_po_number = 'search this to return nothing' ";
	}

	if ( isset( $_GET['reached_percent'] ) ){
		if(mysql_real_escape_string( $_GET['reached_percent'] ) == '90'){
			$sWhere .= " AND accum_percent >= 90 AND accum_percent < 100 ";
		}
		else if(mysql_real_escape_string( $_GET['reached_percent'] ) == '100'){
			$sWhere .= " AND accum_percent >= 100 ";
		}
		else if(mysql_real_escape_string( $_GET['reached_percent'] ) == 'both'){
			$sWhere .= " AND accum_percent >= 90 ";
		}
	}

	
	// $sOrder = "ORDER BY fk_batch_number ASC, if(record_number1 = '' or record_number1 is null,1,0), record_number1 ASC, record_number ASC";
	$sOrder = "ORDER BY accum_percent DESC";


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
	

	while ( $aRow = mysql_fetch_array( $rResult ) )
	{
		$row = array();
		unset($row);

		$maintenance_cycle = (int)$aRow['dc_maintenance_cycle']; 
		$zero=0;
		if($maintenance_cycle>=$aRow['ongoing_accumulated_shots']){
			$balance = $maintenance_cycle-$aRow['ongoing_accumulated_shots'];
			if($balance==0){
				$remainng_prodn_time = '<span class="small">N/A</span>';
				$balance = '<div class="small text-center">Maintenance Cycle Reached</div>';
				if($aRow['dc_maintenance_cycle']==''||$aRow['dc_maintenance_cycle']==0){
					$balance = '<div class="small text-center">Maintenance Cycle Not Set</div>';
				}

			}
			else{
				if($aRow['d_cycle_time']==''||$aRow['d_cycle_time']==0){
					$remainng_prodn_time = '<span class="small">Cycle Time Not Set</span>';
				}				
				else{
					if($aRow['ongoing_accumulated_shots']==0){
						$remainng_prodn_time = '<span class="small">Not yet started</span>';
					}
					else{
						$bal_divide_actual_cavity = $balance/(int)$aRow['last_actual_cavity'];
						$bal_divide_actual_cavity_times_cycle_time = $bal_divide_actual_cavity*floatval($aRow['d_cycle_time']);
						$remainng_prodn_time = $bal_divide_actual_cavity_times_cycle_time/3600;
						$remainng_prodn_time = convertTime($remainng_prodn_time);
					}
				}				
				$balance = number_format($balance);
			}
		}
		else{
			$balance = '<div class="small text-center">Over Maintenance Cycle</div>';
			$remainng_prodn_time = '<div class="small text-center">N/A</div>';
		}	

		// if ($aRow['ongoing_accumulated_shots'] >= ($aRow['dc_maintenance_cycle'] * 0.80) && ($aRow['ongoing_accumulated_shots'] < ($aRow['dc_maintenance_cycle'] * 0.9)) && ($aRow['dc_maintenance_cycle'] > $zero)){
		// 	$row_accumulated_shots = '	<span style="height:100%;width:100%;">'.number_format($aRow['ongoing_accumulated_shots']).' <span style="color:#f7c07e;" class="pull-right fa fa-exclamation-triangle"></span></span>';
		// 	$row[] = '<span style="color:#f7c07e;background-color:#f7c07e;">2 &nbsp</span>';
		// } 
		// else 

		$disabled = '';//for add shots button when 100%

		
		if ($aRow['ongoing_accumulated_shots'] >= ($aRow['dc_maintenance_cycle'] * 0.9) && ($aRow['ongoing_accumulated_shots'] < ($aRow['dc_maintenance_cycle'])) && ($aRow['dc_maintenance_cycle'] > $zero)){						
			$row_accumulated_shots = '	<span>'.number_format($aRow['ongoing_accumulated_shots']).' <i class="text-danger float-left fa fa-exclamation-circle"></i></span>';
		} 
		else if ($aRow['ongoing_accumulated_shots'] >= ($aRow['dc_maintenance_cycle']) && $aRow['dc_maintenance_cycle']!='0' && ($aRow['dc_maintenance_cycle'] > $zero)){
			$row_accumulated_shots = '	<span>'.number_format($aRow['ongoing_accumulated_shots']).' <i class="text-danger float-left fa fa-exclamation-circle"></i></span>';
			$disabled = 'disabled';
		}
		else if($aRow['dc_maintenance_cycle'] <= $zero){
			$row_accumulated_shots = '	<span class="small">Maintenance Cycle Not Set</span>';
		} 
		else{
			$row_accumulated_shots = '<span>'.number_format($aRow['ongoing_accumulated_shots']).'</span>';
		}

		$button = '<div class="dropdown float-left mr-2">
						<button class="btn btn-info btn-sm dropdown-toggle py-0" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i class="fa fa-caret-down"></i>
						</button>
						<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
							<button class="dropdown-item btn_dropdown_add_shot" value="'.$aRow['d_id'].'" '.$disabled.'><i class="fa fa-plus"></i> ADD SHOT</button>
							<button class="dropdown-item btn_dropdown_view_records" value="'.$aRow['d_id'].'"><i class="fa fa-file-alt"></i> VIEW RECORDS</button>
							<button class="dropdown-item btn_dropdown_view_child" '.$btn_children_disable.'><i class="fa fa-table"></i> VIEW CHILDREN</button>
						</div>
					</div>';






		$row[] = $button.'<span class="txt_device_name">'.$aRow['d_name'].'</span>'.'<span class="txt_dc_id" hidden>'.$aRow['dc_idd'].'</span>';
		$row[] = $aRow['d_drawing'];
		$row[] = $aRow['d_cavty'];
		
		if($maintenance_cycle <= $zero){
			$row[] = ' <span class="small">Maintenance Cycle Not Set</span>';
		}
		else{
			$row[] = number_format($aRow['dc_maintenance_cycle']);
		}

		$row[] = $row_accumulated_shots;
		$row[] = $balance;
		$row[] = $aRow['last_actual_cavity'];
		$row[] = $remainng_prodn_time;
		array_push($output['aaData'],$row);
	}
	echo json_encode( $output );
?>