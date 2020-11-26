<?php
	class Cidade 
	{
		private $conection;

		function __construct($db)
		{
			$this->conection = $db;
		}

		public function getCidade($cidade = 1518){

			$stmt = $this->conection->prepare("
				SELECT 
				concat('http://acheiaquiali.com.br/sistema/arquivos/cidades/', imagem) as url,
				c.*
				FROM tab_cidades c
                left join tab_cidades_fotos on  id_cidade = c.Id
				WHERE c.Id = {$cidade}

			");
			$stmt->execute();

			$cidade = null;

			while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				$cidade =  $row;
			}			

			return $cidade;
		}
	}
?>