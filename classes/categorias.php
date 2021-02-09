<?php

	class Categoria 
	{
		private $conection;

		function __construct($db)
		{
			$this->conection = $db;
		}

		public function getCategorias(){
			$stmt = $this->conection->prepare('select Id, descricao from tab_categorias where ativo = "on" order by descricao');
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
				$queryCategorias  = $this->conection->prepare('select * from tab_categorias where Id in (:categorias) and ativo = "on"  order by descricao');
				$queryCategorias->bindParam(":categorias", $categoriaID);
				$queryCategorias->execute();

				while($rowCategorias = $queryCategorias->fetch(PDO::FETCH_ASSOC)){
					$resultCategorias[] = $rowCategorias;
				}	
			}
			// -----------------------------------------------------------

			$stmtCategoriasSingle = $this->conection->prepare("select * from tab_categorias where descricao like CONCAT('%', :search, '%') and ativo = 'on'  order by descricao");
			$stmtCategoriasSingle->bindParam(":search", $search);
			$stmtCategoriasSingle->execute();
	
			while($rowCategoriasSingle = $stmtCategoriasSingle->fetch(PDO::FETCH_ASSOC)){
				$resultCategorias[] = $rowCategoriasSingle;
			}	

			return array_unique($resultCategorias, SORT_REGULAR);
		}

		public function getEmpresasCategorias($categoria){
			$resultEmpresas = [];

			$query  = $this->conection->prepare("
				select 
				c.*,
				cs.nome,
				concat('http://acheiaquiali.com.br/sistema/arquivos/clientes/', c.diretorio ,'/', imagem) as logo
				from tab_clientes c 
				left join tab_clientes_logotipo l on c.Id = l.id_cliente
				left join  tab_cidades cs on cs.Id = c.end_cidade
				inner join tab_clientes_categorias tc on tc.id_cliente = c.Id 
				where tc.id_categoria =  :categoria and c.ativo = 'on' 
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

			$sql = "select 
				c.*,
				concat('http://acheiaquiali.com.br/sistema/arquivos/clientes/', c.diretorio ,'/', imagem) as logo
				from tab_clientes c 
				left join tab_clientes_logotipo l on c.Id = l.id_cliente
				inner join tab_clientes_categorias tc on tc.id_cliente = c.Id 
				where 
				c.ativo = 'on'
				and tc.id_categoria = :categoria";


			$query  = $this->conection->prepare($sql);

			$query->bindParam(":categoria", $categoria);

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