<?php

namespace model;

	 class Model{
	 	
		/**
		 * @var string
		 */
		private $correctUserName = "Admin";
		
		/**
		 * @var string
		 */
		private $correctPassWord = "Password";
		
		/**
		 * @var string
		 */
		
		public $batCave = "2avunw3fuhegunwsgåojwq938th98h2wgvå823v89";
		
		/**
		 * @var string
		 */
		
		private static $loginSess = "login";
		
		/**
		 * @var string
		 */
		
		private static $sessionController = "Session controller";
		
		/**
		 * @var string
		 */
		
		private static $ip = "ip";
		
		/**
		 * @var string
		 */
		
		private static $browser = "browser";
		
		/**
		 * @var string
		 */
		
		private static $agent = "HTTP_USER_AGENT";
		
		/**
		 * @var string
		 */
		
		private static $remote = "REMOTE_ADDR";

		/**
		 * @param userName string
		 * @param passWord string
		 * @return bool, kontrollerar ifall det användaren
		 * matat in stämmer och retunerar i så fall true
		 */
	 	public function LoginModel($userName, $passWord){

			if($userName == $this->correctUserName && $passWord == $this->correctPassWord){
					
				$this->LoginSuccess();
				return true;
			}
			return false;
			
	 	}
		
		/**
		 * @return string, skickar användarnamnet 
		 */
		
		public function GetCorrectUserName(){
			
			return $this->correctUserName;
		}
		
		/**
		 * @return string, Skickar det inte allt 
		 * för uppenbara lösenordet för cookie
		 */
		
		public function GetBatCave(){
			
			return $this->batCave;
		}
		
		/**
		 * Startar sessionen för inloggad
		 */
		
		public function LoginSuccess(){
			
			$_SESSION[self::$loginSess] = true;
		}
		
		/**
		 * Stoppar sessionen för inloggad
		 */
		
		public function LogoutModel(){

				unset($_SESSION[self::$loginSess]);
		}
		
		/**
		 * @return bool, Kontrollerar ifall sessionen
		 * är satt och retunerar i så fall true
		 */
		
		public function AskLogin(){
			
			if(isset($_SESSION[self::$loginSess])){
				
				return true;
			}
			return false;
		}	
		
		/**
		 * @return bool, Kontrollerar ifall sessionen stämmer,
		 * Om inte så tas sessionen bort och true retuneras 
		 */
		
		public function CheckIfSessionIsValid(){
			
			if(isset($_SESSION[self::$sessionController]) == false){
				$_SESSION[self::$sessionController] = array();
				$_SESSION[self::$sessionController][self::$browser] = $_SERVER[self::$agent];
				$_SESSION[self::$sessionController][self::$ip] = $_SERVER[self::$remote];
			}
			if($_SESSION[self::$sessionController][self::$browser] != $_SERVER[self::$agent]){
						
				unset($_SESSION[self::$loginSess]);
				return true;
			}
			return false;
		}
	}


	