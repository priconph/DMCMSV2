<?php
	
	// $page = isset( $_GET['page'] )? $_GET['page'] : 'home';


	require_once('./header-includes.inc.php');

	if(isset($_GET['page'])!=''){
		require_once('./pages/'.$_GET['page'].'.inc.php');	
	}
	else{
		require_once('./pages/home.inc.php');	
		$_GET['page'] = 'home';
	}

	require_once('./footer.inc.php');
	require_once('./footer-includes.inc.php');
?>