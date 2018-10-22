<?php 

namespace Hcode;

use Rain\Tpl;

class Page {

	private $tpl;
	private $options = [];
	private $defaults = [
		"data"=>[]//dados/variaveis que serão passadas pelo template
	];

	public function __construct($opts = array())
	{
		//
		$this->options = array_merge($this->defaults, $opts);

		$config = array(
			//procurar as pastas a partir da pasta raiz no servidor com $_SERVER["DOCUMENT_ROOT"]  
			"tpl_dir"       => $_SERVER["DOCUMENT_ROOT"]."/views/", //pega os arquivos html
			"cache_dir"     => $_SERVER["DOCUMENT_ROOT"]."/views-cache/",
			"debug"         => false 
		);

	    Tpl::configure( $config );

	    $this->tpl = new Tpl;
	    
	    $this->setData($this->options["data"]);

	    $this->tpl->draw("header");

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

		$this->tpl->draw("footer");	

	}


}

 ?>