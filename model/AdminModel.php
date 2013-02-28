<?php

namespace Model;

class AdminModel
{
	private $m_db;
	
	public function __construct(Database $db)
	{
		$this->m_db = $db;
	}
	
	public function IsAdmin(User $user)
	{
		return ($user->GetIsAdmin() == 1) ? true : false; 
	}
	
	public static function test(Database $db)
	{
		// Errormessages is saved in this array
		$errorMessages = array();
		$errorMessages[] = "AdminModel Test";

		$sut = new AdminModel($db);
		$userDAL = new UserDAL($db);
		
		$user = $userDAL->GetUserByID(1);
		if(!$sut->IsAdmin($user))
		{
			$errorMessages[] = "Return false but the user should be a admin. failed IsAdmin function (on line: ". __LINE__ . ")";
		}
		
		$user = $userDAL->GetUserByID(2);
		if($sut->IsAdmin($user))
		{
			$errorMessages[] = "Return true but the user should not be a admin. failed IsAdmin function (on line: ". __LINE__ . ")";
		}
		
		return $errorMessages;
	}
	
}
