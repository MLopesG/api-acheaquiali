<?php 

	include_once './config/cors.php';
	include_once './config/database.php';
	include_once './classes/banner.php';

	$database = new Database();
	$connection = $database->getConnection();

	$classBanners = new Banner($connection);
	
	$action = isset($_GET['action']) ? $_GET['action'] : NULL;

	$result = [
		'success' => true,
		'message' => ''
	];

	switch ($action) {
		case 'all':
			$result['banners'] = $classBanners->getBanners();
			break;
		default:
			 $result['success'] = false;
			 $result['message'] = 'Ação não foi encontrado!';
			break;
	}	

	echo json_encode($result);
 ?>