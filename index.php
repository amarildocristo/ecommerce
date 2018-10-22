<?php 

	require_once("vendor/autoload.php");

	use \Slim\Slim;
	use \Hcode\Page;

	$app = new Slim();

	$app->config('debug', true);

	//define a rota que esta sendo chamado
	$app->get('/', function() {
	    
		$page = new Page();//carrega a pagina

		$page->setTpl("index");//mostra o conteudo

	});

	$app->run();//motor para tudo funcionar

?>