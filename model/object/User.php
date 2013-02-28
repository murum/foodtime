<?php

namespace Model;

class User
{
	private $m_user;

	// User variables
	CONST USERID = "UserID";
	CONST USERNAME = "Username";
	CONST PASSWORD = "Password";
	CONST EMAIL = "Email";
	CONST SKILL = "Skill";
	CONST UPDATED = "UpdatedAt";
	CONST ISADMIN = "IsAdmin";

	public function __construct($userInfo)
	{
		if(isset($userInfo[self::USERID]))
		{
			$this->SetUserID($userInfo[self::USERID]);
		}
		else
		{
			$this->SetUserID(-1);
		}
		$this->SetUsername($userInfo[self::USERNAME]);
		$this->SetPassword($userInfo[self::PASSWORD]);
		$this->SetEmail($userInfo[self::EMAIL]);
		$this->SetSkill($userInfo[self::SKILL]);
		if(isset($userInfo[self::UPDATED]))
		{
			$this->SetUpdated($userInfo[self::UPDATED]);
		}
		else
		{
			$this->SetUpdated(-1);
		}
		if(isset($userInfo[self::ISADMIN]))
		{
			$this->SetIsAdmin($userInfo[self::ISADMIN]);
		}
		else
		{
			$this->SetIsAdmin(-1);
		}
	}

	// Getters
	public function GetUserID()
	{
		return $this->m_user[self::USERID];
	}

	public function GetUsername()
	{
		return $this->m_user[self::USERNAME];
	}

	public function GetPassword()
	{
		return $this->m_user[self::PASSWORD];
	}

	public function GetEmail()
	{
		return $this->m_user[self::EMAIL];
	}

	public function GetSkill()
	{
		return $this->m_user[self::SKILL];
	}

	public function GetUpdated()
	{
		return $this->m_user[self::UPDATED];
	}

	public function GetIsAdmin()
	{
		return $this->m_user[self::ISADMIN];
	}

	// Setters
	public function SetUserID($userID)
	{
		$this->m_user[self::USERID] = $userID;
	}

	public function SetUsername($username)
	{
		$this->m_user[self::USERNAME] = $username;
	}

	public function SetPassword($password)
	{
		$this->m_user[self::PASSWORD] = $password;
	}

	public function SetEmail($email)
	{
		$this->m_user[self::EMAIL] = $email;
	}

	public function SetSkill($skill)
	{
		$this->m_user[self::SKILL] = $skill;
	}

	public function SetUpdated($updated)
	{
		$this->m_user[self::UPDATED] = $updated;
	}

	public function SetIsAdmin($isAdmin)
	{
		$this->m_user[self::ISADMIN] = $isAdmin;
	}
	
	/**
	 * Set the user session
	 *
	 * @param $user User
	 */
	public static function SetUserSession(User $user)
	{
		$_SESSION[\Common\String::SESSION_LOGGEDIN] = serialize($user);
	}
	
	/**
	 * Remove the user in session
	 */
	public static function DeleteUserSession()
	{
		unset($_SESSION[\Common\String::SESSION_LOGGEDIN]);
	}
	
	/**
	 * Get the the user stored in the session
	 *
	 * @return $user, the user.
	 */
	public static function GetUserSession()
	{
		return isset($_SESSION[\Common\String::SESSION_LOGGEDIN]) ? unserialize($_SESSION[\Common\String::SESSION_LOGGEDIN]) : NULL;
	}
	
	public static function test()
	{
		// Errormessages is saved in this array
		$errorMessages = array();
		$errorMessages[] = "User Object Test";
		 
		$userID = 1;
		$username = "Fiskbullar";
		$email = "mongoj_92@hotmail.com";
		$password = "Fiskbullar12";
		$skill = 4;
		$updated = "2012-10-26 12:46:52";
		$isAdmin = 1;
		$userInfo = array(
					self::USERID => $userID, 
					self::USERNAME => $username, 
					self::EMAIL => $email, 
					self::PASSWORD => $password, 
					self::SKILL => $skill,
					self::UPDATED => $updated,
					self::ISADMIN => $isAdmin 
					);
		$user = new User($userInfo);
		
		unset($_SESSION[\Common\String::SESSION_LOGGEDIN]);
		if($user->GetUserSession() != NULL)
		{
			$errorMessages[] = "Something is wrong with the GetUserSession function";
		}
		
		$user->SetUserSession($user);
		if($user->GetUserSession() == NULL)
		{
			$errorMessages[] = "Something is wrong with the SetUserSession function";
		}
		unset($_SESSION[\Common\String::SESSION_LOGGEDIN]);
		
		if($user->GetUserID() != $userID)
		{
			$errorMessages[] = "Something is wrong with the GetUserID function";
		}
		
		$user->SetUserID(4);
		if($user->GetUserID() == $userID)
		{
			$errorMessages[] = "Something is wrong with the SetUserID function";
		}
		
		if($user->GetUsername() != $username)
		{
			$errorMessages[] = "Something is wrong with the GetUsername function";
		}
		
		$user->SetUsername("Fisken");
		if($user->GetUsername() == $username)
		{
			$errorMessages[] = "Something is wrong with the SetUsername function";
		}
		
		if($user->GetEmail() != $email)
		{
			$errorMessages[] = "Something is wrong with the GetEmail function";
		}
		$user->SetEmail("Fisk@Fisken.se");
		if($user->GetEmail() == $email)
		{
			$errorMessages[] = "Something is wrong with the SetEmail function";
		}
		
		if($user->GetPassword() != $password)
		{
			$errorMessages[] = "Something is wrong with the GetPassword function";
		}
		
		$user->SetPassword("Fisaksadsd412");
		if($user->GetPassword() == $password)
		{
			$errorMessages[] = "Something is wrong with the SetPassword function";
		}
		
		if($user->GetSkill() != $skill)
		{
			$errorMessages[] = "Something is wrong with the GetSkill function";
		}
		
		$user->SetSkill(2);
		if($user->GetSkill() == $skill)
		{
			$errorMessages[] = "Something is wrong with the SetSkill function";
		}
		
		if($user->GetUpdated() != $updated)
		{
			$errorMessages[] = "Something is wrong with the GetUpdated function";
		}
		
		$user->SetUpdated("2012-10-24 12:46:52");
		if($user->GetUpdated() == $updated)
		{
			$errorMessages[] = "Something is wrong with the SetUpdated function";
		}
		
		if($user->GetIsAdmin() != $isAdmin)
		{
			$errorMessages[] = "Something is wrong with the GetIsAdmin function";
		}
		
		$user->SetIsAdmin(0);
		if($user->GetIsAdmin() == $isAdmin)
		{
			$errorMessages[] = "Something is wrong with the SetIsAdmin function";
		}
		
		return $errorMessages;
	}
}
