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
		case 'estado_cidades':
			$estado = isset($_GET['estado_id']) ? $_GET['estado_id'] : NULL;
			$result['cidades'] = $classCidade->getCidadeEstados($estado);
			break;
		case 'single':
			$cidade = isset($_GET['cidade_id']) ? $_GET['cidade_id'] : NULL;
			$result['cidade'] = $classCidade->getCidade($cidade);
			break;
		case 'all':
			$result['cidades'] = $classCidade->getCidades();
			break;
		default:
			 $result['success'] = false;
			 $result['message'] = 'Ação não foi encontrado!';
			break;
	}	

	echo json_encode($result);
 ?>