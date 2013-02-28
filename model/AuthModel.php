<?php

namespace Model;

require_once('model/DAL/UserDAL.php');
require_once('model/database/Database.php');
require_once('model/object/User.php');
require_once('model/crypt/Krypter.php');
require_once('common/String.php');

class AuthModel
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
	 * Any user logged in?
	 *
	 * @return BOOLEAN, True = logged in, False = not logged in
	 */
	public static function IsLoggedIn()
	{
		return isset($_SESSION[\Common\String::SESSION_LOGGEDIN]) ? true : false;
	}

	/**
	 * Logging in a user if password is right
	 *
	 * @param $tryUser String, posted username
	 * @param $tryPassword String, posted password
	 * @return Boolean, true = login success, false = login failed.
	 */
	public function DoLogin($tryUser, $tryPassword)
	{
		$acceptedUser = false;
		$user = UserDAL::GetUserByUsername($tryUser);
		if(isset($user))
		{
			if($user->GetPassword() == $tryPassword)
			{
				\Model\User::SetUserSession($user);
				$acceptedUser = true;
			}
		}
		if(isset($acceptedUser))
			return $acceptedUser;
	}

	/**
	 * Logging out a user
	 */
	public function DoLogOut()
	{
		if(isset($_SESSION[\Common\String::SESSION_LOGGEDIN]))
			\Model\User::DeleteUserSession();
	}
	
	/**
	 * Get a user from the database
	 * 
	 * @param $username, username
	 * @return $user or false if the user doesn't exists
	 */
	public function GetUserByName($username)
	{
		$user = UserDAL::GetUserByUsername($username);
		if($user)
			return $user;
		else
			return false;
	}

	public static function test(Database $db)
	{
		// Errormessages is saved in this array
		$errorMessages = array();
		$errorMessages[] = "AuthModel Test";

		$sut = new AuthModel($db);
		$userDAL = new UserDAL($db);

		if($sut->IsLoggedIn())
			$errorMessages[] = 'Something is wrong with the IsLoggedIn() function "When you shouldn\'t be logged in" (on line: ' . __LINE__ . ")";

		// DoLogin fail user
		if($sut->DoLogin("Fiskpinne", "Fläskpannkaka"))
			$errorMessages[] = 'Something is wrong with the DoLogin("Fiskpinne", "Fläskpannkaka") function (on line: ' . __LINE__ . ")";

		// DoLogin success user
		if(!$sut->DoLogin("Fisk", Krypter::Crypt("Fisk22")))
			$errorMessages[] = 'Something is wrong with the DoLogin("Fisk", "Fisk22") function (on line: ' . __LINE__ . ")";

		// Should be logged in.
		if(!$sut->IsLoggedIn())
			$errorMessages[] = 'Something is wrong with the IsLoggedIn() function "When you should be logged in (on line: ' . __LINE__ . ")";

		// Logging out the inlogged test user.
		$sut->DoLogout();
		if($sut->isLoggedIn())
			$errorMessages[] = 'Something is wrong with the DoLogout() function (on line: ' . __LINE__ . ")";

		// DoLogin right username, fail password
		if($sut->DoLogin("Fisk", "Fisken22"))
			$errorMessages[] = 'Something is wrong with the DoLogin("Fisk", "Fisken22") function (on line: ' . __LINE__ . ")";

		// DoLogin fail username, right password
		if($sut->DoLogin("Fisken", "Fisk22"))
			$errorMessages[] = 'Something is wrong with the DoLogin("Fisken", "Fisk22") function (on line: ' . __LINE__ . ")";

		return $errorMessages;
	}

}
