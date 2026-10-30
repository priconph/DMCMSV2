var handler = 'dist/handler/handler.php'; 
var dt_children = '';
var dt_parent_devices = '';
var dt_standalone_children = '';
$(document).ready(function(){
	select_user_access();

	dt_all = $('#tbl_all_records').DataTable({
		"bProcessing" 				: true,
		"bServerSide" 				: true,
		"sAjaxSource" 				: "dist/server_side/dt_home.php",
		"columnDefs" 				: [{ "sClass" : "text-right", "aTargets": [2,3]},
										],
		// "columnDefs" 				: [{ "sClass" : "text-right", "aTargets": [2,3,4,5,6]},//old
		// 								{"sClass" : "text-center", "aTargets": [7]}],//old
		"fnRowCallback" 			: function( nRow, aData, iDisplayIndex, iDisplayIndexFull){
			// if((aData[4].toLowerCase()).indexOf('text-danger')>=0){
			// 	$('td', nRow).parents('tr').find('td:eq(5)').addClass('bg-danger text-white');
			// 	$('td', nRow).parents('tr').find('td:eq(5)').css('font-size','70%');
			// }
			// if((aData[4].toLowerCase()).indexOf('text-warning')>=0){
			// 	$('td', nRow).parents('tr').find('td:eq(5)').addClass('bg-warning');
			// }
		},
	});

	dt_parent_devices = $('#tbl_parent_devices').DataTable({
		"bProcessing" 				: true,
		"bServerSide" 				: true,
		"columnDefs" 				: [{ "sClass" : "text-right", "aTargets": [2,3,4,5]}],
		"sAjaxSource" 				: "dist/server_side/dt_parent_devices.php",
		"fnRowCallback" 			: function( nRow, aData, iDisplayIndex, iDisplayIndexFull){
			$('#tbl_parent_devices_filter').children().children('input').css('border-radius','0');
			if ( (aData[4].toLowerCase()).indexOf('text-danger')>=0 ){
				$('td', nRow).parents('tr').find('td:eq(5)').addClass('bg-danger text-white');
				$('td', nRow).parents('tr').find('td:eq(5)').css('font-size','70%');
			}
			if((aData[4].toLowerCase()).indexOf('text-warning')>=0){
				$('td', nRow).parents('tr').find('td:eq(5)').addClass('bg-warning');
			}

		}
	});


	dt_standalone_children = $('#tbl_standalone_children').DataTable({
		"bProcessing" 				: true,
		"bServerSide" 				: true,
		"columnDefs" 				: [{ "sClass" : "text-right", "aTargets": [2,3,4,5]}],
		"sAjaxSource" 				: "dist/server_side/dt_standalone_children.php",
		"fnRowCallback" 			: function( nRow, aData, iDisplayIndex, iDisplayIndexFull){
			$('#tbl_standalone_children_filter').children().children('input').css('border-radius','0');
			if ( (aData[4].toLowerCase()).indexOf('text-danger')>=0 ){
				$('td', nRow).parents('tr').find('td:eq(5)').addClass('bg-danger text-white');
				$('td', nRow).parents('tr').find('td:eq(5)').css('font-size','70%');
			}
			if((aData[4].toLowerCase()).indexOf('text-warning')>=0){
				$('td', nRow).parents('tr').find('td:eq(5)').addClass('bg-warning');
			}

		}
	});

	dt_users = $('#tbl_list_of_users').DataTable({
	});

	$('#tbl_parent_devices').on('click','tbody tr',function(){
		$('#tbl_all_records_processing').fadeIn();
		var oTable = $("#tbl_all_records").dataTable();	
		var oSettings = oTable.fnSettings();
		dt_all.ajax.url(oSettings.sAjaxSource+"?parent_id=" + escape($(this).find('td:eq(0) input').val())).load();
		$('#tbl_standalone_children tbody').find('tr.bg-selected').removeClass('bg-selected');
		$('#tbl_parent_devices tbody').find('tr.bg-selected').removeClass('bg-selected');
		$(this).addClass('bg-selected');
		$('#tbl_all_records_processing').fadeOut();
	});

	$('#tbl_standalone_children').on('click','tbody tr',function(){
		$('#tbl_all_records_processing').fadeIn();
		var oTable = $("#tbl_all_records").dataTable();	
		var oSettings = oTable.fnSettings();
		dt_all.ajax.url(oSettings.sAjaxSource+"?selected_pkid=" + escape($(this).find('td:eq(0) .txt_dc_id').text())).load();

		$('#tbl_standalone_children tbody').find('tr.bg-selected').removeClass('bg-selected');
		$('#tbl_parent_devices tbody').find('tr.bg-selected').removeClass('bg-selected');
		$(this).addClass('bg-selected');
		$('#tbl_all_records_processing').fadeOut();
	});

	$('#tbl_all_records').on('click','tbody tr',function(){
		$('#tbl_all_records_processing').fadeIn();
		select_device_records($(this).find('.btn_dropdown_view_records').val());
	});

	$('table').on('click','.btn_dropdown_view_records',function(){
		select_device_records( $(this).val() );
	});

	$('tbody').on('click','.btn_dropdown_add_shot',function(){
		var txt_device_name = $(this).closest('tr').find('.txt_device_name').text();
		var txt_dc_id = $(this).closest('tr').find('.txt_dc_id').text();
		$('#modal_add_shot').find('.txt_add_device_name').text(txt_device_name);
		$('#modal_add_shot').find('.txt_add_dc_id').val(txt_dc_id);

		$('#modal_add_shot').find('#btn_save_shot').val( $(this).val() );
		$('#modal_add_shot').modal('show');
	});

	$('.btn_add_shot').click(function(){

		var now = new Date();
		var day = ("0" + now.getDate()).slice(-2);
		var month = ("0" + (now.getMonth() + 1)).slice(-2);
		var today = now.getFullYear()+"-"+(month)+"-"+(day) ;

		$('#modal_add_shot input[name=txt_add_prodn_date]').val(today);
		$('#modal_add_shot input[name=txt_add_shots]').prop('max', $('.div_record_remaining_shots').text() );

		var data = {
			"action"					: "select_list_of_users_in_charge",
		}
		data = $.param(data);
		$.ajax({
			type						: "post",
			dataType 					: "json",
			data						: data,
			url							: handler,
			success						: function(data){
				var html = '';
				for(var ctr=0;ctr<data['ctr'];ctr++){
					html += '<option  value="'+data['name'][ctr]+'">'+data['name'][ctr]+'</option>';
				}
				$('#txt_add_in_charge').html(html);

			}
		});
		$('#modal_add_shot').modal('show');
	});

	$('.btn_add_past_shot').click(function(){
		$('#modal_add_past_shot').modal('show');
	});

	$('.btn_reret_shot_open_modal').click(function(){
		$('#modal_reset_shots').modal('show');
	});

	$('#t_parent_devices tbody').on('click','.btn_dropdown_view_child',function(){
	});

	$('.btn_assign_parent_device').click(function(){
		// var data = {
		// 	"action"					: "select_parent_device_current",
		// 	"keyword" 					: $('#txt_assign_parent_device').val()
		// }
		// data = $.param(data);
		// $.ajax({
		// 	type						: "post",
		// 	dataType 					: "json",
		// 	data						: data,
		// 	url							: handler,
		// 	success						: function(data){
		// 	var html = '';
		// 		for(var ctr=0;ctr<data['ctr'];ctr++){
		// 			html += '<option data-fkid="'+data['d_id'][ctr]+'" value="'+data['d_name'][ctr]+'">'+data['d_name'][ctr]+'</option>';
		// 		}
		// 		$('#modal_assign_parent_device').find('#dl_assign_parent_device').html(html);
		// 	}
		// });
		$('#modal_assign_parent_device').modal('show');
	});

	$('#txt_assign_parent_device').keyup(function(){
		var data = {
			"action"					: "select_parent_device_list",
			"keyword" 					: $('#txt_assign_parent_device').val()
		}
		data = $.param(data);
		$.ajax({
			type						: "post",
			dataType 					: "json",
			data						: data,
			url							: handler,
			success						: function(data){
			var html = '';
				for(var ctr=0;ctr<data['ctr'];ctr++){
					html += '<option data-fkid="'+data['md_id'][ctr]+'" value="'+data['md_device_name'][ctr]+'">'+data['md_device_name'][ctr]+'</option>';
				}
				$('#modal_assign_parent_device').find('#dl_assign_parent_device').html(html);
			}
		});
	});

	$('#txt_assign_parent_device').change(function(){
		$('#txt_assign_parent_device_selected').val('');
		var val = $(this).val();
		var obj = $("#dl_assign_parent_device").find("option[value='" + val + "']");
		if(obj != null && obj.length > 0){
			$('#txt_assign_parent_device_selected').val(val);
		}
	});

	$(".panel-top").resizable({
		handleSelector: ".splitter-horizontal",
		resizeWidth: false,
		handles: 's'
	});

	$('.div_notif_devices_name_dropdown_100').on('click','a.dropdown-item', function(){
		$('#tbl_all_records_processing').fadeIn();
		select_device_records( $(this).data('val') );
	});

	$('.div_notif_devices_name_dropdown_90').on('click','a.dropdown-item', function(){
		$('#tbl_all_records_processing').fadeIn();
		select_device_records( $(this).data('val') );
	});

	$('.div_notif_devices_name_dropdown_for_approval').on('click','a.dropdown-item', function(){
		$('#tbl_all_records_processing').fadeIn();
		select_device_records( $(this).data('val') );
	});

	$('#ckb_a_show_all_90, #ckb_a_show_all_100').on('click', function(){
		var oTable = $("#tbl_all_records").dataTable();		// oTable = $("#"+dt_records.table().node().id).dataTable();
		var oSettings = oTable.fnSettings();

		$('.span_notif_90').closest('button').removeClass('active');
		$('.span_notif_100').closest('button').removeClass('active');

		if( $('#ckb_a_show_all_90').is(':checked') || $('#ckb_a_show_all_100').is(':checked') ){
			if( $('#ckb_a_show_all_90').is(':checked') ){
				dt_all.ajax.url(oSettings.sAjaxSource+"?reached_percent=" + escape( '90' ) ).load();
				$('.span_notif_90').closest('button').addClass('active');
			}
			if( $('#ckb_a_show_all_100').is(':checked') ){
				dt_all.ajax.url(oSettings.sAjaxSource+"?reached_percent=" + escape( '100' ) ).load();
				$('.span_notif_100').closest('button').addClass('active');
			}
			if( $('#ckb_a_show_all_90').is(':checked') && $('#ckb_a_show_all_100').is(':checked') ){
				dt_all.ajax.url(oSettings.sAjaxSource+"?reached_percent=" + escape( 'both' ) ).load();
			}
		}
		else{
			dt_all.ajax.url(oSettings.sAjaxSource).load();
		}
	});

	$('#frm_add_shot').submit(function(e){
		e.preventDefault();
		var data = {
			"action" 		: "insert_shot",
			"d_id" 			: $('#btn_save_shot').val(),
		}
		data = $.param( data ) + "&" + $(this).serialize();
		console.log(data);
		$.ajax({
			type 			: "post",
			dataType 		: "json",
			data 			: data,
			url 			: handler,
			success 		: function(data){
				alert('Successfully added!');
				select_device_records(data['d_id']);
				// select_device_shot_records($('#select_range_of_data').val());
				$('#frm_add_shot').closest('.modal').modal('hide');
				dt_all.ajax.reload();
			}
		});
	});

	$('#frm_add_past_shot').submit(function(e){
		e.preventDefault();
		var data = {
			"action" 		: "insert_past_shot",
			"d_id" 			: $('#btn_save_past_shot').val(),
		}
		data = $.param( data ) + "&" + $(this).serialize();
		console.log(data);
		$.ajax({
			type 			: "post",
			dataType 		: "json",
			data 			: data,
			url 			: handler,
			success 		: function(data){
				alert('Successfully added!');
				select_device_records(data['d_id']);
				$('#frm_add_past_shot').closest('.modal').modal('hide');
				dt_all.ajax.reload();
				// dt_shots.ajax.reload();
			}
		});
	});

	$('#frm_reset_shots').submit(function(e){
		e.preventDefault();
		var data = {
			"action" 		: "reset_shots",
			"d_id" 			: $('#btn_reret_shot').val(),
		}
		data = $.param( data ) + "&" + $(this).serialize();
		console.log(data);
		$.ajax({
			type 			: "post",
			dataType 		: "json",
			data 			: data,
			url 			: handler,
			success 		: function(data){
				alert('Shots have been reset!');
				$('#frm_reset_shots').closest('.modal').modal('hide');
				dt_all.ajax.reload();
				dt_parent_devices.ajax.reload();
				select_device_records(data['d_id']);
				// select_device_records(data['device_pkid']);
			}
		});
	});

	$('#frm_assign_parent_device').submit(function(e){
		e.preventDefault();
		$('#tbl_parent_devices_processing').fadeIn();
		var parent_id = 0;
		var val = $('#txt_assign_parent_device_selected').val();
		var obj = $("#dl_assign_parent_device").find("option[value='" + val + "']");
		if(obj != null && obj.length > 0){
			 parent_id = $(obj).data('fkid');
		}
		var child_id = $('#btn_save_assign_parent_device').val()

		if(parent_id == '' || child_id == ''){
			alert('Please enter a valid data.');
			return;
		}
		var data = {
			"action" 				: "insert_parent_device",
			"child_id" 				: child_id,
			"parent_id" 			: parent_id,
		}
		data = $.param( data ) + "&" + $(this).serialize();
		$.ajax({
			type 			: "post",
			dataType 		: "json",
			data 			: data,
			url 			: handler,
			success 		: function(data){
				alert('Parent has been assigned!');
				$('#frm_assign_parent_device').closest('#modal_assign_parent_device').modal('hide');
				select_device_records(data['child_id']);
				// dt_all.ajax.reload();
				// dt_shots.ajax.reload();
				// reload children table
			}
		});
	});

	$(document).on('show.bs.modal', '.modal', function () {
		var zIndex = 1040 + (10 * $('.modal:visible').length);
		$(this).css('z-index', zIndex);
		setTimeout(function() {
			$('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
		}, 0);
	});

	$(function(){
		$('[data-toggle="popover"]').popover()
		select_notification();
	})

	$('#btn_show_userlist').click(function(){
		console.log('test');
		$('#mdl_show_userlist').modal('show');
		select_list_of_users();
	});

	// setInterval(function(){
	// 	select_for_approvals();
	// },5000);

	$('.btn_reset_filter').click(function(){
		var oTable = $("#tbl_all_records").dataTable();		// oTable = $("#"+dt_records.table().node().id).dataTable();
		var oSettings = oTable.fnSettings();
			dt_all.ajax.url(oSettings.sAjaxSource).load();
	});

	$('#tbl_shots_records tbody').on('click','.a_for_approval_shots',function(){
		var s_id = $(this).attr('id');
		var s_accum_shots = $(this).parent().find('td:eq(3)').text();
		var r = confirm("Are you sure you want to proceed?");
		if(r==true) {
			var data = {
				'action'				: 'update_approve_shots',
				's_id'					: s_id
			}
			data = $.param(data) + "&" + $(this).serialize();
			$.ajax({
				type 	: "post",
				dataType: "json",
				data 	: data,
				url 	: handler,
				success : function(data){
					select_device_shot_records($('#select_range_of_data').val());
					$('#div_record_accumulated_shots').text(s_accum_shots);
					alert('Success!');
				}
			});
		}
	});

	$('#select_range_of_data').change(function(){
		select_device_shot_records($(this).val());
	});

	$('#tbl_shots_records tbody').on('click','.a_for_disapproval_shots',function(){
		var s_id = $(this).attr('id');
		var r = confirm("Are you sure you want to proceed?");
		if(r==true) {
			var data = {
				'action'				: 'update_disapprove_shots',
				's_id'					: s_id
			}
			data = $.param(data) + "&" + $(this).serialize();
			$.ajax({
				type 	: "post",
				dataType: "json",
				data 	: data,
				url 	: handler,
				success : function(data){
					select_device_shot_records($('#select_range_of_data').val());
					alert('Success!');
				}
			});
		}
	});

	$('#tbl_shots_records tbody').on('click','.a_for_approval_edit_data',function(){
		var parent_container = $('#modal_edit_shot');
		var s_id = $(this).attr('id');
		var r = confirm("Proceed to edit?");
		$('#btn_save_shot_update').val(s_id);
		var txt_record_device_name = $(this).closest('.modal').find('.txt_record_device_name').text();
		$(parent_container).find('.txt_edit_device_name').text(txt_record_device_name);
		if(r==true) {
			var data = {
				'action'				: 'select_shot_record_update',
				's_id'					: s_id
			}
			data = $.param(data) + "&" + $(this).serialize();
			$.ajax({
				type 	: "post",
				dataType: "json",
				data 	: data,
				url 	: handler,
				success : function(data){
					for(var ctr=0;ctr<data['ctr'];ctr++){
						$(parent_container).find('[name=txt_edit_prodn_date]').val(data['s_date'][ctr]);
						$(parent_container).find('[name=txt_edit_shots]').val(data['s_count'][ctr]);
						$(parent_container).find('[name=txt_edit_cavity_qty]').val(data['s_actual_cavty'][ctr]);
						$(parent_container).find('[name=txt_edit_machine_num]').val(data['s_machine'][ctr]);
						$(parent_container).find('[name=txt_edit_remarks]').val(data['s_remarks'][ctr]);
					}
					$(parent_container).modal('show');
				}
			});
		}
	});

	$('#frm_edit_shot').submit(function(e){
		e.preventDefault();
		var data = {
			"action" 		: "update_shot_record",
			"s_id" 			: $('#btn_save_shot_update').val(),
		}
		data = $.param( data ) + "&" + $(this).serialize();
		console.log(data);
		$.ajax({
			type 			: "post",
			dataType 		: "json",
			data 			: data,
			url 			: handler,
			success 		: function(data){
				alert('Successfully updated!');
				select_device_records(data['txt_edit_dc_id']);
				select_device_shot_records($('#select_range_of_data').val());
				$('#frm_edit_shot').closest('.modal').modal('hide');
				dt_all.ajax.reload();
				dt_parent_devices.ajax.reload();
				dt_standalone_children.ajax.reload();
				dt_all.ajax.reload();
			}
		});
	});

	$('#frm_assign_user').submit(function(e){
		e.preventDefault();
		var data = {
			"action" 		: "insert_assign_user"
		}
		data = $.param( data ) + "&" + $(this).serialize();
		$.ajax({
			type 			: "post",
			dataType 		: "json",
			data 			: data,
			url 			: handler,
			success 		: function(data){
				select_list_of_users();
				alert('Successfully saved!');
				$('#mdl_assign_user').modal('hide');
			}
		});
	});

	$('#tbl_list_of_users').on('click','.a_assign_user ',function(e){
		e.preventDefault();
		var user_username = $(this).parent().parent().find('td:eq(0)').text();
		$('input[name=txt_username').val(user_username);
		$('#mdl_assign_user').modal('show');
	});

	$('#btn_add_device').click(function(){
		var mdl = $('#modal_add_new_product');
		$(mdl).find('input').val('');
		$(mdl).find('select').val('');
		$(mdl).find('textarea').val('');
		$(mdl).find('datalist').html('');
		$('#modal_add_new_product').modal('show');
	});

	$('#txt_add_product_code').on('keyup', function(e){
		var thiss = $(this);
		var dl_add_product_code = $(thiss).closest('.form-group').find('#dl_add_product_code');
		$(dl_add_product_code).html('');
		data 				= {
			"action" 		: "select_parts_code_list",
			"keyword" 		: $(this).val()
		}
		$.ajax({
			type 			: "post",
			dataType 		: "json",
			data 			: data,
			url 			: handler,
			success 		: function(data){
				var html = '';
				for (var ctr = 0; ctr < data['ctr']; ctr++) {
					html += '<option data-pkid = "' + data['d_id'][ctr] + '" data-cat = "' + data['d_maintenance'][ctr] + '" value = "' + data['d_name'][ctr] + '">' + data['d_name'][ctr] + '</option>';
				}
				$(dl_add_product_code).html(html);
				if(e.keyCode == 13 || e.keyCode == undefined){

					// var pkid = $(dl_add_product_code).find('option').filter(function() {//check if value exist in options
					// 	return this.value == $(thiss).val();
					// }).data('pkid');
					$(thiss).closest('.modal').find('#txt_add_product_name').val('').attr('data-fkid','0');
					$(thiss).closest('.modal').find('#txt_add_product_maint_cycle').val('');

					var pkid = $(dl_add_product_code).find('option[value="'+ $(thiss).val() +'"]').attr('data-pkid');
					var pkid = pkid ? pkid : 0;
					$(thiss).closest('.modal').find('#txt_add_product_name').attr('data-fkid',pkid);
					$(thiss).closest('.modal').find('#txt_add_product_name').val( $(dl_add_product_code).find('option[data-pkid="'+pkid+'"]').text() );
					$(thiss).closest('.modal').find('#txt_add_product_maint_cycle').val( $(dl_add_product_code).find('option[data-pkid="'+pkid+'"]').attr('data-cat') );
					// $(dl_add_product_code).find('option').each(function(index) {//get the option text
					// 	if( $(thiss).val() == $(this).val() ){
					// 		$(thiss).closest('.modal').find('#txt_add_product_name').val( $(this).text() );
					// 	}
					// });
				}
			}
		});
	});

	$('#frm_add_product').on('submit', function(e){
		e.preventDefault();
		var thiss = $(this);
		if( $(thiss).find('#txt_add_product_name').attr('data-fkid') == 0 || $(thiss).find('#txt_add_product_name').attr('data-fkid') == '' || $(thiss).find('#txt_add_product_name').attr('data-fkid') == "undefined" ){
			alert('Please select a valid Part Name.');
			return;
		}
		var data = {
			"action" 			: "insert_new_maint_cycle",
			"fkid" 				: $(thiss).find('#txt_add_product_name').attr('data-fkid'),
		}
		data = $.param(data)+"&"+$(this).serialize();
		$.ajax({
			type 				: "post",
			dataType 			: "json",
			data 				: data,
			url 				: handler,
			success 			: function(data){
				//---
				if( ($.parseJSON(data['result'])).indexOf("expired") >= 0 ){
					alert($.parseJSON(data['result']));
					parent.location.href = 'http://'+window.location.hostname;
					return;
				}
				//---
				alert( $.parseJSON(data['result']) );
				$(thiss).closest('.modal').modal('hide');
				dt_all.ajax.reload();
				dt_parent_devices.ajax.reload();
			}
		});
	});

});

function number_with_comma(val){
	if(val == null || val == NaN){
		return 0;
	}
	while (/(\d+)(\d{3})/.test(val.toString())){
		val = val.toString().replace(/(\d+)(\d{3})/, '$1'+','+'$2');
	}
	return val;
}

function select_device_records(data_val){
	var parent_container = '#modal_device_records';

	$(parent_container).find('.i_record_icon').removeClass('fa-exclamation-triangle text-danger text-warning').addClass('fa-info-circle text-info');
	$(parent_container).find('.div_record_alert').removeClass('alert-info alert-warning alert-danger').addClass('alert-info');

	$(parent_container).find('.txt_record_device_name').text('---');
	$(parent_container).find('.txt_record_drawing_number').text('---');
	$(parent_container).find('.txt_record_maintenance_cycle').text('---');
	$(parent_container).find('.txt_record_cavity_qty').text('---');

	$(parent_container).find('.div_record_accumulated_shots').text('---');
	$(parent_container).find('.div_record_grand_total').text('---');

	$(parent_container).find('.div_record_remaining_shots').text('---');
	$(parent_container).find('.div_record_grand_total').text('---');

	$(parent_container).find('#select_range_of_data').html('');

	$(parent_container).find('.btn_add_shot').prop('disabled',true);
	var data = {
		"action"					: "select_device_records",
		"pkid_val"					: data_val,
	}
	data = $.param(data);
	$.ajax({
		type						: "post",
		dataType 					: "json",
		data						: data,
		url							: handler,
		success						: function(data){			
			for(var ctr=0;ctr<data['ctr'];ctr++){
				$(parent_container).find('.txt_record_device_name').text( data['d_name_2'][ctr] );
				$(parent_container).find('.txt_record_drawing_number').text( data['d_drawing_2'][ctr] );
				$(parent_container).find('.txt_record_maintenance_cycle').text( number_with_comma(data['dc_maintenance_cycle_2'][ctr]) );
				$(parent_container).find('.txt_record_cavity_qty').text( data['d_cavty_2'][ctr] );

				var remaining_shots 	= data['dc_maintenance_cycle_2'][ctr] - data['ongoing_accumulated_shots_2'][ctr];
				remaining_shots = number_with_comma( remaining_shots );
				var icon_class 			= 'fa-info-circle text-info';
				var alert_class 		= 'alert-info';

				var accum_percent_2 	= data['accum_percent_2'][ctr];
				if( accum_percent_2 > 100 ){
					remaining_shots = 'N/A';
					icon_class 			= 'fa-exclamation-triangle text-danger';
					$('.div_record_accumulated_shots').removeClass('bg-warning');	
					$('.div_record_accumulated_shots').addClass('bg-danger');
					$('.div_record_accumulated_shots').addClass('text-light');

					$('.div_record_status').html('<i class="fas fa-exclamation-circle text-danger"></i> Shots exceeds devices\' limit');
				}
				else if( accum_percent_2 == 100 ){
					remaining_shots = 'N/A';
					icon_class 			= 'fa-exclamation-triangle text-danger';

					$('.div_record_accumulated_shots').removeClass('bg-warning');					
					$('.div_record_accumulated_shots').addClass('bg-danger');
					$('.div_record_accumulated_shots').addClass('text-light');

					$('.div_record_status').html('<i class="fas fa-exclamation-circle text-danger"></i> Shots limit is reached');
				}
				else if( accum_percent_2 >= 90 ){
					remaining_shots = 'N/A';
					icon_class 			= 'fa-exclamation-triangle text-danger';
					//novs 3/13/21 req of mam milly
					if(data['session_username']=='millaur' || data['session_username']=='hendegu' || data['session_username']=='hmbuquis'){
						$(parent_container).find('.btn_add_shot').prop('disabled',false);
					}

					$('.div_record_accumulated_shots').removeClass('bg-warning');					
					$('.div_record_accumulated_shots').addClass('bg-danger');
					$('.div_record_accumulated_shots').addClass('text-light');

					$('.div_record_status').html('<i class="fas fa-exclamation-circle text-danger"></i> Accumulated shots already at 90%');
				}
				else if( accum_percent_2 >= 80 ){
					icon_class 			= 'fa-exclamation-triangle text-warning';
					$(parent_container).find('.btn_add_shot').prop('disabled',false);

					$('.div_record_accumulated_shots').removeClass('text-light');
					$('.div_record_accumulated_shots').removeClass('bg-danger');
					$('.div_record_accumulated_shots').addClass('bg-warning');

					$('.div_record_status').html('<i class="fas fa-exclamation-circle text-warning"></i> Accumulated shots already at 80%');
				}
				else{
					$(parent_container).find('.btn_add_shot').prop('disabled',false);
					$('.div_record_status').html('<i class="fas fa-exclamation-circle text-info"></i> Normal');
				}

				$(parent_container).find('.div_record_accumulated_shots').text(number_with_comma(data['ongoing_accumulated_shots_2'][ctr]));
				$(parent_container).find('.div_record_grand_total').text(number_with_comma(data['grand_total_2'][ctr]));

				$(parent_container).find('.div_record_remaining_shots').text(remaining_shots);
				$(parent_container).find('.div_record_actual_cavity').text(data['last_actual_cavity']);//new
				$(parent_container).find('.div_record_remainng_prodn_time_2').text( data['remainng_prodn_time_2'][ctr]);//new
				$(parent_container).find('.div_record_grand_total').text( number_with_comma(data['grand_total_2'][ctr]));

				if(data['parent_name'][ctr]==null){
					$(parent_container).find('.txt_record_parent_device_name').parent().fadeOut();
				}
				else{
					$(parent_container).find('.txt_record_parent_device_name').text(data['parent_name'][ctr]);
					$(parent_container).find('.txt_record_parent_device_name').parent().fadeIn();
				}

				$(parent_container).find('.i_record_icon').removeClass('fa-info-circle text-info').addClass(icon_class);
				$(parent_container).find('.div_record_alert').removeClass('alert-info').addClass(alert_class);

				$('#modal_add_shot').find('.txt_add_device_name').text( data['d_name_2'][ctr] );
				$('#modal_add_shot').find('.txt_add_dc_id').val( data['dc_idd_2'][ctr] );
				$('#modal_add_shot').find('#btn_save_shot').val( data['d_id_2'][ctr] );

				$('#modal_add_past_shot').find('.txt_add_device_name').text( data['d_name_2'][ctr] );
				$('#modal_add_past_shot').find('.txt_add_dc_id').val( data['dc_idd_2'][ctr] );
				$('#modal_add_past_shot').find('#btn_save_past_shot').val( data['d_id_2'][ctr] );

				$('#modal_reset_shots').find('#btn_reret_shot').val( data['d_id_2'][ctr] );
				$('#modal_reset_shots').find('.txt_reset_shots_dc_id').val( data['dc_idd_2'][ctr] );
				$('#modal_reset_shots').find('.txt_reset_shots_prev_maintenance_cycle').val( data['dc_maintenance_cycle_2'][ctr] );



				$('#modal_assign_parent_device').find('#btn_save_assign_parent_device').val(data['d_id_2'][ctr]);

				$('#modal_edit_shot').find('.txt_edit_dc_id').val( data['d_id_2'][ctr] );


				select_range_of_data_options(data_val, parent_container);
				$('#tbl_all_records_processing').fadeOut();
			}
		}
	});
}

function select_range_of_data_options(pkid_val, parent_container){
	var data = {
		"action"					: "select_range_of_data",
		"pkid_val" 					: pkid_val
	}
	data = $.param(data);
	$.ajax({
		type						: "post",
		dataType 					: "json",
		data						: data,
		url							: handler,
		success						: function(data){
			var html = '';
			for(var ctr=0;ctr<data['ctr'];ctr++){
				html += '<option value="'+data['dc_idd'][ctr]+'">'+data['dc_start_date'][ctr]+' - '+data['dc_end_date'][ctr]+'</option>';
			}
			$(parent_container).find('#select_range_of_data').html(html);
			select_device_shot_records($('#select_range_of_data').val());
		}
	});
}

function select_device_shot_records(dc_id){
	var data = {
		"action"					: "select_device_shot_records",
		"dc_id"						: dc_id,
	}
	data = $.param(data);
	$.ajax({
		type						: "post",
		dataType 					: "json",
		data						: data,
		url							: handler,
		success						: function(data){
			var html = '';
			$('#tbl_shots_records tbody').empty();
			if($('#hdn_user_access').val()=='6'||$('#hdn_user_access').val()=='3'){
				for(var ctr=0;ctr<data['ctr'];ctr++){
					html += '<tr>';

					switch(data['s_approval_status'][ctr]){
						case '0':
							html += '<td class="text-center"><a href="#" class="a_for_approval_shots text-success" id="'+data['s_id'][ctr]+'"><i class="fas fa-thumbs-up"></i></a> <a href="#" class="a_for_approval_edit_data" id="'+data['s_id'][ctr]+'"><i class="fas fa-edit"></i></a> <a href="#" class="a_for_disapproval_shots text-danger" id="'+data['s_id'][ctr]+'"><i class="fas fa-thumbs-down"></i></a></td>';							
							break;
						case '2':
							html += '<td><small class="text-danger">Disapproved</small> <a href="#" class="a_for_approval_edit_data" id="'+data['s_id'][ctr]+'"><i class="fas fa-edit"></i></a></td>';
							break;
						default:
							html += '<td></td>';
							break;
					}					

					html += '	<td>'+data['s_date'][ctr]+'</td>';
					html += '	<td class="text-right">'+data['s_count'][ctr]+'</td>';
					html += '	<td class="text-right">'+data['s_accum_shots'][ctr]+'</td>';
					html += '	<td class="text-right">'+data['s_actual_cavty'][ctr]+'</td>';
					html += '	<td>'+data['s_machine'][ctr]+'</td>';
					html += '	<td>'+data['s_operator'][ctr]+'</td>';
					html += '	<td>'+data['s_remarks'][ctr]+'</td>';
					html += '	<td>'+data['s_adate'][ctr]+'</td>';
					html += '</tr>';
				}				
			}
			else{
				for(var ctr=0;ctr<data['ctr'];ctr++){
					html += '<tr>';
					switch(data['s_approval_status'][ctr]){
						case '0':
							html += '<td class="text-center"><small class="text-primary">For Approval</small></td>';							
							break;
						case '2':
							html += '<td><small class="text-danger">Disapproved</small> <a href="#" class="a_for_approval_edit_data" id="'+data['s_id'][ctr]+'"><i class="fas fa-edit"></i></a></td>';
							break;
						default:
							html += '<td></td>';
							break;
					}	
					html += '	<td>'+data['s_date'][ctr]+'</td>';
					html += '	<td class="text-right">'+data['s_count'][ctr]+'</td>';
					html += '	<td class="text-right">'+data['s_accum_shots'][ctr]+'</td>';
					html += '	<td class="text-right">'+data['s_actual_cavty'][ctr]+'</td>';
					html += '	<td>'+data['s_machine'][ctr]+'</td>';
					html += '	<td>'+data['s_operator'][ctr]+'</td>';
					html += '	<td>'+data['s_remarks'][ctr]+'</td>';
					html += '	<td>'+data['s_adate'][ctr]+'</td>';
					html += '</tr>';
				}
			}
			$('#tbl_shots_records tbody').append(html);
			$('#modal_device_records').modal('show');
		}
	});
}

function select_notification(){
	$('.span_notif_100').text('').hide();
	$('.span_notif_90').text('').hide();
	$('.div_notif_devices_name_dropdown_100').html('No records found.');
	$('.div_notif_devices_name_dropdown_90').html('No records found.');
	var data = {
		"action"					: "select_notification",
	}
	data = $.param(data);
	$.ajax({
		type						: "post",
		dataType 					: "json",
		data						: data,
		url							: handler,
		success						: function(data){
			var notif_100_count = 0;
			var notif_90_count = 0;

			var notif_devices_name_dropdown_100 = '';
			var notif_devices_name_dropdown_90 = '';
			for(var ctr=0;ctr<data['ctr'];ctr++){

				if( data['accum_percent_2'][ctr] >= 90 ){
					notif_100_count ++;
					notif_devices_name_dropdown_100 += '<a class="dropdown-item" data-val="'+data['d_id_2'][ctr]+'" href="#">'+data['d_name_2'][ctr]+'</a>';
				}
				else{
					notif_90_count ++;
					notif_devices_name_dropdown_90 += '<a class="dropdown-item" data-val="'+data['d_id_2'][ctr]+'" href="#">'+data['d_name_2'][ctr]+'</a>';
				}
			}
			if( notif_100_count ){
				$('.span_notif_100').text(notif_100_count).show();
				$('.div_notif_devices_name_dropdown_100').html(notif_devices_name_dropdown_100);
			}
			if( notif_90_count ){
				$('.span_notif_90').text(notif_90_count).show();
				$('.div_notif_devices_name_dropdown_90').html(notif_devices_name_dropdown_90);
			}
		}
	});
	
	data = '';
	data = {
		"action"					: "select_for_approvals",
	}
	data = $.param(data);
	$.ajax({
		type						: "post",
		dataType 					: "json",
		data						: data,
		url							: handler,
		success						: function(data){
			var notif_for_approvals = '';		
			for(var ctr=0;ctr<data['ctr'];ctr++){
				notif_for_approvals += '<a class="dropdown-item" data-val="'+data['s_d_id'][ctr]+'" href="#">'+data['DeviceName'][ctr]+'</a>';
			}
			$('.div_notif_devices_name_dropdown_for_approval').html(notif_for_approvals);
			$('.span_notif_for_approval').text(ctr);
		}
	});	
}

function select_list_of_users(){
	var data = {
		"action"					: "select_list_of_users",
	}
	data = $.param(data);
	$.ajax({
		type						: "post",
		dataType 					: "json",
		data						: data,
		url							: handler,
		success						: function(data){
			var html = '';
			$('#tbl_list_of_users tbody').empty();
			for(var i=0;i<data['ctr'];i++){
				html += '<tr>';
				html += '	<td>'+data['username'][i]+'</td>';
				html += '	<td>'+data['name'][i]+'</td>';
				// html += '	<td>'+data['u_access_type'][i]+'</td>';

				if (data['u_access_type'][i] == '1'){
					html += '<td>Operator Module</td>';
				}else if (data['u_access_type'][i] == '2'){
					html += '<td>Maintenance In-charge Module</td>';
				}else if (data['u_access_type'][i] == '3'){
					html += '<td>Approver Module</td>';
				}else if (data['u_access_type'][i] == '4'){
					html += '<td>Section Head Module</td>';
				}else if (data['u_access_type'][i] == '6'){
					html += '<td>Administrator</td>';
				}else{
					html += '<td><i>For Assign</i></td>';
				}

				switch(data['u_access_type'][i]){
					case null:
						html += '	<td><a href="#" class="a_assign_user"><i class="fa fa-edit"></i></a></td>';
						break;
					default:
						html += '	<td>Active</td>';
						break;
				}
				html += '</tr>';
			}
			$('#tbl_list_of_users tbody').append(html);
			$('.wrapper-sticky').css('max-height','300px !important');
		}
	});
}

function select_user_access(){
	var data = {
		"action"					: "select_user_access_arr",
	}
	data = $.param(data);
	$.ajax({
		type						: "post",
		dataType 					: "json",
		data						: data,
		url							: handler,
		success						: function(data){
			console.log(data['u_access_type']);
			switch(data['u_access_type']){
				case '6'://admin
					$('#hdn_user_access').val('6');
					$('#btn_show_userlist').parent().removeClass('d-none');
					$('.btn_reret_shot_open_modal').removeClass('d-none');
					$('.btn_add_shot').removeClass('d-none');
					$('.btn_add_past_shot').removeClass('d-none');
					$('.btn_assign_parent_device').removeClass('d-none');
					$('#btn_for_approval_notif').parent().removeClass('d-none');
					break;
				case '1':
					$('#hdn_user_access').val('1');
					$('.btn_add_shot').removeClass('d-none');
					break;
				case '2':
					$('#hdn_user_access').val('2');
					$('.btn_reret_shot_open_modal').removeClass('d-none');
					break;
				case '3'://approvers
					$('#hdn_user_access').val('3');
					$('.btn_add_shot').removeClass('d-none');
					$('.btn_add_past_shot').removeClass('d-none');
					$('.btn_assign_parent_device').removeClass('d-none');
					$('#btn_for_approval_notif').parent().removeClass('d-none');
					break;
			}

			switch(data['can_add_device']){
				case '1':
					$('#btn_add_device').removeClass('d-none');
					$('.btn_assign_parent_device').removeClass('d-none');
					break;
			}
		}
	});	
}