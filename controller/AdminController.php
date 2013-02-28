<?php

namespace Controller;

require_once('view/AdminView.php');
require_once('model/AdminModel.php');

class AdminController
{
	private $m_db;
	private $m_userInSession;
	private $m_output = "";
	public function __construct(\Model\Database $db)
	{
		$this->m_db = $db;
		$this->m_userInSession = \Model\User::GetUserSession();
	}
	
	public function DoControll()
	{		
		$adminModel = new \Model\AdminModel($this->m_db);
		$adminView = new \View\AdminView();
		
		/**
		 * User logged in?
		 */
		if (isset($this->m_userInSession))
		{
			// Is the user an administrator?
			if($adminModel->IsAdmin($this->m_userInSession))
			{
				\Common\Page::AddSuccessMessage(\Common\String::RIGHTS_ADMIN);
				$this->m_output = $adminView->DoStart();
			}
			else 
			{
				\Common\Page::AddErrorMessage(\Common\String::NORIGHTS_ADMIN);
			}
		}
		else 
		{
			\Common\Page::AddErrorMessage(\Common\String::NORIGHTS_ADMIN);
		}
		return $this->m_output;
	}
}