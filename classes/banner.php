<?php
	class Banner 
	{
		private $conection;

		function __construct($db)
		{
			$this->conection = $db;
		}

		public function getBanners(){

			$stmt = $this->conection->prepare('
				select concat("http://acheiaquiali.com.br/sistema/arquivos/banners/", imagem_banner) as url 
				from tab_banners b 
				inner join tab_clientes c on b.id_cliente = c.Id
				where b.ativo = "on"
			');
			$stmt->execute();

			$resultBanners = [];

			while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				$resultBanners[] = $row;
			}			

			return $resultBanners;
		}
	}
?>