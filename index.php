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

		User::verifyLogin();//verifica se o usuario está logado no administrativo
	    
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

	//rota ou caminho que recebe os dados do formulario
	$app->post('/admin/login', function(){
		//recebe o post dos campos do formulario
		User::login($_POST["login"], $_POST["password"]);
		
		header("Location: /admin");	
		exit();

	});//apois os dados acima criar a classe Use

	//rota ligado ao botão sign out/sair do 
	$app->get('/admin/logout', function(){

		User::logout();

		header("Location: /admin/login");
		exit();

	});

	//rota para listar todos os usuarios
	$app->get('/admin/users', function(){
	
		User::verifyLogin();//verifica se o usuario está logado no administrativo

		$users = User::listAll();

		$page = new PageAdmin();

		//parte usada na tabela para trazer os dados
		$page->setTpl("users", array(
			"users"=>$users
		));	

	});

	$app->get('/admin/users/create', function(){

		User::verifyLogin();//verifica se o usuario está logado no administrativo

		$page = new PageAdmin();

		$page->setTpl("users-create");	

	});

	//rota para apagar um usuario e suas informações
	$app->get('/admin/users/:iduser/delete', function($iduser){

		User::verifyLogin();//verifica se o usuario está logado no administrativo

		$user = new User();

		//carregar os dados do usuario pra saber se ainda esta no banco
		$user->get((int)$iduser);

		$user->delete();

		header("Location: /admin/users");
		exit();


	});

	//rota solicita um usuario especifico
	$app->get('/admin/users/:iduser', function($iduser){

		User::verifyLogin();//verifica se o usuario está logado no administrativo

		$user = new User();

		$user->get((int)$iduser);

		$page = new PageAdmin();

		$page->setTpl("users-update", array(
			"user"=>$user->getValues()
		));	

	});

	//rota para salvar os dados 
	$app->post('/admin/users/create', function(){

		User::verifyLogin();//verifica se o usuario está logado no administrativo

		$user = new User();

		$_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0;

		$user->setData($_POST);

		$user->save();

		header("Location: /admin/users");
		exit();

	});

	//rota para salvar a edição 
	$app->post('/admin/users/:iduser', function($iduser){

		User::verifyLogin();//verifica se o usuario está logado no administrativo

		$user = new User();

		$_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0;

		$user->get((int)$iduser);//carrega os dados e coloca no values
		$user->setData($_POST);

		$user->update();

		header("LOcation: /admin/users");
		exit();

	});

	$app->run();//motor para tudo funcionar

?>