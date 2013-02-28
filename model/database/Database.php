<?php

namespace Model;

class Database
{
	private $mysqli = NULL;

	/**
	 * Opens the database connection through a mysqli
	 * 
	 * @param $dbsettings, DBSetting configuration
	 */
	public function Connect(DBSettings $dbsettings)
	{

		$this->mysqli = new \mysqli($dbsettings->GetHost(), $dbsettings->GetUser(), $dbsettings->GetPass(), $dbsettings->GetDB());
		if($this->mysqli->connect_error)
		{
			throw new \Exception($this->mysqli->connect_error);
		}

		// Sets the database encoding
		$this->mysqli->set_charset("utf8");
		return true;
	}
	
	/**
	 * Select one col in one row
	 * 
	 * @param $stmt, mysqli_stmt
	 * @return the databasevalue of the field
	 */
	public function SelectOne(\mysqli_stmt $stmt)
	{		
		if (!$stmt)
			throw new Exception($this->mysqli->error);
		
		if (!$stmt->execute())
		{
			throw new Exception($this->mysqli->error);
		}
		
		$return = 0;
		
		$stmt->bind_result($return);
		$stmt->fetch();
		$stmt->close();
		
		return $return;
	}

	/**
	 * Selecs all columns in one row.
	 * 
	 * @param $stmt, mysqli stmt
	 * @return $return, all columns in a associativ array
	 */
	public function SelectAll(\mysqli_stmt $stmt)
	{		
		if (!$stmt)
		{
			throw new Exception($this->mysqli->error);
		}
		
		if (!$stmt->execute())
		{
			throw new Exception($this->mysqli->error);
		}
		
		// Fetch the result to a associativ array.
		$return = mysqli_fetch_assoc($stmt->get_result());
						
		$stmt->close();
		
		return $return;
	}
	
	/**
	 * Selects all columns and rows in a table
	 * 
	 * @param $stmt, mysqli stmt
	 * @return $results, all rows in a associativ array
	 */
	public function SelectAllTable(\mysqli_stmt $stmt)
	{
		if(!$stmt)
		{
			throw new \Exception($this->mysqli->error);
		}
		if (!$stmt->execute())
		{
			throw new Exception($this->mysqli->error);
		}
		
		$results = array();
		
		$result = $stmt->get_result();
		
		//While there's a row we keep add it to the results array
		while ($row = $result->fetch_array(MYSQLI_ASSOC))
		{
			$results[] = $row;
		}
						
		$stmt->close();
		
		return $results;
	}

	/**
	 * Insert something to the database
	 * 
	 * @param $stmt, mysqli stmt
	 * @return $return, the pk of the inserted row
	 */
	public function Insert(\mysqli_stmt $stmt)
	{
		if(!$stmt)
		{
			throw new \Exception($this->mysqli->error);
		}

		if(!$stmt->execute())
		{
			throw new \Exception($this->mysqli->error, $this->mysqli->errno);
		}
		
		$return = $stmt->insert_id;

		$stmt->close();

		return $return;
	}

	/**
	 * Update something in the database
	 * 
	 * @param $stmt, mysqli stmt
	 * @return boolean/exception
	 */
	public function Update(\mysqli_stmt $stmt)
	{		
		if (!$stmt)
		{
			throw new Exception($this->mysqli->error);
		}
		
		if (!$stmt->execute())
		{
			throw new Exception($this->mysqli->error);
		}
		$stmt->close();
		
		return true;
	}

	/**
	 * Remove something from the database
	 * 
	 * @param $stmt, mysqli stmt
	 * @return $return, true/false.. if there's more than one affected row something have been removed.
	 */
	public function Remove(\mysqli_stmt $stmt)
	{
		if(!$stmt)
		{
			throw new \Exception($this->mysqli->error, $this->mysqli->errno);
		}

		if(!$stmt->execute())
		{
			throw new \Exception($this->mysqli->error, $this->mysqli->errno);
		}

		$return = ($stmt->affected_rows > 0) ? true : false;

		$stmt->close();

		return $return;
	}

	/**
	 * Prepares a SQL Query
	 * @param $sql String SQL Query
	 * @return mysqli_stmt
	 */
	public function Prepare($sql)
	{
		$return = $this->mysqli->prepare($sql);

		if(!$return)
		{
			throw new \Exception($this->mysqli->error);
		}

		return $return;
	}

	/**
	 * Closes the database connection
	 */
	public function Close()
	{
		return $this->mysqli->close();
	}

	public function test($dbsettings)
	{
		$errorMessages = array();
		$errorMessages[] = "Database Test";

		// Create an instance of the object
		$sut = new Database();

		/**
		 * Can we connect to the Database?
		 */
		if(!$sut->Connect($dbsettings))
		{
			$errorMessages[] = "Database conenction failed. (on line: " . __LINE__ . ")";
		}


		/**
		 * Insert user with a static username, should already exist.
		 */
		$username = "testuser";
		$password = "testuser22";
		$email = "fisk@fisk.se";
		$skill = 4;
		$stmt = $sut->Prepare("INSERT INTO user (username, password, email, skill) VALUES (?,?,?,?)");

		$stmt->bind_param('sssi', $username, $password, $email, $skill);

		// Tries to insert a user that already exists
		try
		{
			$sut->Insert($stmt);
			$errorMessages[] = "Inserted a user with a username that already exists (on line: " . __LINE__ . ")";
		}
		catch(\Exception $e)
		{
			// test success
		}
		
		/**
		 * Update a user
		 */
		 $username = "testuser";
		 $password = "testuser22";
		 $email = "testuser@fisk.se";
		 $skill = 3;
		 $stmt = $sut->Prepare("UPDATE user SET email=?, skill=? WHERE username = ?");
		 $stmt->bind_param('sis', $email, $skill, $username);
		 
		 // Tries to insert a user that already exists
		try
		{
			$sut->Update($stmt);
		}
		catch(\Exception $e)
		{
			$errorMessages[] = "Doesn't work to update email, skill (on line: " . __LINE__ . ")";
		}

		/**
		 * Insert user with a unique username.
		 */
		$stmt = $sut->Prepare("SELECT COUNT(*) FROM user");
		$usersInDatabaseBeforeInsert = $sut->SelectOne($stmt);

		// Add a counter of users in database after username to make username unique.
		$username = "testuser" . $usersInDatabaseBeforeInsert;
		$password = "testuser22";

		$stmt = $sut->Prepare("INSERT INTO user (username, password) VALUES (?,?)");

		$stmt->bind_param('ss', $username, Krypter::Crypt($password));

		// Tries to insert a user with unique username
		try
		{
			$sut->Insert($stmt);
		}
		catch(\Exception $e)
		{
			$errorMessages[] = "Failed to insert a user with a unique username. (on line: " . __LINE__ . ")";
		}

		/**
		 * Delete the testuser from the Unique test.
		 */
		$stmt = $sut->Prepare("SELECT COUNT(*) FROM user");
		$usersInDatabaseBeforeDelete = $sut->SelectOne($stmt);

		$username = "testuser" . $usersInDatabaseBeforeInsert;
		$stmt = $sut->Prepare("DELETE FROM user WHERE username = ?");
		$stmt->bind_param('s', $username);

		// Tries to delete the user with a unique username
		try
		{
			$sut->Remove($stmt);
		}
		catch(\Exception $e)
		{
			$errorMessages[] = "Failed to delete the user with a unique username. (on line: " . __LINE__ . ")";
		}

		/**
		 * Can we disconnect from the Database?
		 */
		if(!$sut->Close())
		{
			$errorMessages[] = "Database disconnect failed. (on line: " . __LINE__ . ")";
		}

		return $errorMessages;
	}

}
