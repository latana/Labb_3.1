<?php

namespace view;

	class View{
		
		/**
		 * @var int
		 */
		const USER_NOT_LOGGEDIN = 0;
		
		/**
		 * @var int
		 */
		const USER_ALREADY_LOGGEDIN = 1;
		
		/**
		 * @var int
		 */
		const SUCCESSFUL_LOGIN = 2;
		
		/**
		 * @var int
		 */
		const MISSING_USERNAME = 3;
		
		/**
		 * @var int
		 */
		const MISSING_PASSWORD = 4;
		
		/**
		 * @var int
		 */
		const BAD_USERNAME_PASSWORD = 5;
		
		/**
		 * @var int
		 */
		const USER_LOGGED_OUT = 6;
		
		/**
		 * @var int
		 */
		const AUTOSAVE_AND_LOGIN = 7;
		
		/**
		 * @var int
		 */
		const SUCCESSFUL_COOKIE = 8;
		
		/**
		 * @var int
		 */
		const FAILED_COOKIE = 9;
		
		/**
		 * @var int
		 */
		const SESSION_THIEF = 10;
		
		/**
		 * @var string
		 */
		public $userName;
		
		/**
		 * @var string
		 */
		
		public $passName;
		
		/**
		 * @var string
		 */
		public static $userID ="userID";
		
		/**
		 * @var string
		 */
		public static $passID ="passID";
		
		/**
		 * @var string
		 */
		public static $loginButton = "loginButton";
		
		/**
		 * @var string
		 */
		public static $logoutButton = "logoutButton";
		
		/**
		 * @var string
		 */
		public static $autologinID = "autoLogin";
		
		/**
		 * @var string
		 */
		private static $cookieUser = "cookieUser";
		
		/**
		 * @var string
		 */
		private static $cookiePass = "cookiePass";
		
		/**
		 * @var string
		 */
		public $safeKey = "safeKey";
		
		/**
		 * @var string
		 */
		public $message;
		
		/**
		 * @var string
		 */
		public $outMessage;
		
		/**
		 * @var string
		 */
		public $loginMessage;
		
		/**
		 * @var string
		 */
		public $keepme;


		public function __construct() {
			
			$this->title = 'Laboration 3. Inte inloggad.';
			$this->body = $this->firstPage();
		}
		
		/**
		 * @return string, retunerar innehållet av vad
		 * användaren matat som användarnamn.
		 */

		
		public static function GetUserName(){
		
			if(isset($_POST[self::$userID])){

				return $_POST[self::$userID];
			}
		}
		
		/**
		 * @return string, Iretunerar innehållet av
		 * vad användaren matat in som lösenord.
		 */
		
		public static function GetPassWord(){
		
			if(isset($_POST[self::$passID])){

				return $_POST[self::$passID];
			}
		}
		
		/**
		 * @return bool, retunerar true om man trycker på login knappen
		 */
		
		public static function TryLogin(){
		
			if(isset($_POST[self::$loginButton])){
			
				return true;
			}
			return false;
		}
		
		/**
		 * return bool, retunerar true om man trycker
		 * på logga ut knappen och tar bort kakorna.
		 */
		
		public static function TryToLogout(){
		
			if(isset($_POST[self::$logoutButton])){
				
				self::DestroyCookie();
				
				return true;
			}
			return false;
		}
		
		/**
		 * @param messageType integer
		 * sätter meddelande beroende på vad för variabel som tas emot,
		 * annars så sätts den till null.
		 */
		
		public function setMessage($messageType = 0) {
			switch($messageType) {
				case self::MISSING_PASSWORD:
					$this->message = "<p>Lösenord saknas</p>";
					break;
				case self::MISSING_USERNAME:
					$this->message = "<p>Användarnamn saknas</p>";
					break;
				case self::BAD_USERNAME_PASSWORD:
					$this->message = "<p>Användarnamn eller Lösenord är felaktig</p>";
					break;
				case self::SUCCESSFUL_LOGIN:
					$this->message = "<p>Inloggning lyckades</p>";
					break;
				case self::AUTOSAVE_AND_LOGIN:
					$this->message ="<p>Du har loggats in och dina uppgifter är sparade</p>";
					break;
				case self::USER_LOGGED_OUT:
					$this->message = "<p>Du har loggats ut</p>";
					break;
				case self::SUCCESSFUL_COOKIE:
					$this->message = "<p>Du blev inloggad med cookie</p>";
					break;
				case self::FAILED_COOKIE:
					$this->message = "<p>Felaktiga värden i cookie</p>";
					break;
				case self::SESSION_THIEF:
				$this->message ="<p>Det är inte tillåtet att stjäla sessionen!</p>";
					break;
				case self::USER_ALREADY_LOGGEDIN:
				case self::USER_NOT_LOGGEDIN:
				default:
					$this->message = (string) NULL;
					break;
			}
		}
		
		/**
		 * Visar första sidan
		 */
		
		public function ShowFirstPage(){
			
			$this->title = 'Laboration 3. Inte inloggad.';
			$this->body = $this->firstPage();
		}
		
		/**
		 * Sidan som ska visas när man är inloggad
		 */
		
		public function ShowLoginPage(){
			
			$this->title = 'Laboration 3. Inloggad.';
			$this->body = $this->loginPage();
		}
		
		/**
		 * @return bool, retunerar true om "Håll mig inloggad" boxen är markerad
		 */
		
		public function AutoLogin(){
				
			if(isset($_POST[self::$autologinID])){
				return true;
			}
			return false;
		}		
		
		/**
		 * @param userName string
		 * @param batCave string
		 * skapar kakorna, sätter tid, namn och value
		 */
		
		public function MakeCookie($userName, $batCave){
			
			$cookieTime = time() + 30;
			
			file_put_contents("cookieTime.txt", "$cookieTime");
			
	 		setcookie(self::$cookieUser, $userName, $cookieTime);
			$cryptPass = md5($batCave, $this->safeKey);
		
			$value = setcookie(self::$cookiePass, $cryptPass, $cookieTime);
	 	}
		
		/**
		 * @return bool, retunerar true om kakorna är satta
		 */
		
		public function CookieExist(){
			
			if(isset($_COOKIE[self::$cookieUser]) && (isset($_COOKIE[self::$cookiePass]))){
					
				return true;
			}
			return false;
		}
		
		/**
		 * Tar bort kakorna
		 */
		
		public function DestroyCookie(){
			
			setcookie(self::$cookieUser, "", time()-9999999);
			
			setcookie(self::$cookiePass, "", time()-9999999);
		}
		
		/**
		 * @param userName string
		 * @param batCave string
		 * @return bool, kollar av kakornas tid och 
		 * value och retunerar true om det stämmer
		 */
		
		public function CheckIfValidCookie($userName, $batCave){
			
			$timeFile = file_get_contents("cookieTime.txt");
			
			if($timeFile > time()){

				if($_COOKIE[self::$cookieUser] == $userName && 
					$_COOKIE[self::$cookiePass] == md5($batCave, $this->safeKey)){
		
					return true;
				}	
			}
			return false;
		}
		
		/**
		 * @return string, matar ut webbplattsens första sidan
		 */
			
		public function firstPage(){
				
			return "<form action='?login' method='post' enctype='multipart/form-data'>
					<h2>Ej Inloggad</h2>
					<fieldset>
						<legend>Login - Skriv in användarnamn och lösenord</legend>
						<label for='userID' >Användarnamn :</label>
						<input type='text' size='20' name='".self::$userID."' 
						id='".self::$userID."' value= '". self::getUserName() ."'/>
						<label for='passID' >Lösenord  :</label>
						<input type='password' size='20' name='".self::$passID."'
						id='".self::$passID."' value='' />
						<label for='autologinID' >Håll mig inloggad  :</label>
						<input type='checkbox' name='".self::$autologinID."' id='".self::$autologinID."' />
						<input type='submit' name='".self::$loginButton."'  value='Logga in' />
					</fieldset>
				</form>";
		}
		
		/**
		 * @return bool, matar ut sidan som ska visas när man är inloggad
		 */
		
		public function loginPage(){
				
			return "<h2>Admin är inloggad</h2>
				<form action='?logout' method='post' enctype='multipart/form-data'>
				<input type='submit' name='".self::$logoutButton."'  value='Logga ut' />
				</form>";
		}
		
		/**
		 * @return bool, matar ut grundstrukturen som alltid ska visas
		 */
		
		public function showPage() {
			echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0
				Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'> 
		        <html xmlns='http://www.w3.org/1999/xhtml'> 
				<head> 
				<title>$this->title</title>
			    <meta http-equiv='content-type' content='text/html; charset=utf-8' />   
			    </head> 
			    <body>
			    $this->message 
			    <h1>Laboration 3 ms223eq</h1>
			    $this->body
				<p>" . strftime('%A, den %d %B år %Y. Klockan är: [%H:%M:%S] ') . " </p>
			    </body>
			    </html>";
		}
	}