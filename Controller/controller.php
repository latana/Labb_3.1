<?php

namespace controller;

require_once 'View/view.php';
require_once 'Model/model.php';

	class Controller{
		
		/**
		 * innehåller klassen Model
		 */
		
		private $classModel;
		
		/**
		 * innehåller klassen View
		 */
		private $classView;
		
		public function __construct() {
			
			$this -> classModel = new \model\Model();
			$this -> classView = new \view\View();
			$this-> startEngine();
		}
		
		/**
		 * Kontrollerar sessions, cookie och
		 * vad användaren har gjort tidigare
		 */
		private function startEngine(){
			
			if($this->classModel->CheckIfSessionIsValid()){
					
				$this->classView->setMessage(\view\View::SESSION_THIEF);
			}
			$this->CookieController();
			
			if(!$this->classModel->AskLogin() && !$this->classView->TryLogin()){
				
			} else if($this->classView->TryToLogout()) {
				$this->classView->setMessage(\view\View::USER_LOGGED_OUT);
				$this->classModel->LogoutModel();
			}
			else{
				if ($this->classModel->AskLogin()) {					
					$this->classView->ShowLoginPage();
				}
				else{
					$this->UserAndPassController();
				}
			}
			$this->classView->showPage();
		}
		
		/**
		 * Kontrollerar vad användaren har matat in
		 * och skickar meddelande beroende på situation
		 */
		private function UserAndPassController(){
			
			$userName = trim($this->classView->GetUserName());
			$passWord = trim($this->classView->GetPassWord());
			$batCave = $this->classModel->GetBatCave();
			
			if (empty($userName)) {
						
				$this->classView->setMessage(\view\View::MISSING_USERNAME);	
			}
			else if(empty($passWord)) {
						
				$this->classView->setMessage(\view\View::MISSING_PASSWORD);
			}
			else if ($this->classModel->LoginModel($userName, $passWord)) {
							
				if($this->classView->AutoLogin()){
					$this->classView->MakeCookie($userName, $batCave);
					$this->classView->setMessage(\view\View::AUTOSAVE_AND_LOGIN);
				}
				else{
					$this->classView->setMessage(\view\View::SUCCESSFUL_LOGIN);
				}
				$this->classView->ShowLoginPage();
			}
			else {
				$this->classView->setMessage(\view\View::BAD_USERNAME_PASSWORD);
			}
		}
		
		/**
		 * Kontrollerar ifall kakorna är satta
		 * för att sedan kontrollera om deras värden stämmer
		 */
		
		private function CookieController(){
			
			$userName = trim($this->classModel->GetCorrectUserName());
			$batCave = $this->classModel->GetBatCave();

			if(!$this->classModel->AskLogin() && $this->classView->CookieExist()){
				
				if($this->classView->CheckIfValidCookie($userName, $batCave)){

						$this->classView->setMessage(\view\View::SUCCESSFUL_COOKIE);
						$this->classModel->LoginSuccess();	
				}
				else{
					$this->classView->setMessage(\view\View::FAILED_COOKIE);
					$this->classView->DestroyCookie();
				}
			}
		}
	}