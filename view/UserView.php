<?php

namespace View;

class UserView
{
	private $m_errorMessages = array();
	private $m_validator;
	
	public function __construct()
	{
		$this->m_validator = new \Model\Validator();
	}
	
	/**
	 * Sets the errormessages array with the validator errorMessages
	 * 
	 */
	public function SetErrorMessages()
	{
		foreach($this->m_validator->GetErrorMessages() as $errorMessage)
		{
			$this->m_errorMessages[] = $errorMessage;
		}
	}

	/**
	 * Get the errormessages array
	 * 
	 * @return array string
	 */
	public function GetErrorMessages()
	{
		return $this->m_errorMessages;
	}
	
	public function GetUserID()
	{
		return NavigationView::IsUserQuery() ? NavigationView::GetUserQuery() : NULL;
	}
	
	/**
	 * Get the email that was entered to update the information
	 *
	 * @return string, emailadress
	 */
	public function GetEmail()
	{
		return isset($_POST[\Common\String::EMAIL]) ? $_POST[\Common\String::EMAIL] : NULL;
	}
	
	/**
	 * Get the email that was entered to update the information
	 *
	 * @return string, password
	 */
	public function GetPassword()
	{
		return isset($_POST[\Common\String::PASSWORD]) ? $_POST[\Common\String::PASSWORD] : NULL;
	}

	/**
	 * Get the email that was entered to update the information
	 *
	 * @return string, emailadress
	 */
	public function GetSkill()
	{
		return isset($_POST[\Common\String::SKILL]) ? $_POST[\Common\String::SKILL] : NULL;
	}
	
	/**
	 * Check if the user tried to update his/her profile
	 *
	 * @return boolean
	 */
	public function TriedToEditProfile()
	{
		return isset($_POST[\Common\String::EDIT_PROFILE_SUBMIT]);
	}
	
	/**
	 * Check if the user tried to delete his/her profile
	 *
	 * @return boolean
	 */
	public function TriedToDeleteProfile()
	{
		return isset($_POST[\Common\String::DELETE_PROFILE_SUBMIT]);
	}
	
	/**
	 * Validate the fields in register form to a extern Validator
	 * 
	 * @return boolean
	 */
	public function ValidateUpdateUser()
	{
		$this->m_validator->ValidateEmail($this->GetEmail());
		$this->m_validator->ValidateSkill($this->GetSkill());
		
		$this->SetErrorMessages();
		
		if(count($this->GetErrorMessages()) != 0) {
			return false;
		}
		return true;
	}
	
	/**
	 * Edit a user form
	 *
	 * @return $html, generated html code
	 */
	public function DoEditProfileForm(\Model\User $user)
	{
		$html = "
			<form class=\"editProfile\" method=\"post\">
				<h3>" . $user->GetUsername() . "</h3>
				<label for=\"" . \Common\String::EMAIL . "\">Email</label>
				<input type=\"email\" id=\"" . \Common\String::EMAIL . "\" name=\"" . \Common\String::EMAIL . "\" value=\"" . $user->GetEmail() . "\" />
				<label for=\"" . \Common\String::SKILL . "\">Kunskapsnivå</label>
				<input type=\"number\" min=\"" . \Common\String::SKILL_MIN . "\" max=\"" . \Common\String::SKILL_MAX . "\" id=\"" . \Common\String::SKILL . "\" name=\"" . \Common\String::SKILL . "\" value=\"" . $user->GetSkill() . "\" />
				<label for=\"" . \Common\String::PASSWORD . "\">Lösenord</label>
				<input type=\"password\" id=\"" . \Common\String::PASSWORD . "\" name=\"" . \Common\String::PASSWORD . "\" /><br />
				<input class=\"left\" type=\"submit\" name=\"" . \Common\String::EDIT_PROFILE_SUBMIT . "\" value=\"" . \Common\String::EDIT_PROFILE_SUBMIT_TEXT . "\" />
				<input class=\"left secondButton\" type=\"submit\" name=\"" . \Common\String::DELETE_PROFILE_SUBMIT . "\" value=\"" . \Common\String::DELETE_PROFILE_SUBMIT_TEXT . "\" />
			</form>
		";
		return $html;
	}

	/**
	 * User profile
	 *
	 * @return $html, generated html code
	 */
	public function DoUserProfile(\Model\User $user)
	{
		$html = "
			<h2>" . $user->GetUsername() . "</h2>
			<dl>
				<dt>Mailadress</dt>
				<dd>" . $user->GetEmail() . "</dd>
				<dt>Kunskapsnivå för matlagning är</dt>
					<dd><meter value=\"" . $user->GetSkill() . "\" min=\"0\" max=\"5\">/meter></dd>
			</dl>
		";
		return $html;
	}
	
	/**
	 * List all users
	 *
	 * @return $html, generated html code
	 */
	public function DoUserlist($users)
	{
		$html = "
			<ul>";
		foreach ($users as $user) {
			$html .= "<li><a class=\"userLink\" href=\"" . NavigationView::GetUserProfileLink($user->GetUserID()) . "\">" . $user->GetUsername() . "</a></li>";	
		}
		$html .= "
			</ul>
		";
		return $html;
	}

	/**
	 * Do errorlist
	 * 
	 * @param $errorMessages, string array
	 */
	public function DoErrorList($errorMessages)
	{
		foreach ($errorMessages as $message ) {
			\Common\Page::AddErrorMessage($message);
		}
	}
}
