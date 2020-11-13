<?php

	class Categoria
	{
		private $conection;

		function __construct($db)
		{
			$this->conection = $db;
		}

		public function getCategorias(){
			$stmt = $this->conection->prepare('select * from tab_categorias');
			$stmt->execute();

			$resultCategorias = [];

			while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				$resultCategorias[] = $row;
			}			

			return $resultCategorias;
		}

		public function searchCategorias($search = null)
		{

			$stmtCategoria = $this->conection->prepare("select * from tab_categorias where descricao like CONCAT('%', :search, '%')");
			$stmtCategoria->bindParam(":search", $search);
			$stmtCategoria->execute();
	
			$resultCategorias = [];

			while($row = $stmtCategoria->fetch(PDO::FETCH_ASSOC)){
				$resultCategorias[] = $row;
			}			

			return $resultCategorias;
		}

		public function clickCategoria($id = null){
		
			$query = 'update tab_categorias set cliquesapp = cliquesapp + 1 where Id = :id';
			$stmt = $this->conection->prepare($query);
			$stmt->bindParam(":id", $id);

			if($stmt->execute()){
				return true;
			}
			return false;
		}
	}
 ?>