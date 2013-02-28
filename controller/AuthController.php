<?php

namespace Controller;

require_once ('view/AuthView.php');
require_once ('model/AuthModel.php');

class AuthController
{
	private $m_db;
	private $m_userInSession;
	private $m_output = "";
	private $m_authView;
	private $m_authModel;
	public function __construct(\Model\Database $db)
	{
		$this->m_db = $db;
		$this->m_userInSession = \Model\User::GetUserSession();
		$this->m_authView = new \View\AuthView;
		$this->m_authModel = new \Model\AuthModel($db);
	}

	/**
	 * Do Authentication controll.
	 * Check if the user tried to login/logout. sets the user session if logged in successful,
	 * Check if the user wants to be recognized and then store a cookie with the user and the crypted password.
	 * @return $this->m_outout, the html generated code
	 */
	public function doAuthControll()
	{
		// Are you logged in?
		if($this->m_authModel->IsLoggedIn())
		{
			$this->m_output = $this->m_authView->DoAuthBox($this->m_userInSession);
		}
		// Are you not logged in?
		else
		{
			$this->m_output = $this->m_authView->DoLogInForm();
		}

		// Did the user try to login
		if($this->m_authView->TriedToLogIn())
		{
			$username = $this->m_authView->GetUsername();
			$password = $this->m_authView->GetPassword();
			// Try to login a user,
				if($this->m_authModel->DoLogin($username, $password))
				{
					$user = $this->m_authModel->GetUserByName($username);
					// Does the user want to save login Data
					if($this->m_authView->TriedToRememberUser())
					{
						// Which username / password should be saved
						$this->m_authView->UserToRemember($user);
					}
					// If the user doesn't want to be recognized
					else
					{
						// Delete the login Cookie
						$this->m_authView->ForgetUser();
					}
					$this->m_output = $this->m_authView->DoAuthBox($user);
				}
				else {
					\Common\Page::AddErrorMessage(\Common\String::WRONG_PASSWORD_OR_USERNAME);
					$this->m_output = $this->m_authView->DoLogInForm();
				}
				
		}
		// Did the user try to logout
		else if($this->m_authView->TriedToLogOut())
		{
			//logout the user
			$this->m_authModel->DoLogOut();

			$this->m_output = $this->m_authView->DoLogInForm();
		}
		return $this->m_output;
	}
	
	/**
	 * Register user controll
	 * Check if the user tries to register and validates the user...
	 * If there's any field written with bad values is a errormessages showed to the user.
	 * If the register is completed the user get informed with a success message.
	 * @return $this->m_outout, the html generated code
	 */
	public function DoRegisterControll()
	{
		$this->m_output = $this->m_authView->DoRegisterForm();
		
		if($this->m_authView->TriedToRegister())
		{
			if($this->m_authView->ValidateRegisterNewUser())
			{
				// if there's already a user registed with the username
				if($this->m_authModel->IsRegistered($this->m_authView->GetUsername()) == false)
				{
					// userInfo for the user to be registed
					$userInfo = array(
						\Model\User::USERNAME => $this->m_authView->GetUsername(),
						\Model\User::EMAIL => $this->m_authView->GetEmail(),
						\Model\User::PASSWORD => $this->m_authView->GetRegisterPassword(),
						\Model\User::SKILL => $this->m_authView->GetSkill()
					);
					//user to be registed
					$user = new \Model\User($userInfo);
					// do register the user
					if($this->m_authModel->DoRegisterUser($user))
					{
						\Common\Page::AddSuccessMessage(\Common\String::REGISTER_SUCCESS);
					}
				}
				// If the username was taken
				else
				{
					\Common\Page::AddErrorMessage(\Common\String::USERNAME_EXISTS);
				}
			}
			// If there's any validation error we show it.
			else
			{
				\Common\Page::AddErrorMessage($this->m_authView->DoErrorList($this->m_authView->GetErrorMessages()));
			}
		}
		return $this->m_output;
	}

}
