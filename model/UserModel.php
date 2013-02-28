<?php

namespace Model;

require_once ('model/DAL/UserDAL.php');

class UserModel
{
	private $m_db = NULL;

	public function __construct(Database $db)
	{
		$this->m_db = $db;
	}
	
	public function IsRegistered($username)
	{
		$user = UserDAL::GetUserByUsername($username);
		if($user->GetUsername() == null)
			return false;
		
		return true;
	}
	
	/**
	 * Register a user into the database.
	 *
	 * @param $user, User Object
	 * @return $registerStatus, Boolean
	 */
	public function DoRegisterUser(User $user)
	{
		$registerStatus = true;
		$user->SetPassword(Krypter::Crypt($user->GetPassword()));
		
		if(!UserDAL::AddUser($user))
			$registerStatus = false;

		return $registerStatus;
	}
	
	/**
	 * Updates a user who's in the database.
	 *
	 * @param $user, User Object
	 * @param $userInSession, The user stored in session
	 * @return $updateStatus, Boolean
	 */
	public function DoUpdateUser(\Model\User $user, \Model\User $userInSession)
	{
		$updateStatus = true;
		
		$user->SetPassword(Krypter::Crypt($user->GetPassword()));
				
		// Check if the entered password is the same as the real password
		if($user->GetPassword() == $userInSession->GetPassword())
		{
			if(UserDAL::UpdateUserProfile($user))
			{
				// set the session to the $user with new values.
				$_SESSION[\Common\String::SESSION_LOGGEDIN] = serialize($user);
			}
			else
			{
				$updateStatus = false;
			}
		}
		else 
		{
			throw new \Exception(\Common\String::PASSWORD_MATCH_UPDATE_PROFILE);
		}

		return $updateStatus;
	}
	
	/**
	 * Delete a user who's in the database.
	 *
	 * @param $userID, user id
	 * @return $updateStatus, Boolean
	 */
	public function DoDeleteUser($userID)
	{
		$deleteStatus = true;
		
		if(UserDAL::DeleteUser($userID))
			\Model\User::DeleteUserSession();
		else
			$deleteStatus = false;

		return $deleteStatus;
	}
	
	/**
	 * Get a user from the database
	 * 
	 * @param $userID, user id
	 * @return the user
	 */
	public function GetUserByID($userID)
	{
		$user = UserDAL::GetUserByID($userID);
		if($user)
			return $user;
		else
			return false;
	}
	
	/**
	 * Get a user from the database
	 * 
	 * @param $Username, Username
	 * @return User . the user.
	 */
	public function GetUserByUsername($username)
	{
		$user = UserDAL::GetUserByUsername($username);
		if($user)
			return $user;
		else
			return false;
	}
	
	/**
	 * Get a user from the database
	 * 
	 * @param $userID, user id
	 * @return the user
	 */
	public function GetUsers()
	{
		$users = UserDAL::GetAllUsers();
		if($users)
			return $users;
		else
			return false;
	}
	
	public static function test(Database $db)
	{
		// Errormessages is saved in this array
		$errorMessages = array();
		$errorMessages[] = "UserModel Test";
		
		$sut = new UserModel($db);
		
		/**
		 * Get all users
		 */
		$users = $sut->GetUsers();
		if(count($users) != 3)
			$errorMessages[] = "Something is wrong with the GetUsers() function";
		
		/**
		 * Get a user with ID
		 */
		$userOne = $sut->GetUserByID(1);
		if($userOne->GetUserID() != 1)
			$errorMessages[] = "Something is wrong with the GetUserByID() function";
		
		/**
		 * Delete a user
		 */
		$userToRemove = $sut->GetUserByID(1);
		$userToRemove->SetUsername("NewTestUser");
		$userToRemove->SetUserID(-1);
		$userIDToRemove = \Model\UserDAL::AddUser($userToRemove);
		if(!$sut->DoDeleteUser($userIDToRemove))
			$errorMessages[] = "Something is wrong with the DoDeleteUser() function";
		
		
		/**
		 * Update a user
		 */
		$userBeforeUpdate = $sut->GetUserByID(1);
		$userToUpdateOldEmail = $userBeforeUpdate->GetEmail();
		$userBeforeUpdate->SetEmail("mongoj_92@hotmail.com");
		$sut->DoUpdateUser($userBeforeUpdate, $userBeforeUpdate);
		$userAfterUpdate = $sut->GetUserByID(1);
		
		if($userToUpdateOldEmail == $userAfterUpdate->GetEmail())
			$errorMessages[] = "Something is wrong with the DoUpdateUser() function";
		
		$userToChangeBackTo = $sut->GetUserByID(1);
		$userToChangeBackTo->SetEmail($userToUpdateOldEmail);
		$sut->DoUpdateUser($userToChangeBackTo, $userToChangeBackTo);
		
		return $errorMessages;
	}
}
