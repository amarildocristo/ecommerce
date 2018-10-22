<?php 

	require_once("vendor/autoload.php");

	use \Slim\Slim;
	use \Hcode\Page;
	use \Hcode\PageAdmin;

	$app = new Slim();

	$app->config('debug', true);

	//define a rota que esta sendo chamado
	$app->get('/', function() {
	    
		$page = new Page();//carrega a pagina

		$page->setTpl("index");//mostra o conteudo

	});

	//se trocar o nome do diretorio pode melhorar a segurança
	$app->get('/administrative', function() {
	    
		$page = new PageAdmin();//carrega a pagina

		$page->setTpl("index");//mostra o conteudo

	});

	$app->run();//motor para tudo funcionar

?>