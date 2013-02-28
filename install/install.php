<?php

/**
 * Kod ifrån http://www.hardcode.nl/subcategory_4/article_558-execute-mysql-sql-dump-files-via-php-mysqli.htm
 */

require_once("DBSettings.php");
 
class InstallController
{
	public static function DoControll()
	{
		$installView = New InstallView();
		$installModel = New InstallModel();
		
		$html = $installView->DoInstallForm(); 
		if($installView->TriedToInstall())
		{
			$dbSettings = new DBSettings(
								$installView->GetDBHost(), 
								$installView->GetDBUser(), 
								$installView->GetDBPass()
							);
			try
			{
				if($installModel->DoInstall($dbSettings))
				{
					$html = $installView->DoInstallSuccessMessage();
				}	
			}
			catch (Exception $e)
			{
				$html = $e->getMessage();	
			}
		}
		return $html;
	}
}

class InstallView
{
	private $m_formHost = "dbHost";
	private $m_formUser = "dbUser";
	private $m_formPass = "dbPass";
	private $m_installFormSubmit = "installSubmit";
	
	/**
	 * Get the HOST
	 */
	public function GetDBHost()
	{
		return isset($_POST[$this->m_formHost]) ? $_POST[$this->m_formHost] : "";
	}
	
	/**
	 * Get the USER
	 */
	public function GetDBUser()
	{
		return isset($_POST[$this->m_formUser]) ? $_POST[$this->m_formUser] : "";
	}
	
	/**
	 * Get the PASS
	 */
	public function GetDBPass()
	{
		return isset($_POST[$this->m_formPass]) ? $_POST[$this->m_formPass] : "";
	}
	
	/**
	 * Did the user try to install
	 */
	public function TriedToInstall()
	{
		return isset($_POST[$this->m_installFormSubmit]);
	}
	
	/**
	 * Make html form
	 */
	public function DoInstallForm()
	{
		$defaultHost = "localhost";
		$defaultUser = "root";
		$defaultPassword = "";
		
		$html = "
				<h1 class=\"testRubrik\">Installera applikationen</h1>
				<p>
					För att få fart på applikationen krävs det en databas.
					Databasen skapar vi med hjälp av formuläret här under.
				</p>
				
				<form method=\"post\">
					<label for=\"" . $this->m_formHost . "\">DB Host</label>
					<input type=\"text\" name=\"" . $this->m_formHost . "\" id=\"" . $this->m_formHost . "\" value=\"\" /><br />
					<label for=\"" . $this->m_formUser . "\">DB User</label>
					<input type=\"text\" name=\"" . $this->m_formUser . "\" id=\"" . $this->m_formUser . "\" value=\"\" /><br />
					<label for=\"" . $this->m_formPass . "\">DB Password</label>
					<input type=\"password\" name=\"" . $this->m_formPass . "\" id=\"" . $this->m_formPass . "\" value=\"\" /><br />
					<input type=\"submit\" class=\"left\" name=\"" . $this->m_installFormSubmit . "\" value=\"Installera\" />
				</form>
		";
		return $html;
	}
	
	public function DoInstallSuccessMessage()
	{
		return "
			<p>Du lyckades installera databasen</p>
			<a href=\"/\">Till applikationen</a>
		";
	}
}

class InstallModel
{	
	public function DoInstall(DBSettings $dbSetting)
	{
		$mysqli = new mysqli(
						$dbSetting->GetHost(), 
						$dbSetting->GetUser(), 
						$dbSetting->GetPass()
					);
		if($mysqli->connect_errno)
		{
			 throw new Exception($mysqli->connect_error);
		}
		
		$mysqli->set_charset("utf8");
	 
		$sql = file_get_contents("foodtime.sql");
		if (!$sql){
			throw new Exception('Error opening file');
		}
		
		$mysqli->multi_query($sql);
		
		$mysqli->close();		
		return true;
	}
}
?>

<!DOCTYPE html>
<html lang="sv">
<head>
	<meta charset="UTF-8" />
	<title>Foodtime - Install</title>
	<link rel="stylesheet" href="../frontend/css/style.css" />
</head>
<body>
	<div id="wrapper">
		<div class="container_12">
			<?php echo InstallController::DoControll(); ?>
		</div>
	</div>
</body>
</html>










