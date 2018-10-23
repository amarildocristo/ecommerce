<?php 
	session_start();

	require_once("vendor/autoload.php");

	use \Slim\Slim;
	use \Hcode\Page;
	use \Hcode\PageAdmin;
	use \Hcode\Model\User;

	$app = new Slim();

	$app->config('debug', true);

	//Rota para o index/raiz do site
	$app->get('/', function() {
	    
		$page = new Page();//carrega a pagina

		$page->setTpl("index");//mostra o conteudo

	});

	//se trocar o nome do diretorio pode melhorar a segurança
	//Rota para a pagina administrativa
	$app->get('/admin', function() {

		User::verifyLogin();
	    
		$page = new PageAdmin();//carrega a pagina

		$page->setTpl("index");//mostra o conteudo

	});

	//Rota para a tela de Login
	$app->get('/admin/login', function() {
	    //tela de login não tem header nem footer
		$page = new PageAdmin([
			"header"=>false,
			"footer"=>false
		]);

		$page->setTpl("login");

	});

	$app->post('/admin/login', function(){

		User::login($_POST["login"], $_POST["password"]);
		
		header("Location: /admin");	
		exit;

	});

	$app->get('/admin/logout', function(){

		User::logout();

		header("Location: /admin/login");
		exit;

	});
	

	$app->run();//motor para tudo funcionar

?>