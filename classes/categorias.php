<?php

	class Categoria 
	{
		private $conection;

		function __construct($db)
		{
			$this->conection = $db;
		}

		public function getCategorias(){
			$stmt = $this->conection->prepare('select Id, descricao from tab_categorias order by descricao');
			$stmt->execute();

			$resultCategorias = [];

			while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				$resultCategorias[] = $row;
			}			

			return $resultCategorias;
		}

		public function searchCategorias($search = null)
		{

			// Buscar categorias pelo cliente
			$stmtClientes = $this->conection->prepare("select * from tab_clientes where 
				tags like CONCAT('%', :search, '%' or 
				descricaodaempresa like CONCAT('%', :search, '%')");
			$stmtClientes->bindParam(":search", $search);
			$stmtClientes->execute();
	
			$resultCategorias = [];

			while($row = $stmtClientes->fetch(PDO::FETCH_ASSOC)){

				$categoriaID  =  $this->converteArray(explode('-', $row['id_categorias']));
				$queryCategorias  = $this->conection->prepare('select * from tab_categorias where Id in (:categorias)');
				$queryCategorias->bindParam(":categorias", $categoriaID);
				$queryCategorias->execute();

				while($rowCategorias = $queryCategorias->fetch(PDO::FETCH_ASSOC)){
					$resultCategorias[] = $rowCategorias;
				}	
			}
			// -----------------------------------------------------------

			$stmtCategoriasSingle = $this->conection->prepare("select * from tab_categorias where descricao like CONCAT('%', :search, '%')");
			$stmtCategoriasSingle->bindParam(":search", $search);
			$stmtCategoriasSingle->execute();
	
			while($rowCategoriasSingle = $stmtCategoriasSingle->fetch(PDO::FETCH_ASSOC)){
				$resultCategorias[] = $rowCategoriasSingle;
			}	

			return $resultCategorias;
		}

		public function getEmpresasCategorias($categoria){
			$resultEmpresas = [];

			$query  = $this->conection->prepare("
				select 
				c.Id,
				c.apelido,
				c.fonecelular,
				c.site,
				c.end_rua,
				c.end_numero,
				concat('http://acheiaquiali.com.br/sistema/arquivos/clientes/', c.diretorio ,'/', imagem) as logo
				from tab_clientes c inner join tab_clientes_logotipo l on c.Id = l.id_cliente
				where id_categorias like CONCAT('%', :categoria, '%')
			");
			$query->bindParam(":categoria", $categoria);
			$query->execute();

			while($row = $query->fetch(PDO::FETCH_ASSOC)){
				$resultEmpresas[] = $row;
			}	

			return $resultEmpresas;
		}	

		public function getEmpresasCategoriasSearch($categoria, $search){
			$resultEmpresas = [];

			$query  = $this->conection->prepare("
				select 
				c.Id,
				c.apelido,
				c.fonecelular,
				c.site,
				c.end_rua,
				c.end_numero,
				concat('http://acheiaquiali.com.br/sistema/arquivos/clientes/', c.diretorio ,'/', imagem) as logo
				from tab_clientes c inner join tab_clientes_logotipo l on c.Id = l.id_cliente
				where 
				id_categorias like CONCAT('%', :categoria, '%')
				or razaosocial like CONCAT('%', :search, '%')
				or nomefantasia like CONCAT('%', :search, '%')
				or tags like CONCAT('%', :search, '%')
				or descricaodaempresa like CONCAT('%', :search, '%')
			");
			
			$query->bindParam(":categoria", $categoria);
			$query->bindParam(":search", $search);
			$query->execute();

			while($row = $query->fetch(PDO::FETCH_ASSOC)){
				$resultEmpresas[] = $row;
			}	

			return $resultEmpresas;
		}	

		public function clickCategoria($id = null){
			$sql = "INSERT INTO tab_cliques ( dhcad, quemcriou, id_categoria) VALUES (now(), 'Aplicativo Mobile', :id)";
			$stmt = $this->conection->prepare($sql);
			$stmt->bindParam(":id", $id);

			if($stmt->execute()){
				return true;
			}
			return false;
		}

		private function converteArray($array){
			return implode(',', ($array));
		}
	}
 ?>