<?php

namespace Model;

require_once ('model/object/User.php');

class UserDAL
{
	const table_name = "user";

	private static $m_db;

	public function __construct(Database $db)
	{
		self::$m_db = $db;
	}

	/**
	 * Get all users from the databse
	 * 
	 * @return User array.. a array of user objects
	 */
	public static function GetAllUsers()
	{
		$table = UserDAL::table_name;

		$query = "SELECT * FROM $table";
		$stmt = self::$m_db->Prepare($query);

		try
		{
			$usersInfo = self::$m_db->SelectAllTable($stmt);
		}
		catch (exception $e)
		{
			return false;
		}

		$users = array();
		foreach($usersInfo as $userInfo)
		{
			$users[] = new User($userInfo);
		}

		return $users;
	}

	/**
	 * Get a user from the database with a user ID
	 * 
	 * @param $userid, User Userid
	 * @return User, a userobject
	 */
	public static function GetUserByID($userID)
	{
		$table = UserDAL::table_name;
		$fieldToMatch = "UserID";
		$query = "SELECT * FROM $table WHERE $fieldToMatch = ?";

		$stmt = self::$m_db->Prepare($query);

		$stmt->bind_param("i", $userID);

		$userInfo = array();

		try
		{
			$userInfo = self::$m_db->SelectAll($stmt);
			if(!isset($userInfo))
			{
				return false;
			}
		}
		catch (exception $e)
		{
			return false;
		}

		return new User($userInfo);
	}

	/**
	 * Get a user from the database with a selected username
	 * 
	 * @param $username User Username
	 * @return User . user objet
	 */
	public static function GetUserByUsername($username)
	{
		$table = UserDAL::table_name;
		$fieldToMatch = "Username";
		$query = "SELECT * FROM $table WHERE $fieldToMatch = ?";

		$stmt = self::$m_db->Prepare($query);

		$stmt->bind_param("s", $username);

		$userInfo = array();

		try
		{
			$userInfo = self::$m_db->SelectAll($stmt);
		}
		catch (exception $e)
		{
			return false;
		}

		return new User($userInfo);
	}

	/**
	 * Adds an user to the database
	 *
	 * @param $user , User object
	 * @return bool
	 */
	static public function AddUser(User $user)
	{
		$table = UserDAL::table_name;
		$sqlQuery = "INSERT INTO $table (Username, Password, Email, Skill) VALUES (?, ?, ?, ?)";

		$stmt = self::$m_db->Prepare($sqlQuery);

		// Get nessecary data to register a user
		$username = $user->GetUsername();
		$password = $user->GetPassword();
		$email = $user->GetEmail();
		$skill = $user->GetSkill();

		$stmt->bind_param("sssi", $username, $password, $email, $skill);

		try
		{
			// Execute the query and return the USERID
			$userID = self::$m_db->Insert($stmt);
		}
		catch (exception $e)
		{
			return false;
		}

		return $userID;
	}

	/**
	 * Update a user information
	 * 
	 * @return bool
	 */
	static public function UpdateUserProfile(User $user)
	{
		$table = UserDAL::table_name;
		// Query for adding a user to the databse
		$sqlQuery = "UPDATE $table SET Email=?, Skill=? WHERE UserID=?";
		
		// Prepare the statement
		$stmt = self::$m_db->Prepare($sqlQuery);
		
		$email = $user->GetEmail();
		$skill = $user->GetSkill();
		$userID = $user->GetUserID();
		
		$stmt->bind_param("sii", $email, $skill, $userID);
		
		try
		{
			self::$m_db->Update($stmt);
		}
		catch (exception $e)
		{
			return false;
		}
		
		return true;
	}
	
	/**
	 * Delete a user from the database
	 *
	 * @return bool
	 */
	static public function DeleteUser($userID)
	{
		$table = UserDAL::table_name;
		
		$sqlQuery = "DELETE FROM $table WHERE UserID=?";

		$stmt = self::$m_db->Prepare($sqlQuery);

		$stmt->bind_param("i", $userID);

		$return = true;

		try
		{
			$return = self::$m_db->Remove($stmt);
		}
		catch (exception $e)
		{
			return false;
		}

		return $return;
	}

	public static function test(Database $db)
	{
		$errorMessages = array();
		$errorMessages[] = "UserDAL Test";

		$sut = new UserDAL($db);

		/**
		 * Test to get a user that exists and check the ID
		 * TEST #1
		 */
		$user = $sut->GetUserByUsername("Fisken");
		$userID = $user->GetUserID();
		if($userID != 2)
		{
			$errorMessages[] = "GetUserByUsername failed (on line: " . __LINE__ . ")";
		}

		/**
		 * Test to get a user from the GetUserByID
		 * TEST #2
		 */
		if(!$sut->GetUserByID(2))
		{
			$errorMessages[] = "GetUserByID failed (on line: " . __LINE__ . ")";
		}
		
		/**
		 * Test to update a users profile
		 * TEST #3
		 */
		if(!$sut->UpdateUserProfile($user))
		{
			$errorMessages[] = "UpdateUserProfile failed (on line: " . __LINE__ . ")";
		}

		/**
		 * Add a user with a unique username
		 * TEST #4
		 */
		$users = $sut->GetAllUsers();
		try
		{
			$userInfo = array(
				User::USERNAME => 'Bengan' . count($users),
				User::PASSWORD => 'Bengan22',
				User::EMAIL => 'Bengan@telia.com',
				User::SKILL => '4'
			);
			$user = new User($userInfo);
			$userIDToRemove = $sut->AddUser($user); // This user will be deleted in TEST #6
			// Testsuccess
		}
		catch (\Exception $e)
		{
			$errorMessages[] = "AddUser failed (on line: " . __LINE__ . ")";
		}

		/**
		 * Add a user with the same username as above
		 * TEST #5
		 */
		$userInfo = array(
			User::USERNAME => 'Bengan' . count($users),
			User::PASSWORD => 'Bengan22',
			User::EMAIL => 'Bengan@telia.com',
			User::SKILL => '4'
		);
		$user = new User($userInfo);
		try
		{
			if(!$sut->AddUser($user))
			{
				$errorMessages[] = "AddUser failed (on line: " . __LINE__ . ")";
			}
		}
		catch(\Exception $e)
		{

		}

		/**
		 * Delete a user
		 * TEST #6
		 */
		if(!$sut->DeleteUser($userIDToRemove))
		{
			$errorMessages[] = "RemoveUser failed (on line: " . __LINE__ . ")";
		}

		/**
		 * Delete a user that not exists
		 * TEST #7
		 */
		if($sut->DeleteUser($userIDToRemove))
		{
			$errorMessages[] = "RemoveUser failed (on line: " . __LINE__ . ")";
		}

		return $errorMessages;
	}

}
