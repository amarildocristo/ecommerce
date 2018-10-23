<?php 

namespace Hcode;

use Rain\Tpl;

class Page {

	private $tpl;
	private $options = [];
	private $defaults = [
		//padronizando header e o footer nas paginas
		"header"=>true,
		"footer"=>true,
		"data"=>[]//dados/variaveis que serão passadas pelo template
	];

	//Aqui quando receber as opções será setado como false 
	public function __construct($opts = array(), $tpl_dir = "/views/")
	{
		//Aqui fará o marge e prevalecer o que esta sendo mandado, atribuindo o false no defaults acima
		$this->options = array_merge($this->defaults, $opts);

		$config = array(
			//procurar as pastas a partir da pasta raiz no servidor com $_SERVER["DOCUMENT_ROOT"]  
			"tpl_dir"       => $_SERVER["DOCUMENT_ROOT"].$tpl_dir, //pega os arquivos html
			"cache_dir"     => $_SERVER["DOCUMENT_ROOT"]."/views-cache/",
			"debug"         => false 
		);

	    Tpl::configure( $config );

	    $this->tpl = new Tpl;
	    
	    $this->setData($this->options["data"]);

	    //validando e carregando caso seja chamado o header, já que o padrão receberá false 
	    if($this->options["header"] === true) $this->tpl->draw("header");
	}

	//pega os dados que serão enviados 
	private function setData($data = array())
	{
		foreach ($data as $key => $value) {
	    	$this->tpl->assign($key, $value);
	    }
	}

	//metodo para conteudo da pagina ou o corpo
	public function setTpl($name, $data = array(), $returnHTML = false)
	{

		$this->setData($data);

		//template
		return $this->tpl->draw($name, $returnHTML);

	}

	public function __destruct()
	{
		 //validando e carregando caso seja chamado o footer, já que o padrão receberá false 
		if($this->options["footer"] === true) $this->tpl->draw("footer");	
	}

}

 ?>