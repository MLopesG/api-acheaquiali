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

		public function searchCategorias($search = null, $cidade = null)
		{
			// Buscar categorias pelo cliente
			$stmtClientes = $this->conection->prepare("
				SELECT ca.Id, ca.descricao as descricao, 'categoria'  as tipo FROM tab_clientes tbc
				inner join tab_clientes_categorias tbca on tbc.Id = tbca.id_cliente
				inner join tab_categorias ca on ca.Id = tbca.id_categoria
				inner join  tab_cidades cs on cs.Id = tbc.end_cidade
				where tbc.ativo = 'on' 
				and cs.Id = {$cidade}
				and LOWER(tbc.tags) like  CONCAT('%', LOWER(:search), '%') 
				and LOWER(tbc.descricaodaempresa) like  CONCAT('%', LOWER(:search), '%')
				union
				SELECT tbc.Id,  (
			       case
				        when (tbc.apelido is not null)  and tbc.apelido <> '' then tbc.apelido
				        when (tbc.nomefantasia is not null) and tbc.nomefantasia <> ''  then tbc.nomefantasia
				        when (tbc.razaosocial is not null)  and tbc.razaosocial <> ''  then tbc.razaosocial
			        	else tbc.nomecompleto
			        end
			    ) as descricao, 'empresa'  as tipo   FROM tab_clientes tbc
				inner join tab_clientes_categorias tbca on tbc.Id = tbca.id_cliente
				inner join tab_categorias ca on ca.Id = tbca.id_categoria
				inner join  tab_cidades cs on cs.Id = tbc.end_cidade
				where tbc.ativo = 'on' 
				and cs.Id = {$cidade}
				and LOWER(tbc.tags) like  CONCAT('%', LOWER(:search), '%') 
				and LOWER(tbc.descricaodaempresa) like  CONCAT('%', LOWER(:search), '%')
			");

			$stmtClientes->bindParam(":search", $search);
			$stmtClientes->execute();

			return $stmtClientes->fetchAll(PDO::FETCH_ASSOC);
		}

		public function getEmpresasCategorias($categoria, $cidade){
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
				and cs.Id = {$cidade}
			");
			$query->bindParam(":categoria", $categoria);
			$query->execute();

			while($row = $query->fetch(PDO::FETCH_ASSOC)){
				$resultEmpresas[] = $row;
			}	

			return $resultEmpresas;
		}	

		public function getEmpresasCategoriasSearch($categoria, $search, $cidade){

			$resultEmpresas = [];

			$sql = "select 
				c.*,
				concat('http://acheiaquiali.com.br/sistema/arquivos/clientes/', c.diretorio ,'/', imagem) as logo
				from tab_clientes c 
				left join tab_clientes_logotipo l on c.Id = l.id_cliente
				inner join tab_clientes_categorias tc on tc.id_cliente = c.Id 
				where 
				c.ativo = 'on'
				and tc.id_categoria = :categoria
				and  c.end_cidade = {$cidade}";


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
	}
 ?>
