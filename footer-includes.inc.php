		</div> <!-- container fluid -->
		<!-- jQuery -->
		<!-- <script src="./dist/admin-lte-3.0.0-alpha/plugins/jquery/jquery.min.js"></script> -->
		<!-- Bootstrap -->
		<!-- <script src="./dist/admin-lte-3.0.0-alpha/plugins/bootstrap/js/bootstrap.bundle.min.js"></script> -->
		<!-- AdminLTE App -->
		<!-- <script src="./dist/admin-lte-3.0.0-alpha/dist/js/adminlte.js"></script> -->

		<!--JQUERY-->
		<script type="text/javascript" language="javascript" src="dist/jquery/jquery-3.3.1.js"></script>
		<script type="text/javascript" language="javascript" src="dist/jquery/jquery-ui-1.12.1/jquery-ui.js"></script>

		<script type="text/javascript" language="javascript" src="dist/jquery/jquery-resizable/src/jquery-resizable.js"></script>
		<script type="text/javascript" language="javascript" src="dist/jquery/jquery-resizable/src/jquery-resizableTableColumns.js"></script>

		<script type="text/javascript" language="javascript" src="dist/popper.js/popper.min.js"></script>
		<script type="text/javascript" language="javascript" src="dist/bootstrap-4.2.1/js/bootstrap.min.js"></script>
		<!-- <script type="text/javascript" language="javascript" src="dist/bootstrap-4.2.1/js/bootstrap.bundle.min.js"></script> -->

		<script type="text/javascript" language="javascript" src="dist/DataTables/1.10.19/media/js/jquery.dataTables.min.js"></script>
		<script type="text/javascript" language="javascript" src="dist/DataTables/1.10.19/media/js/dataTables.bootstrap4.min.js"></script>

		<!--DATATABLES FIXED COLS-->
		<script type="text/javascript" language="javascript" src="dist/DataTables/FixedColumns/3.2.5/js/dataTables.fixedColumns.min.js"></script>
		<script type="text/javascript" language="javascript" src="dist/DataTables/FixedColumns/3.2.5/js/fixedColumns.bootstrap4.min.js"></script>

		<script type="text/javascript" language="javascript" src="dist/fontawesome-5.7.1/js/all.min.js"></script>
 		<script type="text/javascript" src="dist/sticky_table/sticky-table.min.js"></script>

		<!--CUSTOM JS-->
		<!-- <link rel="stylesheet" href="dist/js/main.css"> -->


		<?php
			$js_file_name = isset( $_GET['page'] )? $_GET['page'] : 'home';
			if( file_exists('dist/js/'.$js_file_name.'.js') && is_readable('dist/js/'.$js_file_name.'.js') ){
				echo '<script type="text/javascript" language="javascript" src="dist/js/'.$js_file_name.'.js?n=6"></script>';
			}
		?>
	</body>
</html>