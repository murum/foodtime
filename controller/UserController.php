<?php

namespace Controller;

require_once('view/UserView.php');
require_once('model/UserModel.php');
require_once('view/NavigationView.php');
require_once('model/object/User.php');

class UserController
{
	// member variables
	private $m_output = "";
	private $m_userView;
	private $m_userModel;
	private $m_userInSession;
	
	public function __construct(\Model\Database $db)
	{
		$this->m_userView = new \View\UserView();
		$this->m_userModel = new \Model\UserModel($db);
		$this->m_userInSession = \Model\User::GetUserSession();
	}

	/**
	 * @return $this->m_output 
	 */
	public function DoControll()
	{
		/**
		 * Is the profileQuery is active the user should update profile or delete his own user.
		 */
		if(\View\NavigationView::IsProfileQuery())
		{
			// If user is editing profile
			if(\View\NavigationView::GetProfileQuery() == \View\NavigationView::EDIT)
			{
				// check if user is logged in.
				if(isset($this->m_userInSession))
				{
					$this->m_output = $this->m_userView->DoEditProfileForm($this->m_userInSession);
					if($this->m_userView->TriedToEditProfile())
					{
						if($this->m_userView->ValidateUpdateUser())
						{
							$userInfo = array(
								\Model\User::USERID => $this->m_userInSession->GetUserID(),
								\Model\User::USERNAME => $this->m_userInSession->GetUsername(),
								\Model\User::EMAIL => $this->m_userView->GetEmail(),
								\Model\User::PASSWORD => $this->m_userView->GetPassword(),
								\Model\User::SKILL => $this->m_userView->GetSkill()
							);
							$user = new \Model\User($userInfo);
							// try to update user.
							try
							{
								if($this->m_userModel->DoUpdateUser($user, $this->m_userInSession))
								{
									\Common\Page::AddSuccessMessage(\Common\String::UPDATE_PROFILE_SUCCESS);
								}
							}
							// error in the update process.
							catch (\Exception $e)
							{
								\Common\Page::AddErrorMessage($e->getMessage());
							}
						}
						// validate errors.
						else
						{
							\Common\Page::AddErrorMessage($this->m_userView->DoErrorList($this->m_userView->GetErrorMessages()));
						}
					}
					else if ($this->m_userView->TriedToDeleteProfile())
					{
						// try to delete user
						try
						{
							if($this->m_userModel->DoDeleteUser($this->m_userInSession->GetUserID()))
							{
								\Common\Page::AddSuccessMessage(\Common\String::DELETE_PROFILE_SUCCESS);
							}
						}
						// Erorr in the delete process.
						catch (\Exception $e)
						{
							\Common\Page::AddErrorMessage($e->getMessage());
						}
					}
				}
				// the user who tries to edit a profile ain't logged in.. show the user. 
				else
				{
					\Common\Page::AddErrormessage(\Common\String::NORIGHTS_EDIT_PROFILE);
				}
			}
		}
		/**
		 * If the user query is active will it show a list of all users or a userprofile of any member. 
		 */
		else if(\View\NavigationView::IsUserQuery())
		{
			// query = start.. list all members
			if(\View\NavigationView::GetUserQuery() == \View\NavigationView::START)
			{
				$users = $this->m_userModel->GetUsers();
				// if there's users.
				if($users)
				{
					$this->m_output = $this->m_userView->DoUserList($users);
				}
				// if there's no users
				else
				{
					\Common\Page::AddErrormessage(\Common\String::FAIL_GET_USERS);					
				}
			}
			// query = something else.. detailed user view.
			else 
			{
				$userID = $this->m_userView->GetUserID();
				$user = $this->m_userModel->GetUserByID($userID);
				// if the value is right show the detailed user.
				if($user)
				{
					$this->m_output = $this->m_userView->DoUserProfile($user);
				}
				// if the value is strange there's no user to show..
				else
				{
					\Common\Page::AddErrormessage(\Common\String::FAIL_GET_USER_PROFILE);
				}
			}
		}
		return $this->m_output;
	}

}
