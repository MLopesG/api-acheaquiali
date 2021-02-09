<?php
	class Banner 
	{
		private $conection;

		function __construct($db)
		{
			$this->conection = $db;
		}

		public function getBanners($tipo = 1){

			$stmt = $this->conection->prepare("
				select concat('http://acheiaquiali.com.br/sistema/arquivos/banners/', imagem_banner) as url ,
				b.id_cliente as id_cliente_banner
				from tab_banners b 
				inner join tab_clientes c on b.id_cliente = c.Id
				where b.ativo = 'on'
				and local = {$tipo}
			");
			$stmt->execute();

			$resultBanners = [];

			while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				$resultBanners[] = $row;
			}			

			return $resultBanners;
		}
	}
?>