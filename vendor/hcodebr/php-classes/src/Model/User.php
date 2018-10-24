<?php 

	namespace Hcode\Model;
	use \Hcode\DB\Sql;
	use \Hcode\Model;

	class User extends Model {

		const SESSION = "User";

		//
		public static function login($login, $password)
		{
			$sql = new Sql();  

			$results = $sql->select("SELECT * FROM tb_users WHERE deslogin = :LOGIN", array(
				":LOGIN"=>$login
			));

			//verificar se retornou infomação
			if(count($results) === 0)
			{
				throw new \Exception("Usuário inexistente ou senha inválida", 1);	
			}

			//primeiro registro encontrado na posicao "0"
			$data = $results[0];

			//verificar a senha do usuario/ se o hush(senha criptografada) bateu com a senha ou não
			if(password_verify($password, $data["despassword"]) === true)
			{
				$user = new User();//classe User é um model

				//metodo setData() definido no Model
				$user->setData($data);//seta todos os campos
				//metodo getValues() definido no Model
				//Pra que um login funcione corretamente tem que criar uma sessão, os dados precisam estar numa sessão.
				$_SESSION[User::SESSION] = $user->getValues();

				return $user;

			}else{
				throw new \Exception("Usuário inexistente ou senha inválida", 1);	
			}
		}

		//Verifica se o usuario está logado na administração
		public static function verifyLogin($inadmin = true)
		{
			//Se não for definida a sessão
			if(!isset($_SESSION[User::SESSION]) 
			   || 
			   !$_SESSION[User::SESSION]//Se for falsa
			   ||
			   !(int)$_SESSION[User::SESSION]["iduser"] > 0 //
			   ||
			   (bool)$_SESSION[User::SESSION]["inadmin"] !== $inadmin
			){

				header("Location: /admin/login");
				exit();

			}
		}

		public static function logout()
		{
		
			$_SESSION[User::SESSION] = Null;
			
		}

		//Lista todos os dados do banco
		public static function listAll()
		{
			$sql = new Sql();
			
			return $sql->select("SELECT * FROM tb_users a INNER JOIN tb_persons b USING(idperson) ORDER BY b.desperson");
			 
		}

		//executa o insert dentro do banco
		public function save()
		{
			$sql = new Sql();

			$results = $sql->select("CALL sp_users_save(:desperson, :deslogin, :despassword, :desemail, :nrphone, :inadmin)", array(
				":desperson"=>$this->getdesperson(),
				":deslogin"=>$this->getdeslogin(),
				":despassword"=>$this->getdespassword(),
				":desemail"=>$this->getdesemail(),
				":nrphone"=>$this->getnrphone(),
				":inadmin"=>$this->getinadmin()
			));

			$this->setData($results[0]);	
		}

		public function get($iduser)
		{
			$sql = new Sql();

			$results = $sql->select("SELECT * FROM tb_users a INNER JOIN tb_persons b USING(idperson) WHERE a.iduser = :iduser", array(
				":iduser"=>$iduser
			));

			$this->setData($results[0]);
		}

		public function update()
		{
			$sql = new Sql();

			$results = $sql->select("CALL sp_usersupdate_save(:iduser, :desperson, :deslogin, :despassword, :desemail, :nrphone, :inadmin)", array(
				":iduser"=>$this->getiduser(),
				":desperson"=>$this->getdesperson(),
				":deslogin"=>$this->getdeslogin(),
				":despassword"=>$this->getdespassword(),
				":desemail"=>$this->getdesemail(),
				":nrphone"=>$this->getnrphone(),
				":inadmin"=>$this->getinadmin()
			));

			$this->setData($results[0]);	
		}

		public function delete()
		{
			$sql = new Sql();

			$sql->query("CALL sp_users_delete(:iduser)", array(
				":iduser"=>$this->getiduser()
			));
		}
	}

 ?>