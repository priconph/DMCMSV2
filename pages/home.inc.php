<input type="hidden" id="hdn_user_access">
<div class="panel-container-vertical">
	<div class="panel-top">
		<div class="panel-container p-3">
			<div class="row">
				<nav class="navbar fixed-top navbar-light bg-light p-0" style="box-shadow: 0 4px 6px -6px black;height:53px;">
					<div class="parent_border_top">&nbsp;</div>
					<div class="d-flex justify-content-between w-100">
						<div class="p-3">
							<i class="fas fa-folder"></i> Devices
						</div>
						<div class="p-3 text-center pt-2">
							<h4 class="badge badge-danger shadow" style="font-size:100%;">DMCMS</h4> 
						</div>
						<div class="p-3 text-right" style="margin-top:-6px;">
							<div class="dropdown float-right d-none">								
								<button class="btn btn-light dropdown-toggle btn-sm" type="button" id="btn_show_userlist">
									<i class="fas fa-users-cog"></i>
								</button>
							</div>
							<div class="dropdown float-right ml-1 d-none">
								<button class="btn btn-light dropdown-toggle btn-sm" id="btn_for_approval_notif" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									<i class="fa fa-bell fa-2x text-info"></i><span class="badge badge-secondary align-top mx-n2 my-n1 span_notif_for_approval">0</span>
								</button>
								<div class="dropdown-menu" style="margin-left: -170px;">
									<div class="px-3 py-1" href="#">For Approval Shots</div>
									<div class="overflow_auto border-bottom border-top div_notif_devices_name_dropdown_for_approval">
									</div>
									<div class="px-3 py-1 text-center small">
										<div class="custom-control custom-checkbox">
											<input type="checkbox" class="custom-control-input" id="ckb_a_show_all_100">
											<label class="custom-control-label text-primary cursor_pointer" for="ckb_a_show_all_100" id="show_all_100">Show all</label>
										</div>
									</div>
								</div>
							</div>
							<div class="dropdown float-right">
								<button class="btn btn-light dropdown-toggle btn-sm" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									<i class="fa fa-exclamation-triangle fa-2x text-warning"></i><span class="badge badge-secondary align-top mx-n2 my-n1 span_notif_90">0</span>
								</button>
								<div class="dropdown-menu" style="margin-left: -190px;">
									<div class="px-3 py-1" href="#">80% <span class="small">Reached</span></div>
									<div class="overflow_auto border-bottom border-top div_notif_devices_name_dropdown_90">
									</div>
									<div class="px-3 py-1 text-center small">
										<div class="custom-control custom-checkbox">
											<input type="checkbox" class="custom-control-input" id="ckb_a_show_all_90">
											<label class="custom-control-label cursor_pointer text-primary" for="ckb_a_show_all_90" id="show_all_90">Show all</label>
										</div>
									</div>
								</div>
							</div>
							<div class="dropdown float-right">
								<button class="btn btn-light dropdown-toggle btn-sm" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									<i class="fa fa-exclamation-triangle fa-2x text-danger"></i><span class="badge badge-secondary align-top mx-n2 my-n1 span_notif_100">0</span>
								</button>
								<div class="dropdown-menu" style="margin-left: -170px;">
									<div class="px-3 py-1" href="#">90% <span class="small">Reached</span></div>
									<div class="overflow_auto border-bottom border-top div_notif_devices_name_dropdown_100">
									</div>
									<div class="px-3 py-1 text-center small">
										<div class="custom-control custom-checkbox">
											<input type="checkbox" class="custom-control-input" id="ckb_a_show_all_100">
											<label class="custom-control-label text-primary cursor_pointer" for="ckb_a_show_all_100" id="show_all_100">Show all</label>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</nav>
			</div>
			<div class="row">
				<div class="col-sm-6">
					<div class="row" style="margin-top:50px;">
						<div class="col-sm-12">
							<h4 class="border-bottom text-right"><i>Devices with children</i></h4>
							<div class="table-responsive" style="height:300px;overflow-y:auto;">
								<table class="table table-hover table-striped table-bordered table-sm w-100" id="tbl_parent_devices">
									<thead>
										<tr>
											<th>Device Name</th>
											<th>Drawing #</th>
											<th>Cavity Qty</th>
											<th>Maint. Cycle</th>
											<th>Accum. Shots</th>
											<th>Remaining<br>Shots</th>
										</tr>
									</thead>
									<tbody class="cursor_pointer">
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="row" style="margin-top:50px;">
						<div class="col-sm-12">
							<h4 class="border-bottom text-right"><i>Standalone devices</i></h4>
							<div class="table-responsive" style="height:300px;overflow-y:auto;">
								<table class="table table-hover table-striped table-bordered table-sm w-100" id="tbl_standalone_children">
									<thead>
										<tr>
											<th>Device Name</th>
											<th>Drawing #</th>
											<th>Cavity Qty</th>
											<th>Maint. Cycle</th>
											<th>Accum. Shots</th>
											<th>Remaining<br>Shots</th>
										</tr>
									</thead>
									<tbody class="cursor_pointer">
									</tbody>
								</table>
							</div>
						</div>
					</div>	
				</div>
			</div>		
		</div>
	</div>
	<div class="splitter-horizontal">
	</div>
	<div class="panel-bottom p-3" style="overflow-y:scroll;">
		<div class="row" style="margin-bottom:3px;">	
			<div class="col-sm-12" style="box-shadow: 0 4px 6px -6px black;height:38px;">					
				<i class="fas fa-folder-open"></i> <span class="p-1 rounded bg-info text-light spn-selected-devices">Click a row to show actions preview action/s</span>
				<button type="button" class="btn btn-sm btn-primary btn_reset_filter mb-1 float-right"><i class="fa fa-sync-alt fa-spin"></i> Reset Filter</button>
				<button type="button" class="btn btn-sm btn-primary mb-1 mr-1 float-right d-none" id="btn_add_device"><i class="fa fa-plus"></i> Add Device</button>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12 pt-2">
				<div class="table-responsive">
					<table class="table table-hover table-striped table-bordered table-sm w-100" id="tbl_all_records">
						<thead class="">
							<tr>
								<th>Device Name</th>
								<th>Drawing #</th>
								<th>Cavity Qty</th>
								<th>Maint. Cycle</th>
							</tr>
						</thead>
						<tbody class="cursor_pointer"></tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="modal_device_records">
	<div class="modal-dialog modal-w-90 modal-dialog-centered" role="document">
		<div class="modal-content" style="z-index: 999999 !important;">
			<div class="modal-header">
				<h5 class="modal-title" style="font-size:98%;"><i class="fa fa-table"></i> Device's Shot Records</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body pt-0">
				<div class="row px-2 pt-0">
					<div class="d-flex align-content-start flex-wrap pb-2">
						<div class="px-3">
							<label class="small o-x text-secondary">Parent</label><br>
							<span class="font-weight-bold txt_record_parent_device_name"><span>
						</div>
						<div class="px-3">
							<label class="small o-x text-secondary">Device Name</label><br>
							<span class="font-weight-bold txt_record_device_name"><span>
						</div>
						<div class="px-3">
							<label class="small o-x text-secondary">Drawing Number</label><br>
							<span class="txt_record_drawing_number"><span>
						</div>
						<div class="px-3">
							<label class="small o-x text-secondary">Cavity Qty.</label><br>
							<span class="txt_record_cavity_qty"><span>
						</div>
						<div class="px-3">
							<label class="small o-x text-secondary">Maint. Cycle</label><br>
							<span class="txt_record_maintenance_cycle"><span>
						</div>
						<div class="px-3">
							<label class="small o-x text-secondary">Accum. Shots</label><br>
							<span class="px-2 rounded font-weight-bold div_record_accumulated_shots"><span>
						</div>
						<div class="px-3">
							<label class="small o-x text-secondary">Total Accum. Shots</label><br>
							<span class="px-2 rounded font-weight-bold div_record_grand_total"><span>
						</div>
						<div class="px-3">
							<label class="small o-x text-secondary">Rem. Shots</label><br>
							<span class="div_record_remaining_shots"><span>
						</div>
						<div class="px-3">
							<label class="small o-x text-secondary">Actl. Cavity:</label><br>
							<span class="div_record_actual_cavity"><span>
						</div>
						<div class="px-3">
							<label class="small o-x text-secondary">Rem. Time:</label><br>
							<span class="div_record_remainng_prodn_time_2"><span>
						</div>
						<div class="px-3">
							<label class="small o-x text-secondary">Range of Data:</label><br>
							<select class="form-control form-control-sm" id="select_range_of_data">
							</select>
						</div>
						<div class="px-3">
							<label class="small o-x text-secondary">Status/Remarks:</label><br>
							<span class=" div_record_status"><span>
						</div>
					</div>
				</div>
				<div class="row border-bottom border-top">
					<div class="col-sm-12 px-0">
						<table class="table table-striped table-sticky table-hover w-100" style="height:140.5px !important;" data-sticky-top="tr:first-child" data-sticky-left="tr td:first-child" data-sticky-bottom="tr:last-child" data-sticky-right="tr td:last-child" id="tbl_shots_records">
							<thead>
								<tr>
									<th>Action/s</th>
									<th>Prodn Date</th>
									<th>Shot</th>
									<th>Shot Accum</th>
									<th>Cavity Qty<br>Cycle</th>
									<th>Machine No.</th>
									<th>In-charge</th>
									<th>Remarks</th>
									<th>Date Encoded</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>					
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-sm btn-primary btn_reret_shot_open_modal d-none"><i class="fa fa-sync-alt"></i> Reset Shots</button>
				<button type="button" class="btn btn-sm btn-primary btn_assign_parent_device d-none"><i class="fas fa-angle-double-up"></i> Assign Parent Device</button>
				<button type="button" class="btn btn-sm btn-primary btn_add_shot d-none"><i class="fa fa-plus"></i> Add Shot</button>
				<button type="button" class="btn btn-sm btn-primary btn_add_past_shot d-none"><i class="fa fa-calendar-plus"></i> Add Past Shot</button>
				<button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="modal_add_shot">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" style="font-size:98%;"><i class="far fa-calendar-plus"></i> Add Shot</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="frm_add_shot">
					<div class="row">
						<div class="col-sm-12">
							<div class="alert alert-secondary">
								<label class="small">Device Name</label>
								<div class="h5 txt_add_device_name">IC26V-0803-01#IN</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label>Production Date</label>
								<input type="date" class="form-control" name="txt_add_prodn_date" required>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label>Shots</label>
								<input type="number" class="form-control" min="0" name="txt_add_shots" placeholder="0" required>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label>Actual Cavity Quantity</label>
								<input type="number" class="form-control" min="0" name="txt_add_cavity_qty" placeholder="0" required>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label>Machine No.</label>
								<input type="text" class="form-control" name="txt_add_machine_num" required>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label>Shift</label>
								<select class="form-control" name="txt_add_shift">
									<option value="1">Day</option>
									<option value="0">Night</option>
								</select>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label>In Charge</label>
								<select class="form-control" name="txt_add_in_charge" id="txt_add_in_charge">
	<!-- 								<option value="Algen Ebarle">Algen Ebarle</option>
									<option value="Allen Lopez">Allen Lopez</option>
									<option value="Alvin De Guzman">Alvin De Guzman</option>
									<option value="Angelo Pronce">Angelo Pronce</option>
									<option value="Antonio Velasquez">Antonio Velasquez</option>
									<option value="Ariel Arandia">Ariel Arandia</option> -->
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
								<label>Remarks</label>
								<textarea class="form-control" placeholder="Type here" name="txt_add_remarks"></textarea>
							</div>
						</div>
					</div>
					<input type="hidden" class="form-control txt_add_dc_id" name="txt_add_dc_id">
				</form>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-sm btn-primary" id="btn_save_shot" form="frm_add_shot"><i class="fas fa-save"></i> Save</button>
				<button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="modal_edit_shot">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" style="font-size:98%;"><i class="far fa-calendar-plus"></i> Edit Shot</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="frm_edit_shot">
					<div class="row">
						<div class="col-sm-12">
							<div class="alert alert-secondary">
								<label class="small">Device Name</label>
								<div class="h5 txt_edit_device_name"></div>
							</div>
						</div>
					</div>
					<br>
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label>Production Date</label>
								<input type="date" class="form-control" name="txt_edit_prodn_date" required>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label>Shots</label>
								<input type="number" class="form-control" name="txt_edit_shots" placeholder="0" required>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label>Actual Cavity Quantity</label>
								<input type="number" class="form-control" name="txt_edit_cavity_qty" placeholder="0" required>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label>Machine No.</label>
								<input type="text" class="form-control" name="txt_edit_machine_num" required>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
								<label>Remarks</label>
								<textarea class="form-control" placeholder="Type here" name="txt_edit_remarks"></textarea>
							</div>
						</div>
					</div>
					<input type="hidden" class="form-control txt_edit_dc_id" name="txt_edit_dc_id">
				</form>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-sm btn-primary" id="btn_save_shot_update" form="frm_edit_shot"><i class="fas fa-save"></i> Update</button>
				<button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="modal_add_past_shot">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" style="font-size:98%;"><i class="fas fa-calendar-plus"></i> Add Past Shot</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="frm_add_past_shot">
					<div class="row">
						<div class="col-sm-12">
							<div class="alert alert-secondary">
								<div class="small">Device Name</div>
								<div class="h5 txt_add_device_name"></div>
							</div>
						</div>
					</div>
					<br>
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label>Production Date</label>
								<input type="date" class="form-control" name="txt_add_prodn_date" required>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label>Shots</label>
								<input type="number" class="form-control" name="txt_add_shots" placeholder="0" required>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<div class="alert alert-info">Note: This is for previous accumulated shots only!</div>
						</div>
					</div>
					<input type="hidden" class="form-control txt_add_dc_id" name="txt_add_dc_id">
				</form>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-sm btn-primary" id="btn_save_past_shot" form="frm_add_past_shot"><i class="fas fa-save"></i> Save</button>
				<button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="modal_assign_parent_device">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" style="font-size:98%;"><i class="fas fa-angle-double-up"></i> Assign Parent Device</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="frm_assign_parent_device">
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label>Select Parent Device Name</label>
								<input type="text" class="form-control" id="txt_assign_parent_device" list="dl_assign_parent_device">
								<datalist id="dl_assign_parent_device"></datalist>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label>Selected</label>
								<input type="text" class="form-control" id="txt_assign_parent_device_selected" required readonly>
							</div>
						</div>
					</div>
				</form>
			</div>
			<input type="hidden" class="form-control" id="txt_assign_parent_device_current" name="txt_assign_parent_device_current">
			<div class="modal-footer">
				<button type="submit" class="btn btn-sm btn-primary" id="btn_save_assign_parent_device" form="frm_assign_parent_device"><i class="fas fa-save"></i> Save</button>
				<button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="mdl_show_userlist">
	<div class="modal-dialog modal-w-90 modal-dialog-centered" role="document">
		<div class="modal-content" style="z-index: 999999 !important;">
			<div class="modal-header">
				<h5 class="modal-title" style="font-size:98%;"><i class="fa fa-table"></i> List of Users</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body pt-2" style="height:305px;">
				<div class="row border-bottom border-top">
					<div class="col-sm-12 px-0">
						<table class="table table-striped table-sticky table-hover w-90" data-sticky-top="tr:first-child" data-sticky-left="tr td:first-child" data-sticky-bottom="tr:last-child" data-sticky-right="tr td:last-child" id="tbl_list_of_users">
							<thead>
								<tr>
									<th>Username</th>
									<th>Name</th>
									<th>Access Type</th>
									<th>Action/s</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>					
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-sm btn-primary" data-dismiss="modal">Save</button>
				<button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="modal_reset_shots">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" style="font-size:98%;"><i class="fa fa-sync-alt"></i> Reset Shots</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="frm_reset_shots">
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
								<label>Remarks</label>
								<textarea class="form-control" placeholder="Type here" name="txt_reset_shots_remarks"></textarea>
							</div>
						</div>
					</div>
					<input type="hidden" class="form-control txt_reset_shots_dc_id" name="txt_reset_shots_dc_id">
					<input type="hidden" class="form-control txt_reset_shots_prev_maintenance_cycle" name="txt_reset_shots_prev_maintenance_cycle">
				</form>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-sm btn-primary" id="btn_reret_shot" form="frm_reset_shots"><i class="fas fa-save"></i> Save</button>
				<button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="mdl_assign_user">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" style="font-size:98%;"><i class="fas fa-users-cog"></i> Assign User</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="frm_assign_user">
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label>Username</label>
								<input type="text" class="form-control" name="txt_username" required>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label>Access Type</label>
								<select class="form-control" name="slt_access_type">
									<option value="7">Viewer</option>	
									<option value="1">Operator</option>	
									<option value="2">Maintenance In-charge</option>
									<option value="3">Approver</option>
									<option value="4">Section Head</option>
									<option value="6">Administrator</option>
								</select>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-sm btn-primary" form="frm_assign_user"><i class="fas fa-save"></i> Save</button>
				<button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="modal_add_new_product" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id=""><i class="fa fa-plus"></i> Add New Product</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body form_group_narrow_label">
				<form id="frm_add_product">
					<div class="row">
						<div class="col-sm-12" id="alert_popup_container_add_product"></div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label>Search part name</label>
								<input type="text" class="form-control form-control-sm placeholder_search" id="txt_add_product_code" list="dl_add_product_code" required>
								<datalist id="dl_add_product_code"></datalist>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label>Part name</label>
								<input type="text" class="form-control form-control-sm" id="txt_add_product_name" readonly>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label>Start date</label>
								<input type="date" class="form-control form-control-sm" id="txt_add_product_start_date" name="txt_add_product_start_date" required>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label>Maint. cycle</label>
								<input type="number" class="form-control form-control-sm" id="txt_add_product_maint_cycle" name="txt_add_product_maint_cycle" required>
							</div>
						</div>
					</div>
<!-- 					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
								<label>Remarks</label>
								<textarea class="form-control form-control-sm" name="txt_add_product_remarks"></textarea>
							</div>
						</div>
					</div> -->
				<!-- <input type="hidden" name="txt_add_product_pkid" id="txt_add_product_pkid"> -->
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-success" form="frm_add_product">Save</button>
			</div>
		</div>
	</div>
</div>