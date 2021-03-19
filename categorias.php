<?php 
	include_once './config/cors.php';
	include_once './config/database.php';
	include_once './classes/categorias.php';

	$database = new Database();
	$connection = $database->getConnection();

	$classCategorias = new Categoria($connection);
	
	$action = isset($_GET['action']) ? $_GET['action'] : NULL;

	$result = [
		'success' => true,
		'message' => ''
	];

	switch ($action) {
		case 'all':
			$result['categorias'] = $classCategorias->getCategorias();
			break;
		case 'empresas':
			$categoria = isset($_GET['id']) ? $_GET['id'] : NULL;
			$cidade = isset($_GET['cidade']) ? $_GET['cidade'] : NULL;
			$result['empresas'] = $classCategorias->getEmpresasCategorias($categoria, $cidade);
			break;
		case 'empresas-search':
			$categoria = isset($_GET['id']) ? $_GET['id'] : NULL;
			$search = isset($_GET['search']) ? $_GET['search'] : NULL;
			$cidade = isset($_GET['cidade']) ? $_GET['cidade'] : NULL;
			$result['empresas'] = $classCategorias->getEmpresasCategoriasSearch($categoria, $search, $cidade);
			break;
		case 'click':
			$action = isset($_GET['id']) ? $_GET['id'] : NULL;
			$clickVerify = $classCategorias->clickCategoria($action);

			if($clickVerify){
				$result['message'] = 'Click registrado!';
			}else{
				$result['success'] = false;
			 	$result['message'] = 'Não foi possivel registrar click';
			}
			break;
		case 'search':
			$search = isset($_GET['search']) ? $_GET['search'] : NULL;
			$result['data'] = $classCategorias->searchCategorias($search);
			break;
		default:
			 $result['success'] = false;
			 $result['message'] = 'Ação não foi encontrado!';
			break;
	}

	echo json_encode($result);
 ?>