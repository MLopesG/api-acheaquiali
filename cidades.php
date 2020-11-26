<?php 

	include_once './config/cors.php';
	include_once './config/database.php';
	include_once './classes/cidades.php';

	$database = new Database();
	$connection = $database->getConnection();

	$classCidade = new Cidade($connection);
	
	$action = isset($_GET['action']) ? $_GET['action'] : NULL;

	$result = [
		'success' => true,
		'message' => ''
	];

	switch ($action) {
		case 'all':
			$result['cidade'] = $classCidade->getCidade();
			break;
		default:
			 $result['success'] = false;
			 $result['message'] = 'Ação não foi encontrado!';
			break;
	}	

	echo json_encode($result);
 ?>