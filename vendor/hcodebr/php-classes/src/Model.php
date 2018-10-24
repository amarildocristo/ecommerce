<?php 

	namespace Hcode;

	//
	class Model {

		//$values terá todos os dados/valores dos campos que tem dentro dos objetos.
		//No caso do objeto usuario seus dados como: id, login, senha.  
		private $values = [];

		//Metodo magico que alimentará os getters e setters
		public function __call($name, $args)
		{
			//tres primeiros numeros do metodo do nome que foi chamado
			$method = substr($name, 0, 3);
			//nome do campo que foi chamado
			$fieldName = substr($name, 3, strlen($name));

			switch($method)
			{
				case "get":
					return $this->values[$fieldName];
				break;

				case "set":
					$this->values[$fieldName] = $args[0];
				break;
			}
		}

		//recebe todos os dados que vem do banco
		public function setData($data = array())
		{
			foreach ($data as $key => $value) {

				$this->{"set".$key}($value);	

			}
		}

		public function getValues()
		{
			return $this->values;
		}
	}

 ?>