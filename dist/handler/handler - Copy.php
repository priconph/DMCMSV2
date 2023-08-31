<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	session_start();
	$session_username = isset($_SESSION['username'])?$_SESSION['username']:'';

	function check_session(){
		$session_username = isset($_SESSION['username'])?$_SESSION['username']:'';
		if(!$session_username){
			return json_encode("Session expired. Transaction not saved. Please re-login.");
		}
		else{
			return '';
		}
	}



	if( !function_exists('is_ajax') ){
		function is_ajax(){
			return isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest';
		}
	}

	if(is_ajax()){
		if(isset($_POST['action'])&&!empty($_POST['action'])){
			switch ($_POST['action']){
				case 'select_device_records'			:		select_device_records(); 			break;
				case 'select_notification'				:		select_notification(); 				break;
				case 'insert_shot'						:		insert_shot(); 						break;
				case 'insert_past_shot'					:		insert_past_shot(); 				break;
				case 'select_range_of_data'				:		select_range_of_data(); 			break;
				case 'select_device_shot_records'		:		select_device_shot_records(); 		break;
				case 'select_parent_device_list'		:		select_parent_device_list(); 		break;
				case 'insert_parent_device'				:		insert_parent_device(); 			break;
				case 'select_userlist_table'			: 		select_userlist_table();			break;
				case 'select_list_of_users'				: 		select_list_of_users();				break;
				case 'select_user_access'				: 		select_user_access();				break;
				case 'select_user_access_arr'			: 		select_user_access_arr();			break;
				case 'update_approve_shots'				: 		update_approve_shots();				break;
				case 'reset_shots'						: 		reset_shots();						break;
				case 'select_for_approvals' 			: 		select_for_approvals(); 			break;
				case 'update_disapprove_shots'			: 		update_disapprove_shots(); 			break;
				case 'select_shot_record_update'		: 		select_shot_record_update(); 		break;
				case 'update_shot_record'				: 		update_shot_record(); 				break;
				case 'insert_assign_user'				: 		insert_assign_user();				break;
				case 'select_list_of_users_in_charge'	: 		select_list_of_users_in_charge();	break;
				case 'select_parts_code_list'			: 		select_parts_code_list();			break;
				case 'insert_new_maint_cycle'			: 		insert_new_maint_cycle();			break;

			}
		}
	}

	function select_parts_code_list(){
		require_once('../class/oop.php'); //OOP Path
		$return 		= $_POST;
		$result 		= "";
		$array_fields 	= array('*');
		$table 	   		= 'db_pps.t_device';
		// $table 	   		= '
		// 	(
		// 		SELECT
		// 			t1.id,
		// 			t1.R3Code,
		// 			t1.DeviceName,
		// 			t2.pkid,
		// 			t2.logdel
		// 		FROM '.$db_name.'.tbl_dieset t1
		// 		LEFT JOIN tbl_production_wip t2
		// 		ON t1.id = t2.fkid
		// 		WHERE pkid IN 
		// 			(
		// 				SELECT MAX(pkid) FROM tbl_production_wip GROUP BY fkid
		// 			) OR pkid IS NULL
		// 		AND (R3Code LIKE "%'.$return['keyword'].'%" OR DeviceName LIKE "%'.$return['keyword'].'%")
		// 		ORDER BY t1.id ASC
		// 	) t_all
		// ';

		$joins 	   		= '';
		$sql_where 		= ' WHERE d_name LIKE "%'.$return['keyword'].'%" ';
		$sql_order 		= '';
		$sql_limit 		= 'LIMIT 0,10';
		$result 		= DB_DMCMS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		// echo DB_PPS_PI::getInstance()->select_query_script($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$ctr 			= 0;
		while($row = mysqli_fetch_array($result)){
			$return['d_id'][$ctr] 					= $row['d_id'];
			$return['d_name'][$ctr] 				= $row['d_name'];
			$return['d_maintenance'][$ctr] 			= $row['d_maintenance'];
			$ctr ++;
		}

		$return['ctr'] 		= $ctr;
		$return["result"] 	= json_encode($result); //return the serialize values
		echo json_encode($return);
		exit();
	}

	function insert_new_maint_cycle(){
		require_once('../class/oop.php');
		$return 			= $_POST;
		$result 			= "";
		$table 				= 't_dieset_cycle';
		$array_fields 		= array(
			'dc_d_id',
			'dc_start_date',
			'dc_maintenance_cycle',
			'dc_status',
			'username'
		);
		$array_values 		= array(
			$return['fkid'],
			$return['txt_add_product_start_date'],
			$return['txt_add_product_maint_cycle'],
			'ongoing',
			$GLOBALS['session_username']
		);
		if( select_device_exist($return['fkid']) == 1 ){
			$return['result'] 	=  json_encode('Already exist.');
			echo json_encode($return);
			exit();
		}
		$result 			= DB_DMCMS::getInstance()->insert_query($table,$array_fields,$array_values);
		$return['result'] 	= json_encode($result);

		echo json_encode($return);
		exit();
	}


	function select_device_exist($d_id){
		require_once('../class/oop.php');
		$result 		= "";
		$array_fields 	= array('dc_d_id');
		$table 	   		= 't_dieset_cycle';
		$joins 	   		= '';
		$sql_where 		= 'WHERE dc_d_id = "'.$d_id.'" AND logdel !=1';
		$sql_order 		= 'LIMIT 0,1';
		$sql_limit 		= '';
		$result 		= DB_DMCMS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$exist = 0;
		while($row = mysqli_fetch_array($result)){
			$exist = 1;
		}
		return $exist;
		exit();
	}


	function select_prev_accum_shots($d_id){
		require_once('../class/oop.php');
		$result 		= "";
		$array_fields 	= array('s_id, s_count, s_status');
		$table 	   		= 't_shots';
		$joins 	   		= '';
		$sql_where 		= 'WHERE s_d_id = "'.$d_id.'" AND logdel !=1';
		$sql_order 		= 'ORDER BY s_date DESC, s_adate DESC';
		$sql_limit 		= '';
		$result 		= DB_DMCMS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$prev_s_accum_shots_2 = 0;
		$start_count = false;

		$items = array();
		while($row = mysqli_fetch_array($result)){
			if( $start_count == true ){$items[] = $row;}
			if( $row['s_id'] == $s_id ){$start_count = true;}
		}
		$items = array_reverse($items);
		$row = array();
		foreach ($items as $row) {
			$prev_s_accum_shots_2 += $row['s_count'];
			if( $row['s_status'] == 'reset' || $row['s_status'] == 'past' ){
				$prev_s_accum_shots_2 = 0;
			}
		}
		return $prev_s_accum_shots_2;
		exit();
	}

	function convertTime_handler($dec){
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
		return lz_handler($hours).":".lz_handler($minutes);
	}

	function lz_handler($num){
		return (strlen($num) < 2) ? "0{$num}" : $num;
	}

	function select_device_records(){
		require_once('../class/oop.php');
		$return 		= $_POST; 
		$result 		= "";
		$array_fields 	= array('v_dmcms_main_page.*','t_mother_devices.md_device_name AS parent_name');
		$table 	   		= '
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

					(( t_shots_sum.`ongoing_accumulated_shots` / `t_dieset_cycle`.`dc_maintenance_cycle`)*100) AS accum_percent,

					t_grand_total.`grand_total`
					

				from `t_dieset_cycle`
				join `db_pps`.`t_device`
				on `t_dieset_cycle`.`dc_d_id` = `t_device`.`d_id` and 
					(`t_device`.`d_id` not in 
						(
							"44","119","119","122","125","126","127","164","172","173","175","259","260","305","306","316","319","349","365","368","376","378","382","390","391","463","488","489"
						)
					)
				left join `t_shots`
				on `t_shots`.`s_d_id` = `t_dieset_cycle`.`dc_d_id`
				left join 
					(
						select `t_shots`.`s_d_id`, sum(`t_shots`.`s_count`) AS `ongoing_accumulated_shots`
						from `t_shots` 
						where `t_shots`.`s_status` = "ongoing" AND `t_shots`.`s_approval_status` = "1"
						group by `t_shots`.`s_d_id`
					) t_shots_sum
				on t_shots_sum.`s_d_id` = `t_dieset_cycle`.`dc_d_id`
				left join 
					(
						select `t_shots`.`s_d_id`, sum(`t_shots`.`s_count`) AS `grand_total`
						from `t_shots` 
						group by `t_shots`.`s_d_id`
					) t_grand_total
				on t_grand_total.`s_d_id` = `t_dieset_cycle`.`dc_d_id`
				where `t_dieset_cycle`.`dc_status` = "ongoing" and `t_dieset_cycle`.`logdel` = 0
				group by `t_dieset_cycle`.`dc_d_id`
			) v_dmcms_main_page
		';
		$joins 	   		= 'LEFT JOIN t_children_allocation ON v_dmcms_main_page.d_id=t_children_allocation.ca_child_id LEFT JOIN t_mother_devices ON t_children_allocation.ca_parent_id=t_mother_devices.md_id';
		$sql_where 		= 'WHERE d_id = "'.$return['pkid_val'].'"';
		$sql_order 		= '';
		$sql_limit 		= 'LIMIT 0,1';
		$result 		= DB_DMCMS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$ctr 			= 0;
		while($row = mysqli_fetch_array($result)){
			$remainng_prodn_time = 'X';
			$maintenance_cycle = (int)$row['dc_maintenance_cycle']; 
			if($maintenance_cycle>=$row['ongoing_accumulated_shots']){
				$balance = $maintenance_cycle-$row['ongoing_accumulated_shots'];
				if($balance==0){
					$remainng_prodn_time = 'N/A';
					$balance = 'Maintenance Cycle Reached';
					if($row['dc_maintenance_cycle']==''||$row['dc_maintenance_cycle']==0){
						$balance = 'Maintenance Cycle Not Set';
					}	
				}
				else{
					if($row['d_cycle_time']==''||$row['d_cycle_time']==0){
						$remainng_prodn_time = 'Cycle Time Not Set';
					}				
					else{
						if($row['ongoing_accumulated_shots']==0){
							$remainng_prodn_time = 'Not yet started';
						}
						else{
							$bal_divide_actual_cavity = $balance/(int)$row['last_actual_cavity'];
							$bal_divide_actual_cavity_times_cycle_time = $bal_divide_actual_cavity*floatval($row['d_cycle_time']);
							$remainng_prodn_time = $bal_divide_actual_cavity_times_cycle_time/3600;
							$remainng_prodn_time = convertTime_handler($remainng_prodn_time);
						}
					}				
				}
			}
			else{
				$remainng_prodn_time = 'N/A';
			}	
			$return['parent_name'][$ctr] 						= $row['parent_name'];
			$return['d_name_2'][$ctr] 							= $row['d_name'];
			$return['d_drawing_2'][$ctr] 						= $row['d_drawing'];
			$return['d_cavty_2'][$ctr] 							= $row['d_cavty'];
			$return['dc_maintenance_cycle_2'][$ctr] 			= $row['dc_maintenance_cycle'];
			$return['ongoing_accumulated_shots_2'][$ctr] 		= $row['ongoing_accumulated_shots'];
			$return['dc_mstatus_2'][$ctr] 						= $row['dc_mstatus'];
			$return['dc_idd_2'][$ctr] 							= $row['dc_idd'];
			$return['dc_status_2'][$ctr] 						= $row['dc_status'];
			$return['d_id_2'][$ctr] 							= $row['d_id'];
			$return['d_cycle_time_2'][$ctr] 					= number_format($row['d_cycle_time']);
			$return['accum_percent_2'][$ctr] 					= $row['accum_percent'];
			$return['grand_total_2'][$ctr] 						= number_format($row['grand_total']);
			$return['remainng_prodn_time_2'][$ctr] 				= $remainng_prodn_time;
			$return['last_actual_cavity']				        = number_format($row['d_cavty']);

			$ctr ++;
		}

		$return['ctr'] 		= $ctr;
		$return["result"] 	= json_encode($result);
		echo json_encode($return);
		exit();
	}

	function select_notification(){
		require_once('../class/oop.php');
		$return 		= $_POST; 
		$result 		= "";
		$array_fields 	= array('*');
		$table 	   		= '
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

					(( t_shots_sum.`ongoing_accumulated_shots` / `t_dieset_cycle`.`dc_maintenance_cycle`)*100) AS accum_percent,

					t_grand_total.`grand_total`
					

				from `t_dieset_cycle`
				join `db_pps`.`t_device`
				on `t_dieset_cycle`.`dc_d_id` = `t_device`.`d_id` and 
					(`t_device`.`d_id` not in 
						(
							"44","119","119","122","125","126","127","164","172","173","175","259","260","305","306","316","319","349","365","368","376","378","382","390","391","463","488","489"
						)
					)
				left join `t_shots`
				on `t_shots`.`s_d_id` = `t_dieset_cycle`.`dc_d_id`
				left join 
					(
						select `t_shots`.`s_d_id`, sum(`t_shots`.`s_count`) AS `ongoing_accumulated_shots`
						from `t_shots` 
						where `t_shots`.`s_status` = "ongoing" AND `t_shots`.`s_approval_status` = "1"
						group by `t_shots`.`s_d_id`
					) t_shots_sum
				on t_shots_sum.`s_d_id` = `t_dieset_cycle`.`dc_d_id`
				left join 
					(
						select `t_shots`.`s_d_id`, sum(`t_shots`.`s_count`) AS `grand_total`
						from `t_shots`
						group by `t_shots`.`s_d_id`
					) t_grand_total
				on t_grand_total.`s_d_id` = `t_dieset_cycle`.`dc_d_id`
				where `t_dieset_cycle`.`dc_status` = "ongoing" and `t_dieset_cycle`.`logdel` = 0
				group by `t_dieset_cycle`.`dc_d_id`
			) v_dmcms_main_page
		';
		$joins 	   		= '';
		$sql_where 		= 'WHERE accum_percent > 89';
		$sql_order 		= 'ORDER BY accum_percent DESC';
		$sql_limit 		= '';
		$result 		= DB_DMCMS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$ctr 			= 0;
		while($row = mysqli_fetch_array($result)){

			$return['d_name_2'][$ctr] 							= $row['d_name'];
			$return['accum_percent_2'][$ctr] 					= $row['accum_percent'];
			$return['dc_idd_2'][$ctr] 							= $row['dc_idd'];
			$return['d_id_2'][$ctr] 							= $row['d_id'];

			$ctr ++;
		}

		$return['ctr'] 		= $ctr;
		$return["result"] 	= json_encode($result);
		echo json_encode($return);
		exit();
	}

	function insert_shot(){
		require_once('../class/oop.php');

		$return 			= $_POST;
		$result 			= "";
		$table 				= 't_shots';
		$array_fields 		= array(
			's_d_id',
			's_date',
			's_count',
			's_machine',
			's_remarks',
			's_status',
			's_dc_id',
			's_actual_cavty',
			's_adate',
			's_shift_schedule',
			's_operator',
			's_encoded_by'
		);
		$array_values 		= array(
			$return['d_id'],
			date("Y-m-d", strtotime($return['txt_add_prodn_date']) ),
			$return['txt_add_shots'],
			$return['txt_add_machine_num'],
			$return['txt_add_remarks'],
			'ongoing',
			$return['txt_add_dc_id'],
			$return['txt_add_cavity_qty'],
			date("Y-m-d H:i:s"),
			$return['txt_add_shift'],
			$return['txt_add_in_charge'],
			$GLOBALS['session_username']
		);

		//code to compare, email if it reaches 80%
		check_for_email($return['d_id'], $return['txt_add_shots'], $return['txt_add_in_charge'], $return['txt_add_remarks'], $return['txt_add_dc_id']);

		$result 			= DB_DMCMS::getInstance()->insert_query($table,$array_fields,$array_values);
		$return['result'] 	= json_encode($result);

		echo json_encode($return);
		exit();
	}

	function check_for_email($mach_id, $plus_shots, $in_charge, $my_remarks, $my_dc_id)
	{
		require_once('../class/oop.php');
 		$my_name = ''; $my_shots = '';

		$return 		= $_POST; 
		$result 		= '';
		$array_fields 	= array('v_dmcms_main_page.*','t_mother_devices.md_device_name AS parent_name');
		$table 	   		= '
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

					t_grand_total.`grand_total`
					

				from `t_dieset_cycle`
				join `db_pps`.`t_device`
				on `t_dieset_cycle`.`dc_d_id` = `t_device`.`d_id` and 
					(`t_device`.`d_id` not in 
						(
							"44","119","119","122","125","126","127","164","172","173","175","259","260","305","306","316","319","349","365","368","376","378","382","390","391","463","488","489"
						)
					)
				left join `t_shots`
				on `t_shots`.`s_d_id` = `t_dieset_cycle`.`dc_d_id` AND `t_shots`.`s_dc_id` = `t_dieset_cycle`.`dc_idd`

				left join 
					(
						select `t_shots`.`s_d_id`, sum(`t_shots`.`s_count`) AS `ongoing_accumulated_shots`
						from `t_shots` 
						where `t_shots`.`s_status` = "ongoing" AND `t_shots`.`s_approval_status` = "1"
						group by `t_shots`.`s_d_id`
					) t_shots_sum
				on t_shots_sum.`s_d_id` = `t_dieset_cycle`.`dc_d_id`
				left join 
					(
						select `t_shots`.`s_d_id`, sum(`t_shots`.`s_count`) AS `grand_total`
						from `t_shots` where `t_shots`.`s_dc_id` = '.$my_dc_id.'
					) t_grand_total
				on t_grand_total.`s_d_id` = `t_dieset_cycle`.`dc_d_id`
				where `t_dieset_cycle`.`dc_status` = "ongoing" and `t_dieset_cycle`.`logdel` = 0
				group by `t_dieset_cycle`.`dc_d_id`
			) v_dmcms_main_page
		';
		$joins 	   		= 'LEFT JOIN t_children_allocation ON v_dmcms_main_page.d_id=t_children_allocation.ca_child_id LEFT JOIN t_mother_devices ON t_children_allocation.ca_parent_id=t_mother_devices.md_id';
		$sql_where 		= 'WHERE d_id = "'.$mach_id.'"'; //AND my_dc_id = "'.$my_dc_id.'"';
		$sql_order 		= '';
		$sql_limit 		= 'LIMIT 0,1';
		$result 		= DB_DMCMS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$ctr 			= 0;
		$accum_count    = 0;

		while($row = mysqli_fetch_array($result))
		{
			$maintenance_cycle = (int)$row['dc_maintenance_cycle'];

			$my_name 		= $row['d_name'];
			$my_shots 		= $row['grand_total'] + $plus_shots;
			$my_percentage  = ($my_shots / $maintenance_cycle) * 100;


				if($my_percentage >= 90.0)
				{
					$my_percentage_2 = 90;
					send_email_notif($my_name,$my_percentage_2,$in_charge,$my_remarks,$my_percentage);
				}
				if($my_percentage >= 80.0 && 89.9 > $my_percentage)
				{
					$my_percentage_2 = 80;
					send_email_notif($my_name,$my_percentage_2,$in_charge,$my_remarks,$my_percentage);
				}
				$ctr++;
		}

	}

	function insert_past_shot(){
		require_once('../class/oop.php');
		$return 			= $_POST;
		$result 			= "";
		$table 				= 't_shots';
		$array_fields 		= array(
			's_d_id',
			's_date',
			's_count',
			's_status',
			's_adate',
			's_operator'
		);
		$array_values 		= array(
			$return['d_id'],
			date("Y-m-d", strtotime($return['txt_add_prodn_date']) ),
			$return['txt_add_shots'],
			'past',
			date("Y-m-d H:i:s"),
			$GLOBALS['session_username']
		);
		$result 			= DB_DMCMS::getInstance()->insert_query($table,$array_fields,$array_values);
		$return['result'] 	= json_encode($result);
		echo json_encode($return);
		exit();
	}

	function select_range_of_data(){
		require_once('../class/oop.php');
		$return 		= $_POST; 
		$result 		= "";
		$array_fields 	= array('*');
		$table 	   		= 't_dieset_cycle';
		$joins 	   		= '';
		// $sql_where 		= 'WHERE logdel = 0 AND dc_start_date != 0 AND dc_d_id = "'.$return['pkid_val'].'"';//novs edit, not showing any record if date start is null
		$sql_where 		= 'WHERE logdel = 0 AND dc_d_id = "'.$return['pkid_val'].'"';
		$sql_order 		= 'ORDER BY dc_idd DESC';
		$sql_limit 		= '';
		$result 		= DB_DMCMS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$ctr 			= 0;
		while($row = mysqli_fetch_array($result)){
			$return['dc_start_date'][$ctr] 					= date('M d, Y', strtotime($row['dc_start_date']));
			$return['dc_end_date'][$ctr] 					= date('M d, Y', strtotime($row['dc_end_date']));
			$return['dc_idd'][$ctr] 						= $row['dc_idd'];

			if( $row['dc_start_date'] == '0000-00-00' || $row['dc_start_date'] == NULL){//added novs
				$return['dc_start_date'][$ctr] = 'N/A';
			}
			if( $row['dc_end_date'] == '0000-00-00' || $row['dc_end_date'] == NULL ){//added novs
				$return['dc_end_date'][$ctr] = 'N/A';
			}

			$ctr ++;
		}

		$return['ctr'] 		= $ctr;
		$return["result"] 	= json_encode($result);
		echo json_encode($return);
		exit();
	}

	function select_device_shot_records(){
		require_once('../class/oop.php');
		$return 		= $_POST; 
		$result 		= "";
		$array_fields 	= array('s_id',
								's_date',
								's_count',
								's_machine',
								's_remarks',
								's_approval_status',
								's_operator',
								's_actual_cavty',
								's_adate');
		$table 	   		= 't_shots';
		$joins 	   		= '';
		$sql_where 		= 'WHERE logdel = 0 AND s_dc_id="'.$return['dc_id'].'" AND s_dc_id <>0';
		if( !$return['dc_id'] ){
			// $sql_where 		= 'WHERE logdel = 0 AND s_dc_id <>0';
		}
		$sql_order 		= 'ORDER BY s_date ASC';
		$sql_limit 		= '';
		$result 		= DB_DMCMS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$ctr 			= 0;
		$accum_shots 	= 0;
		while($row = mysqli_fetch_array($result)){
			$d_qty = $row['s_count'];
			if($row['s_approval_status']==2){
				$d_qty = 0;
			}

			$accum_shots 								= $accum_shots+$d_qty;
			$return['s_id'][$ctr] 						= $row['s_id'];
			$return['s_date'][$ctr] 					= date('M d, Y', strtotime($row['s_date']));
			$return['s_adate'][$ctr] 					= date('M d, Y', strtotime($row['s_adate']));
			$return['s_count'][$ctr] 					= number_format($row['s_count']);
			$return['s_accum_shots'][$ctr] 				= number_format($accum_shots);
			$return['s_machine'][$ctr] 					= $row['s_machine'];
			$return['s_remarks'][$ctr] 					= $row['s_remarks'];
			$return['s_actual_cavty'][$ctr] 			= $row['s_actual_cavty'];
			$return['s_approval_status'][$ctr] 			= $row['s_approval_status'];
			$return['s_operator'][$ctr] 				= $row['s_operator'];
			$ctr++;
		}
		if($ctr>0){
			$return['s_id'] = array_reverse($return['s_id']);
			$return['s_date'] = array_reverse($return['s_date']);
			$return['s_adate'] = array_reverse($return['s_adate']);
			$return['s_count'] = array_reverse($return['s_count']);
			$return['s_accum_shots'] = array_reverse($return['s_accum_shots']);
			$return['s_machine'] = array_reverse($return['s_machine']);
			$return['s_remarks'] = array_reverse($return['s_remarks']);
			$return['s_actual_cavty'] = array_reverse($return['s_actual_cavty']);
			$return['s_approval_status'] = array_reverse($return['s_approval_status']);
			$return['s_operator'] = array_reverse($return['s_operator']);
		}

		$return['ctr'] 		= $ctr;
		$return['result'] 	= json_encode($result);
		echo json_encode($return);
		exit();
	}

	function select_parent_device_list(){
		require_once('../class/oop.php');
		$return 		= $_POST; 
		$result 		= "";
		$array_fields 	= array('md_id','md_device_name');
		$table 	   		= 't_mother_devices';
		$joins 	   		= '';
		$sql_where 		= 'WHERE md_logdel="0"';
		$sql_order 		= 'ORDER BY md_id DESC';
		$sql_limit 		= 'LIMIT 0,10';
		$result 		= DB_DMCMS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$ctr 			= 0;
		while($row = mysqli_fetch_array($result)){
			$return['md_id'][$ctr] 						= $row['md_id'];
			$return['md_device_name'][$ctr] 			= $row['md_device_name'];
			$ctr ++;
		}

		$return['ctr'] 		= $ctr;
		echo json_encode($return);
		exit();
	}

	function insert_parent_device(){
		require_once('../class/oop.php');
		$return 			= $_POST;
		$result 			= "";
		$table 				= 't_children_allocation';
		$array_fields 		= array(
			'ca_child_id',
			'ca_parent_id',
			'ca_assigned_by'
		);
		$array_values 		= array(
			$return['child_id'],
			$return['parent_id'],
			$GLOBALS['session_username']
		);
		$result 			= DB_DMCMS::getInstance()->insert_query($table,$array_fields,$array_values);
		$return['result'] 	= json_encode($result);
		echo json_encode($return);
		exit();
	}

	function check_has_child($device_pkid){
		require_once('../class/oop.php');
		$return 		= $_POST; 
		$result 		= "";
		$array_fields 	= array('ca_id');
		$table 	   		= 't_children_allocation';
		$joins 	   		= '';
		$sql_where 		= 'WHERE ca_parent_id = "'.$device_pkid.'"';
		$sql_order 		= 'ORDER BY ca_id DESC';
		$sql_limit 		= 'LIMIT 0,20';
		$result 		= DB_DMCMS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$ctr 			= 0;
		while($row = mysqli_fetch_array($result)){
			$ctr ++;
		}
		return $ctr;
		exit();
	}

	function select_children_total_data($parent_pkid){
		require_once('../class/oop.php');
		$return 		= $_POST; 
		$result 		= "";
		$array_fields 	= array('v_dmcms_main_page.*');
		$table 	   		= "
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
		$joins 	   		= 'RIGHT JOIN t_children_allocation ON v_dmcms_main_page.d_id=ca_child_id';
		// $sql_where 		= 'WHERE d_id = "1237"';
		$sql_where 		= 'WHERE ca_parent_id = "'.$parent_pkid.'" AND ca_logdel = 0';
		$sql_order 		= '';
		$sql_limit 		= '';
		$result 		= DB_DMCMS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$ctr 			= 0;
		$total_accum_shots = 0;
		$row_accumulated_shots = 0;
		while($row = mysqli_fetch_array($result)){
			$remainng_prodn_time = 'X';
			$maintenance_cycle = (int)$row['dc_maintenance_cycle']; 
			$zero=0;
			if($maintenance_cycle>=$row['ongoing_accumulated_shots']){
				$balance = $maintenance_cycle-$row['ongoing_accumulated_shots'];
				if($balance==0){
					$remainng_prodn_time = '<span class="small">N/A</span>';
					$balance = '<div class="small text-center">Maintenance Cycle Reached</div>';
					if($row['dc_maintenance_cycle']==''||$row['dc_maintenance_cycle']==0){
						$balance = '<div class="small text-center">Maintenance Cycle Not Set</div>';
					}	
				}
				else{
					if($row['d_cycle_time']==''||$row['d_cycle_time']==0){
						$remainng_prodn_time = '<span class="small">Cycle Time Not Set</span>';
					}				
					else{
						if($row['ongoing_accumulated_shots']==0){
							$remainng_prodn_time = '<span class="small">Not yet started</span>';
						}
						else{
							$bal_divide_actual_cavity = $balance/(int)$row['last_actual_cavity'];
							$bal_divide_actual_cavity_times_cycle_time = $bal_divide_actual_cavity*floatval($row['d_cycle_time']);
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

			// if ($row['ongoing_accumulated_shots'] >= ($row['dc_maintenance_cycle'] * 0.80) && ($row['ongoing_accumulated_shots'] < ($row['dc_maintenance_cycle'] * 0.9)) && ($row['dc_maintenance_cycle'] > $zero)){
			// 	$row_accumulated_shots = '	<span style="height:100%;width:100%;">'.number_format($row['ongoing_accumulated_shots']).' <span style="color:#f7c07e;" class="pull-right fa fa-exclamation-triangle"></span></span>';
			// 	$row[] = '<span style="color:#f7c07e;background-color:#f7c07e;">2 &nbsp</span>';
			// } 
			// else 

			$disabled = '';//for add shots button when 100%

			if ($row['ongoing_accumulated_shots'] >= ($row['dc_maintenance_cycle'] * 0.9) && ($row['ongoing_accumulated_shots'] < ($row['dc_maintenance_cycle'])) && ($row['dc_maintenance_cycle'] > $zero)){						
				$row_accumulated_shots = $row['ongoing_accumulated_shots'];
			} 
			else if ($row['ongoing_accumulated_shots'] >= ($row['dc_maintenance_cycle']) && $row['dc_maintenance_cycle']!='0' && ($row['dc_maintenance_cycle'] > $zero)){
				$row_accumulated_shots = $row['ongoing_accumulated_shots'];
				$disabled = 'disabled';
			}
			else if($row['dc_maintenance_cycle'] <= $zero){
				$row_accumulated_shots = 0;
			} 
			else{
				$row_accumulated_shots = $row['ongoing_accumulated_shots'];
			}

			// $return['d_name_2'][$ctr] 							= $row['d_name'];
			// $return['d_drawing_2'][$ctr] 						= $row['d_drawing'];
			// $return['d_cavty_2'][$ctr] 							= $row['d_cavty'];
			// $return['dc_maintenance_cycle_2'][$ctr] 			= $row['dc_maintenance_cycle'];
			// $return['ongoing_accumulated_shots_2'][$ctr] 		= $row['ongoing_accumulated_shots'];
			// $return['last_actual_cavity'][$ctr] 				= $row['last_actual_cavity'];
			// $return['dc_mstatus_2'][$ctr] 						= $row['dc_mstatus'];
			// $return['dc_idd_2'][$ctr] 							= $row['dc_idd'];
			// $return['dc_status_2'][$ctr] 						= $row['dc_status'];
			// $return['d_id_2'][$ctr] 							= $row['d_id'];
			// $return['d_cycle_time_2'][$ctr] 					= number_format($row['d_cycle_time']);
			// $return['accum_percent_2'][$ctr] 					= number_format($row['accum_percent']);
			// $return['grand_total_2'][$ctr] 						= number_format($row['grand_total']);
			// $return['remainng_prodn_time_2'][$ctr] 				= $remainng_prodn_time;
		// echo $row_accumulated_shots.' ';
			$total_accum_shots += $row_accumulated_shots;

			$ctr ++;
		}

		$return['total_accum_shots'] 	= $total_accum_shots;
		$return['ctr'] 					= $ctr;
		$return["result"] 				= json_encode($result);
		// print_r($return);
		return $return;

		exit();
	}

	function select_list_of_users(){
		require_once('../class/oop.php');
		$return 		= $_POST; 
		$result 		= "";
		$array_fields 	= array('username',
								'name',
								'u_access_type');
		$table 	   		= 'db_rapid.vw_module_per_user';
		$joins 	   		= 'LEFT JOIN t_users ON db_rapid.vw_module_per_user.username=t_users.u_username';
		$sql_where 		= 'WHERE module_id="168"';
		$sql_order 		= '';
		$sql_limit 		= '';
		$result 		= DB_DMCMS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$ctr 			= 0;
		while($row = mysqli_fetch_array($result)){
			$return['username'][$ctr] 					= $row['username'];
			$return['name'][$ctr] 						= $row['name'];
			$return['u_access_type'][$ctr] 				= $row['u_access_type'];
			$ctr++;
		}

		$return['ctr'] 		= $ctr;
		$return['result'] 	= json_encode($result);
		echo json_encode($return);
		exit();
	}

	function select_list_of_users_in_charge(){
		require_once('../class/oop.php');
		$return 		= $_POST; 
		$result 		= "";
		$array_fields 	= array('username',
								'name',
								'u_access_type');
		$table 	   		= 'db_rapid.vw_module_per_user';
		$joins 	   		= 'LEFT JOIN t_users ON db_rapid.vw_module_per_user.username=t_users.u_username';
		$sql_where 		= 'WHERE module_id="168" AND (u_access_type = 1 OR u_access_type = 2)';
		$sql_order 		= '';
		$sql_limit 		= '';
		$result 		= DB_DMCMS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$ctr 			= 0;
		while($row = mysqli_fetch_array($result)){
			$return['username'][$ctr] 					= $row['username'];
			$return['name'][$ctr] 						= $row['name'];
			$return['u_access_type'][$ctr] 				= $row['u_access_type'];
			$ctr++;
		}

		$return['ctr'] 		= $ctr;
		$return['result'] 	= json_encode($result);
		echo json_encode($return);
		exit();
	}

	function select_user_access(){
		require_once('../class/oop.php');
		$return 		= $_POST; 
		$result 		= "";
		$array_fields 	= array('u_access_type');
		$table 	   		= 't_users';
		$joins 	   		= '';
		$sql_where 		= 'WHERE u_username="'.$GLOBALS['session_username'].'"';
		$sql_order 		= '';
		$sql_limit 		= '';
		$result 		= DB_DMCMS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		if($row = mysqli_fetch_array($result)){
			$return['u_access_type']			= $row['u_access_type'];
		}

		$return['result'] 	= json_encode($result);
		echo json_encode($return);
		exit();
	}

	function select_user_access_arr(){
		require_once('../class/oop.php');
		$return 		= $_POST; 
		$result 		= "";
		$array_fields 	= array('u_access_type,can_add_device');
		$table 	   		= 't_users';
		$joins 	   		= '';
		$sql_where 		= 'WHERE u_username="'.$GLOBALS['session_username'].'"';
		$sql_order 		= '';
		$sql_limit 		= '';
		$result 		= DB_DMCMS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		if($row = mysqli_fetch_array($result)){
			$return['u_access_type']			= $row['u_access_type'];
			$return['can_add_device']			= $row['can_add_device'];
		}

		$return['result'] 	= json_encode($result);
		echo json_encode($return);
		exit();
	}

	function update_approve_shots(){
		require_once('../class/oop.php');
		$return 			= $_POST;
		$result 			= "";
		$table 				= 't_shots';
		$array_fields 		= array('s_approval_status');
		$array_values 		= array('1');
		$result 			= DB_DMCMS::getInstance()->approve_shots($table,$array_fields,$array_values,$return['s_id']);
		$return['result'] 	= json_encode($result);
		echo json_encode($return);
		exit();
	}

	function update_disapprove_shots(){
		require_once('../class/oop.php');
		$return 			= $_POST;
		$result 			= "";
		$table 				= 't_shots';
		$array_fields 		= array('s_approval_status');
		$array_values 		= array('2');
		$result 			= DB_DMCMS::getInstance()->approve_shots($table,$array_fields,$array_values,$return['s_id']);
		$return['result'] 	= json_encode($result);
		echo json_encode($return);
		exit();
	}

	function reset_shots(){
		require_once('../class/oop.php');
		$return 			= $_POST;
		$result 			= "";
		$table 				= 't_dieset_cycle';
		$array_fields 		= array('dc_status');
		$array_values 		= array('finished');
		$sql_where 			= 'WHERE dc_d_id = "'.$return['d_id'].'"';
		$result 			= DB_DMCMS::getInstance()->update_query_with_other_condition($table,$array_fields,$array_values,$sql_where);
		$return['result1'] 	= json_encode($result);

		$result 			= "";
		$table 				= 't_shots';
		$array_fields 		= array('s_status');
		$array_values 		= array('finished');
		$sql_where 			= 'WHERE s_d_id = "'.$return['d_id'].'"';
		$result 			= DB_DMCMS::getInstance()->update_query_with_other_condition($table,$array_fields,$array_values,$sql_where);
		$return['result2'] 	= json_encode($result);

		$result 			= "";
		$table 				= 't_shots';
		$array_fields 		= array(
			's_d_id',
			's_date',
			's_count',
			's_machine',
			's_remarks',
			's_status',
			's_dc_id',
			's_actual_cavty',
			's_adate',
			's_operator'
		);
		$array_values 		= array(
			$return['d_id'],
			date("Y-m-d"),
			'',
			'1',
			$return['txt_reset_shots_remarks'],
			'reset',
			$return['txt_reset_shots_dc_id'],
			'',
			date("Y-m-d H:i:s"),
			$GLOBALS['session_username']
		);
		$result 			= DB_DMCMS::getInstance()->insert_query($table,$array_fields,$array_values);
		$return['result3'] 	= json_encode($result);

		$result 			= "";
		$table 				= 't_dieset_cycle';
		$array_fields 		= array(
			'dc_d_id',
			'dc_start_date',
			'dc_maintenance_cycle',
			'dc_status',
			'username'
		);
		$array_values 		= array(
			$return['d_id'],
			date("Y-m-d"),
			$return['txt_reset_shots_prev_maintenance_cycle'],
			'ongoing',
			$GLOBALS['session_username']
		);
		$result 			= DB_DMCMS::getInstance()->insert_query($table,$array_fields,$array_values);
		$return['result'] 	= json_encode($result);

		$return['device_pkid'] 	= $return['d_id'];
		echo json_encode($return);
		exit();
	}

	function select_for_approvals(){
		require_once('../class/oop.php');
		$return 		= $_POST; 
		$result 		= "";
		$array_fields 	= array('s_d_id','DeviceName');
		$table 	   		= 't_shots';
		$joins 	   		= 'LEFT JOIN db_pps.tbl_dieset ON t_shots.s_d_id=db_pps.tbl_dieset.id';
		$sql_where 		= 'WHERE s_approval_status="0" AND s_d_id>0 AND s_status="ongoing" AND logdel ="0"';
		$sql_order 		= 'GROUP BY s_d_id ASC';
		$sql_limit 		= '';
		$result 		= DB_DMCMS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$ctr 			= 0;
		while($row = mysqli_fetch_array($result)){
			$return['s_d_id'][$ctr] 				= $row['s_d_id'];
			$return['DeviceName'][$ctr] 			= $row['DeviceName'];
			$ctr ++;
		}

		$return['ctr'] 		= $ctr;
		echo json_encode($return);
		exit();		
	}

	function select_shot_record_update(){
		require_once('../class/oop.php');
		$return 		= $_POST; 
		$result 		= "";
		$array_fields 	= array('*');
		$table 	   		= 't_shots';
		$joins 	   		= '';
		$sql_where 		= 'WHERE s_id="'.$return['s_id'].'"';
		$sql_order 		= '';
		$sql_limit 		= '';
		$result 		= DB_DMCMS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$ctr 			= 0;
		while($row = mysqli_fetch_array($result)){
			$return['s_date'][$ctr] 			= $row['s_date'];
			$return['s_count'][$ctr] 			= $row['s_count'];
			$return['s_actual_cavty'][$ctr] 	= $row['s_actual_cavty'];
			$return['s_machine'][$ctr] 			= $row['s_machine'];
			$return['s_remarks'][$ctr] 			= $row['s_remarks'];
			$ctr ++;
		}

		$return['ctr'] 		= $ctr;
		echo json_encode($return);
		exit();		
	}

	function update_shot_record(){
		require_once('../class/oop.php');
		$return 			= $_POST;
		$result 			= "";
		$table 				= 't_shots';
		$array_fields 		= array(
			's_date',
			's_count',
			's_machine',
			's_remarks',
			's_actual_cavty'
		);
		$array_values 		= array(
			date("Y-m-d", strtotime($return['txt_edit_prodn_date']) ),
			$return['txt_edit_shots'],
			$return['txt_edit_machine_num'],
			$return['txt_edit_remarks'],
			$return['txt_edit_cavity_qty']
		);
		$sql_where 			= 'WHERE s_id = "'.$return['s_id'].'"';
		$result 			= DB_DMCMS::getInstance()->update_query_with_other_condition($table,$array_fields,$array_values,$sql_where);
		$return['result'] 	= json_encode($result);
		echo json_encode($return);
		exit();
	}

	function insert_assign_user(){
		require_once('../class/oop.php');
		$return 			= $_POST;
		$result 			= "";
		$table 				= 't_users';
		$array_fields 		= array(
			'u_username',
			'u_access_type',
			'u_assigned_by'
		);
		$array_values 		= array(
			$return['txt_username'],
			$return['slt_access_type'],
			$GLOBALS['session_username']
		);
		$result 			= DB_DMCMS::getInstance()->insert_query($table,$array_fields,$array_values);
		$return['result'] 	= json_encode($result);
		echo json_encode($return);
		exit();		
	}

	function send_email_notif($device_name,$my_percentage, $in_charge, $my_remarks, $actual_percent)
	{

		require_once ('/var/www/DMCMSV2/dist/phpmailer/PHPMailerAutoload.php'); // for testing


		$mail = new PHPMailer(true);

		try {
		    //Server settings                                    // Enable verbose debug output
		    $mail->isSMTP();                                            // Set mailer to use SMTP
		    $mail->Host       = '192.168.3.254';  // Specify main and backup SMTP servers
		    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
		    $mail->Username   = 'issinfoservice';                     // SMTP username
		    $mail->Password   = 'pmi1234';                               // SMTP password
		    $mail->SMTPSecure = 'none';                                  // Esnable TLS encryption, `ssl` also accepted
		    $mail->Port       = 25;                                    // TCP port to connect to

		    //Recipients
		    $mail->setFrom('dmcmsv2systemnotif@pricon.ph', 'DMCMS V2 System Notification');
		   	//$mail->addAddress('mtlaural@pricon.ph');
		   	$mail->addAddress('group-iss-ppsdmcms@pricon.ph');
			$mail->addBCC('aaleoncio@pricon.ph');     // Add a recipient

		    // Content
		    $mail->isHTML(true);                                  // Set email format to HTML
		    $mail->Subject = 'Device '.$device_name.' has reached '.$my_percentage.'%-over accumulated shots';
		    $mail->Body    = 'This is to inform you that device <b>'.$device_name.'</b> has reached <b style="color:red">'.$actual_percent.'%</b> accumulated shots. <br><br><u>LAST SHOT DETAILS:</u><br><b>Encoded By: </b>'.$in_charge.'<br><b>Remarks: </b>'.$my_remarks.'<br><br>For more info, please log-in to your Rapid account. Go to http://rapid/ and DMCMSV2 under My Systems.<br><br>Notice of Disclaimer: This message (including any attachments) contains confidential information intended for a specific individual and purpose, and is protected by law. If you are not the intended recipient, you should delete this message. Any disclosure,copying, or distribution of this message, or the taking of any action based on it, is strictly prohibited.';
		    $mail->AltBody = 'This is to inform you that device '.$device_name.' has reached 80% accumulated shots. For more info, please log-in to your Rapid account. Go to http://rapid/ and DMCMSV2 under My Systems.';
		    $mail->send();
		    //echo 'Message has been sent';
		} catch (Exception $e) {
		    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
		}
	}
?>