<?php

namespace Model;

class Validator
{
   // Reguljära uttryck
   private static $m_emailRegExp = "/^[a-z0-9-_]+(\.[a-z0-9-_]+)?@[a-z0-9-]+(\.[a-z0-9-]+)?\.[a-z]{2,6}$/i";

   private static $m_passwordRegExp = "/^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/";
	
	private static $m_startLetterRegEx = "/^[a-zåäöA-ZÅÄÖ]{1}$/";
	private static $m_recipeNameRegEx = "/^[a-zåäöA-ZÅÄÖ]{2}[a-zåäöA-ZÅÄÖ\s]{3,73}$/";
	private static $m_ingredientAndDescriptionRegEx = "/^[0-9a-zåäöA-ZÅÄÖ,:.;]{2}[0-9a-zåäöA-ZÅÄÖ,:.;\s]{18,3998}$/";

   /* Errormessages */
   private static $m_errorMessages;
   private static $m_emailError = "emailError";
	private static $m_recipeNameError = "recipeNameError";
	private static $m_recipeIngredientError = "recipeIngredientError";
	private static $m_recipeDescriptionError = "recipeDescriptionError";
   private static $m_usernameError = "usernameError";
	private static $m_startLetterRecipeNameError = "startLetterRecipeNameError";
	private static $m_startLetterIngredientError = "startLetterIngredientError";
	private static $m_startLetterDescriptionError = "startLetterDescriptionError";
   private static $m_passwordError = "passwordError";
   private static $m_passwordAgainError = "passwordAgainError";
   private static $m_numberError = "numberError";
	private static $m_tagError = "TagError";
   private static $m_filterJSError = "JSError";
   private static $m_filterHTMLAndJSError = "HTMLAndJSError";

   public function __construct()
   {
      self::$m_errorMessages = array();
   }

   public function GetErrorMessages()
   {
      return self::$m_errorMessages;
   }

	/**
    * Validate an recipe name 
    * @param $recipeName Recipe name
    * @return bool
    */
	public function ValidateRecipeName($recipeName)
	{
		$recipeNameFirstLetter = substr($recipeName, 0, 1);
		$recipeNameIsValid = preg_match(self::$m_startLetterRegEx, $recipeNameFirstLetter);
      if(!$recipeNameIsValid)
      {
         self::$m_errorMessages[self::$m_startLetterRecipeNameError] = \Common\String::STARTLETTER_RECIPENAME;
      }
		$recipeNameIsValid  = preg_match(self::$m_recipeNameRegEx, $recipeName);
		if(!$recipeNameIsValid)
		{
			self::$m_errorMessages[self::$m_recipeNameError] = \Common\String::RECIPENAME_LENGTH;
		}
      return $recipeNameIsValid;
	}
	
	/**
    * Validate an recipe name 
    * @param $recipeName Recipe name
    * @return bool
    */
	public function ValidateIngredient($ingredient)
	{
		$ingredientFirstLetter = substr($ingredient, 0, 1);
		$recipeNameIsValid = preg_match(self::$m_startLetterRegEx, $ingredientFirstLetter);
      if(!$recipeNameIsValid)
      {
         self::$m_errorMessages[self::$m_startLetterIngredientError] = \Common\String::STARTLETTER_INGREDIENT;
      }
		$recipeNameIsValid  = preg_match(self::$m_ingredientAndDescriptionRegEx, $ingredient);
		if(!$recipeNameIsValid)
		{
			self::$m_errorMessages[self::$m_recipeIngredientError] = \Common\String::INGREDIENT_LENGTH;
		}
      return $recipeNameIsValid;
	}
	
	/**
    * Validate an recipe name 
    * @param $recipeName Recipe name
    * @return bool
    */
	public function ValidateDescription($description)
	{
		$descriptionFirstLetter = substr($description, 0, 1);
		$recipeNameIsValid = preg_match(self::$m_startLetterRegEx, $descriptionFirstLetter);
      if(!$recipeNameIsValid)
      {
         self::$m_errorMessages[self::$m_startLetterDescriptionError] = \Common\String::STARTLETTER_DESCRIPTION;
      }
		$recipeNameIsValid  = preg_match(self::$m_ingredientAndDescriptionRegEx, $description);
		if(!$recipeNameIsValid)
		{
			self::$m_errorMessages[self::$m_recipeDescriptionError] = \Common\String::DESCRIPTION_LENGTH;
		}
      return $recipeNameIsValid;
	}
	
   /**
    * Validate an Emailadress ex daniel@domain.se daniel.toll@lnu.se daniel@student.lnu.se
    * @param $email Email adress
    * @return bool
    */
   public function ValidateEmail($email)
   {
      $emailIsValid = preg_match(self::$m_emailRegExp, $email);
      if(!$emailIsValid)
      {
         self::$m_errorMessages[self::$m_emailError] = \Common\String::EMAIL_FORMAT;
      }
      return $emailIsValid;
   }

   /**
    * Validate an username
    * @param $username Username
    * @return bool
    */
   public function ValidateUsername($username)
   {
      $usernameIsValid = true;
      if(!isset($username))
      {
         self::$m_errorMessages[self::$m_usernameError] = \Common\String::USERNAME_NOT_NULL;
         $usernameIsValid = false;
      }
      if(strlen($username) < 3 || strlen($username) > 50)
      {
         self::$m_errorMessages[self::$m_usernameError] = \Common\String::USERNAME_LENGTH;
         $usernameIsValid = false;
      }
      return $usernameIsValid;
   }

   /**
    * Validate an password
    * @param $password Password
    * @return bool
    */
   public function ValidatePassword($password)
   {
      $passwordIsValid = true;
      if(!preg_match(self::$m_passwordRegExp, $password))
      {
         self::$m_errorMessages[self::$m_passwordError] = \Common\String::PASSWORD_FORMAT;
         $passwordIsValid = false;
      }
      return $passwordIsValid;
   }

   /**
    * Validate so the repeated password matches the first
    * @param $password Password
    * @param $secondPassword repeated password
    * @return bool
    */
   public function ValidatePasswordMatch($password, $secondPassword)
   {
      $passwordIsValid = true;
      if($password != $secondPassword)
      {
         self::$m_errorMessages[self::$m_passwordAgainError] = \Common\String::REPEAT_PASSWORD_NOT_MATCH;
         $passwordIsValid = false;
      }
      return $passwordIsValid;
   }

   /**
    * Validate var as number
    * @param $skill Number
    * @return bool
    */
   public function ValidateSkill($skill)
   {
      $numberIsValid = true;
      if(!is_numeric($skill))
      {
         self::$m_errorMessages[self::$m_numberError] = \Common\String::SKILL_NAN;
         $numberIsValid = false;
      }
      if($skill > \Common\String::SKILL_MAX)
      {
      	self::$m_errorMessages[self::$m_numberError] = \Common\String::SKILL_TO_HIGH;
         $numberIsValid = false;
      }
		if($skill < \Common\String::SKILL_MIN)
      {
      	self::$m_errorMessages[self::$m_numberError] = \Common\String::SKILL_TO_LOW;
         $numberIsValid = false;
      }

      return $numberIsValid;
   }

	/**
	 * 
	 */
	public function AnyTags($input)
	{
		$inputWithoutTags = strip_tags($input);
		if($inputWithoutTags == $input)
		{
			return true;
		}
		else
		{
			self::$m_errorMessages[self::$m_numberError] = \Common\String::USERNAME_OR_EMAIL_WITH_TAG;
			return false;
		}
	}
	
   public function testErrorMessageFormat($testName, $testFunction)
   {
      return "<div>
                      Fel vid validering av $testName
                      <div>
                          <p>TEST: $testFunction</p>
                      </div>
                  </div>";
   }

   public static function test()
   {
      $errorMessages = array();
      $errorMessages[] = "Validator Test";
      

      $sut = new Validator();

		/**
		 * Recipe name with numbers
		 */
		if($sut->ValidateRecipeName("1232132"))
      {
         $errorMessages[] = "RecipeName '1232132' does work (on line: " . __LINE__ . ")";
      }
		
		/**
		 * Recipe which start with a whitespace
		 */
		if($sut->ValidateRecipeName(" Fiskbullar med senapsmak"))
      {
         $errorMessages[] = "RecipeName ' Fiskbullar med senapsmak' does work (on line: " . __LINE__ . ")";
      }
		
		/**
		 * Recipe to short
		 */
		if($sut->ValidateRecipeName("Fisk"))
      {
         $errorMessages[] = "RecipeName 'Fisk' does work (on line: " . __LINE__ . ")";
      }
		
		/**
		 * Recipe more than 75chars
		 */
		if($sut->ValidateRecipeName("Fiskbullar med senapsmak Fiskbullar med senapsmak Fiskbullar med senapsmak Fiskbullar med senapsmak Fiskbullar med senapsmak Fiskbullar med senapsmak Fiskbullar med senapsmak Fiskbullar med senapsmak Fiskbullar med senapsmak Fiskbullar med senapsmak Fiskbullar med senapsmak Fiskbullar med senapsmak"))
      {
         $errorMessages[] = "RecipeName 'Fiskbullar med senapsmak Fiskbullar med senapsmak Fiskbullar med senapsmak Fiskbullar med senapsmak Fiskbullar med senapsmak Fiskbullar med senapsmak Fiskbullar med senapsmak Fiskbullar med senapsmak Fiskbullar med senapsmak Fiskbullar med senapsmak Fiskbullar med senapsmak Fiskbullar med senapsmak' does work (on line: " . __LINE__ . ")";
      }
		
		/**
		 * Valid recipename
		 */
		if(!$sut->ValidateRecipeName("Fiskbullar med senapsmak"))
      {
         $errorMessages[] = "RecipeName 'Fiskbullar med senapsmak' doesn't work (on line: " . __LINE__ . ")";
		}
		
		/**
		 * Ingredient start with whitespace.
		 */
		if($sut->ValidateIngredient(" 1Burk Arla fiskbullar med senapsmak"))
      {
         $errorMessages[] = "Ingredient ' 1Burk Arla fiskbullar med senapsmak' does work (on line: " . __LINE__ . ")";
		}
		
		/**
		 * Ingredient to short
		 */
		if($sut->ValidateIngredient("1Burk Arla"))
      {
         $errorMessages[] = "Ingredient '1Burk Arla' does work (on line: " . __LINE__ . ")";
		}
		
		/**
		 * Ingredient with just numbers
		 */
		if($sut->ValidateIngredient("3213123123122"))
      {
         $errorMessages[] = "Ingredient '3213123123122' doesn't work (on line: " . __LINE__ . ")";
		}
		
		/**
		 * Valid ingredient
		 */
		if(!$sut->ValidateIngredient("1burk Arla fiskbullar med senapsmak"))
      {
         $errorMessages[] = "Ingredient '1Burk Arla fiskbullar med senapsmak' does work (on line: " . __LINE__ . ")";
		}
		
		/**
		 * Valid description
		 */
		if(!$sut->ValidateDescription("Gör såhär: 1. Lägg allting i en kastrull låt koka ca 15minuter."))
      {
         $errorMessages[] = "Description 'Gör såhär: 1. Lägg allting i en kastrull låt koka ca 15minuter.' does work (on line: " . __LINE__ . ")";
		}
		
		/**
		 * Description start with white space
		 */
		if($sut->ValidateDescription(" Gör såhär: 1. Lägg allting i en kastrull låt koka ca 15minuter."))
      {
         $errorMessages[] = "Description ' Gör såhär: 1. Lägg allting i en kastrull låt koka ca 15minuter.' does work (on line: " . __LINE__ . ")";
		}
		
		/**
		 * Description with just numbers
		 */
		if($sut->ValidateDescription("32131321321321 1"))
      {
         $errorMessages[] = "Description '32131321321321 1' does work (on line: " . __LINE__ . ")";
		}
		
		/**
		 * To short description
		 */
		if($sut->ValidateDescription("Gör såhär: 1."))
      {
         $errorMessages[] = "Description 'Gör såhär: 1.' does work (on line: " . __LINE__ . ")";
		}
		
		/**
		 * To long description 
		 */
		if($sut->ValidateDescription("Gör såhär: 1. Lägg allting i en kastrull låt koka ca 15minuter.Gör såhär: 1. Lägg allting i en kastrull låt koka ca 15minuter.Gör såhär: 1. Lägg allting i en kastrull låt koka ca 15minuter.Gör såhär: 1. Lägg allting i en kastrull låt koka ca 15minuter.Gör såhär: 1. Lägg allting i en kastrull låt koka ca 15minuter.Gör såhär: 1. Lägg allting i en kastrull låt koka ca 15minuter.Gör såhär: 1. Lägg allting i en kastrull låt koka ca 15minuter.Gör såhär: 1. Lägg allting i en kastrull låt koka ca 15minuter.Gör såhär: 1. Lägg allting i en kastrull låt koka ca 15minuter.Gör såhär: 1. Lägg allting i en kastrull låt koka ca 15minuter.Gör såhär: 1. Lägg allting i en kastrull låt koka ca 15minuter.Gör såhär: 1. Lägg allting i en kastrull låt koka ca 15minuter.Gör såhär: 1. Lägg allting i en kastrull låt koka ca 15minuter.Gör såhär: 1. Lägg allting i en kastrull låt koka ca 15minuter.Gör såhär: 1. Lägg allting i en kastrull låt koka ca 15minuter.Gör såhär: 1. Lägg allting i en kastrull låt koka ca 15minuter.Gör såhär: 1. Lägg allting i en kastrull låt koka ca 15minuter.Gör såhär: 1. Lägg allting i en kastrull låt koka ca 15minuter.Gör såhär: 1. Lägägg allting i en kastrull låt koka ca 15minuter.Gör såhär: 1. Lägg allting i en kastrull låt koka ca 15minuter.Gör såhär: 1. Lägg allting i en kastrull låt koka ca 15minuter.Gör såhär: 1. Lägg allting i en kastrull låt koka ca 15minuter.Gör såhär: 1. Lägg allting i en kastrull låt koka ca 15minuter.Gör såhär: 1. Lägg allting i en kastrull låt koka ca 15minuter.Gör såhär: 1. Lägg allting i en kastrull låt koka ca 15minuter.Gör såhär: 1. Lägg allting i en kastrull låt koka ca 15minuter.Gör såhär: 1. Lägg allting i en kastrull låt koka ca 15minuter.Gör såhär: 1. Lägg allting i en kastrull låt koka ca 15minuter.Gör såhär: 1. Lägg allting i en kastrull låt koka ca 15minuter.Gör såhär: 1. Lägg allting i en kastrull låt koka ca 15minuter.Gör såhär: 1. Lägg allting i en kastrull låt koka ca 15minuter.Gör såhär: 1. Lägg allting i en kastrull låt koka ca 15minuter.Gör såhär: 1. Lägg allting i en kastrull låt koka ca 15minuter.Gör såhär: 1. Lägg allting i en kastrull låt koka ca 15minuter.Gör såhär: 1. Lägg allting i en kastrull låt koka ca 15minuter.Gör såhär: 1. Lägg allting i en kastrull låt koka ca 15minuter.Gör såhär: 1. Lägg allting i en kastrull låt koka ca 15minuter.Gör såhär: 1. Lägg allting i en kastrull låt koka ca 15minuter.Gör såhär: 1. Lägg allting i en kastrull låt koka ca 15minuter.Gör såhär: 1. Lägg allting i en kastrull låt koka ca 15minuter.Gör såhär: 1. Lägg allting i en kastrull låt koka ca 15minuter.Gör såhär: 1. Lägg allting i en kastrull låt koka ca 15minuter.g allting i en kastrull låt koka ca 15minuter.Gör såhär: 1. Lägg allting i en kastrull låt koka ca 15minuter.Gör såhär: 1. Lägg allting i en kastrull låt koka ca 15minuter.Gör såhär: 1. Lägg allting i en kastrull låt koka ca 15minuter.Gör såhär: 1. Lägg allting i en kastrull låt koka ca 15minuter.Gör såhär: 1. Lägg allting i en kastrull låt koka ca 15minuter.Gör såhär: 1. Lägg allting i en kastrull låt koka ca 15minuter.Gör såhär: 1. Lägg allting i en kastrull låt koka ca 15minuter.Gör såhär: 1. Lägg allting i en kastrull låt koka ca 15minuter.Gör såhär: 1. Lägg allting i en kastrull låt koka ca 15minuter.Gör såhär: 1. Lägg allting i en kastrull låt koka ca 15minuter.Gör såhär: 1. Lägg allting i en kastrull låt koka ca 15minuter.Gör såhär: 1. Lägg allting i en kastrull låt koka ca 15minuter.Gör såhär: 1. Lägg allting i en kastrull låt koka ca 15minuter.Gör såhär: 1. Lägg allting i en kastrull låt koka ca 15minuter.Gör såhär: 1. Lägg allting i en kastrull låt koka ca 15minuter.Gör såhär: 1. Lägg allting i en kastrull låt koka ca 15minuter.Gör såhär: 1. Lägg allting i en kastrull låt koka ca 15minuter.Gör såhär: 1. Lägg allting i en kastrull låt koka ca 15minuter.Gör såhär: 1. Lägg allting i en kastrull låt koka ca 15minuter.Gör såhär: 1. Lägg allting i en kastrull låt koka ca 15minuter.Gör såhär: 1. Lägallting i e: 1. Lägallting i ellting i e"))
      {
         $errorMessages[] = "Description 'Gör såhär: 1. Lägg allting i en kastrull låt koka ca 15minuter...............' doesn't work (on line: " . __LINE__ . ")";
		}
		
      /**
       * Test with a valid email address.
       */
      if(!$sut->ValidateEmail("mongoj_92@hotmail.se"))
      {
         $errorMessages[] = "Email 'mongoj_92@hotmail.se' doesn't goes through as it should. (on line: " . __LINE__ . ")";
      }

      /**
       * Test with a bad email address
       */
      if($sut->ValidateEmail("hej"))
      {
         $errorMessages[] = "Email you can enter a email without a domain. (on line: " . __LINE__ . ")";
      }

      if($sut->ValidateEmail(".@.com"))
      {
         $errorMessages[] = "Email you can write an email with .@.com (on line: " . __LINE__ . ")";
      }

      /**
       * Test with good username
       */
      if(!($sut->ValidateUsername("Fisken")))
      {
         $errorMessages[] = "Username an password with 6 characters doesn't work as username as it should. (on line: " . __LINE__ . ")";
      }

      /**
       * Test with bad username
       */
      if($sut->ValidateUsername("JoakimhatarmffimhatarmffnimhatarmffnimhatarmffnimhatarmffnnardeforlorarmotGAISellerOstersIF"))
      {
         $errorMessages[] = "Username a user naem that's to long goes through the validator (on line: " . __LINE__ . ")";
      }

      /**
       * Test with correct password
       */
      if(!$sut->ValidatePassword("FiskBulle22"))
      {
         $errorMessages[] = "Password something is wrong with the validatepassword, a password that should much, doesn't match (on line: " . __LINE__ . ")";
      }

      /**
       * Test with bad password
       */
      if($sut->ValidatePassword("jesper"))
      {
         $errorMessages[] = "Password you can write a password without uppercased letter and no digit (on line: " . __LINE__ . ")";
      }

      /**
       * Test with bad password
       */
      if($sut->ValidatePassword("jesper2"))
      {
         $errorMessages[] = "Password you can write a password with no uppercased letter. (on line: " . __LINE__ . ")";
      }

      /**
       * Test with correct Skill as string
       */
      if(!$sut->ValidateSkill("4"))
      {
         $errorMessages[] = "Kunskapsnivå you can't enter a string number into validate skill (on line: " . __LINE__ . ")";
      }

      /**
       * Test with correct Skill as int
       */
      if(!$sut->ValidateSkill(4))
      {
         $errorMessages[] = "Kunskapsnivå you can't enter an number to the validateNumber function (on line: " . __LINE__ . ")";
      }
		
		/**
       * Test with a to high Skill as int
       */
      if($sut->ValidateSkill(8))
      {
         $errorMessages[] = "Kunskapsnivå you can enter a value that is to high to the validateNumber function (on line: " . __LINE__ . ")";
      }
		
		/**
       * Test with a to low Skill as int
       */
      if($sut->ValidateSkill(0))
      {
         $errorMessages[] = "Kunskapsnivå you can enter a value that is to low to the validateNumber function (on line: " . __LINE__ . ")";
      }

      /**
       * Test with bad Skill
       */
      if($sut->ValidateSkill("jesper"))
      {
         $errorMessages[] = "Kunskapsnivå you can write a string and it validates as a number (on line: " . __LINE__ . ")";
      }

		/**
		 * Anytags test
		 */
		if($sut->AnyTags("</div>Fisken"))
		{
			$errorMessages[] = "AnyTags, it worked to write tags in a textfield (on line: " . __LINE__ . ")";
		}

      return $errorMessages;
   }

}
