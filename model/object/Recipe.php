<?php

namespace Model;

class Recipe
{
	private $m_recipe;

	// Recipe variables
	CONST RECIPEID = "RecipeID";
	CONST USERID = "UserID";
	CONST AUTHOR = "Author";
	CONST RECIPENAME = "RecipeName";
	CONST INGREDIENT = "RecipeIngredient";
	CONST DESCRIPTION = "RecipeDescription";
	CONST SEVERITY = "Severity";

	public function __construct($recipeInfo)
	{
		if(isset($recipeInfo[self::RECIPEID]))
		{
			$this->SetRecipeID($recipeInfo[self::RECIPEID]);
		}
		else
		{
			$this->SetRecipeID(-1);
		}
		if(isset($recipeInfo[self::USERID]))
		{
			$this->SetUserID($recipeInfo[self::USERID]);
		}
		else
		{
			$this->SetUserID(-1);
		}
		$this->SetRecipeName($recipeInfo[self::RECIPENAME]);
		$this->SetRecipeIngredient($recipeInfo[self::INGREDIENT]);
		$this->SetRecipeDescription($recipeInfo[self::DESCRIPTION]);
		$this->SetSeverity($recipeInfo[self::SEVERITY]);
	}

	// Getters
	public function GetRecipeID()
	{
		return $this->m_recipe[self::RECIPEID];
	}
	
	public function GetUserID()
	{
		return $this->m_recipe[self::USERID];
	}
	
	public function GetAuthor()
	{
		return $this->m_recipe[self::AUTHOR];
	}

	public function GetRecipeName()
	{
		return $this->m_recipe[self::RECIPENAME];
	}

	public function GetRecipeIngredient()
	{
		return $this->m_recipe[self::INGREDIENT];
	}

	public function GetRecipeDescription()
	{
		return $this->m_recipe[self::DESCRIPTION];
	}

	public function GetSeverity()
	{
		return $this->m_recipe[self::SEVERITY];
	}

	// Setters
	public function SetRecipeID($recipeID)
	{
		$this->m_recipe[self::RECIPEID] = $recipeID;
	}
	
	public function SetUserID($userID)
	{
		$this->m_recipe[self::USERID] = $userID;
	}
	
	public function SetAuthor($author)
	{
		$this->m_recipe[self::AUTHOR] = $author;
	}

	public function SetRecipeName($recipeName)
	{
		$this->m_recipe[self::RECIPENAME] = $recipeName;
	}

	public function SetRecipeIngredient($recipeIngredient)
	{
		$this->m_recipe[self::INGREDIENT] = $recipeIngredient;
	}

	public function SetRecipeDescription($recipeDescription)
	{
		$this->m_recipe[self::DESCRIPTION] = $recipeDescription;
	}

	public function SetSeverity($severity)
	{
		$this->m_recipe[self::SEVERITY] = $severity;
	}
	
	/**
	 * Set the Recipe Session
	 * 
	 * @param $recipe, RECIPE
	 */
	public static function SetRecipeSession(Recipe $recipe)
	{
		$_SESSION[\Common\String::SESSION_RECIPE] = serialize($recipe);
	}
	
	/**
	 * Get the the recipe stored in the session
	 *
	 * @return $recipe, the user.
	 */
	public static function GetRecipeSession()
	{
		return isset($_SESSION[\Common\String::SESSION_RECIPE]) ? unserialize($_SESSION[\Common\String::SESSION_RECIPE]) : NULL;
	}
	
	public static function test()
	{
		// Errormessages is saved in this array
		$errorMessages = array();
		$errorMessages[] = "Recipe Object Test";
		
		$recipeID = 1; 
		$userID = 1;
		$recipeName = "Fiskbullar";
		$recipeIngredient = "1burk arlafiskbullar";
		$recipeDescription = "Lägg allting i en kastrull och låt koka 15minuter";
		$recipeSeverity = 4;
		$recipeInfo = array(
					self::RECIPEID => $recipeID, 
					self::USERID => $userID, 
					self::RECIPENAME => $recipeName, 
					self::INGREDIENT => $recipeIngredient, 
					self::DESCRIPTION => $recipeDescription,
					self::SEVERITY => $recipeSeverity 
					);
		$recipe = new Recipe($recipeInfo);
		
		unset($_SESSION[\Common\String::SESSION_RECIPE]);
		if($recipe->GetRecipeSession() != NULL)
		{
			$errorMessages[] = "Something is wrong with the GetRecipeSession function";
		}
		
		$recipe->SetRecipeSession($recipe);
		if($recipe->GetRecipeSession() == NULL)
		{
			$errorMessages[] = "Something is wrong with the SetRecipeSession function";
		}
		
		if($recipe->GetRecipeID() != $recipeID)
		{
			$errorMessages[] = "Something is wrong with the GetRecipeID function";
		}
		
		$recipe->SetRecipeID(2);
		if($recipe->GetRecipeID() == $recipeID)
		{
			$errorMessages[] = "Something is wrong with the SetRecipeID function";
		}
		
		if($recipe->GetUserID() != $userID)
		{
			$errorMessages[] = "Something is wrong with the GetUserID function";
		}
		
		$recipe->SetUserID(2);
		if($recipe->GetUserID() == $userID)
		{
			$errorMessages[] = "Something is wrong with the SetUserID function";
		}
		
		if($recipe->GetRecipeName() != $recipeName)
		{
			$errorMessages[] = "Something is wrong with the GetRecipeName function";
		}
		
		$recipe->SetRecipeName("Kaka med maka");
		if($recipe->GetRecipeName() == $recipeName)
		{
			$errorMessages[] = "Something is wrong with the SetRecipeName function";
		}
		
		if($recipe->GetRecipeIngredient() != $recipeIngredient)
		{
			$errorMessages[] = "Something is wrong with the GetRecipeIngredient function";
		}
		
		$recipe->SetRecipeIngredient("1burk arlafiskbulle");
		if($recipe->GetRecipeIngredient() == $recipeIngredient)
		{
			$errorMessages[] = "Something is wrong with the SetRecipeIngredient function";
		}
		
		if($recipe->GetRecipeDescription() != $recipeDescription)
		{
			$errorMessages[] = "Something is wrong with the GetRecipeDescription function";
		}
		
		$recipe->SetRecipeDescription("Gör inte som det står lol.");
		if($recipe->GetRecipeDescription() == $recipeDescription)
		{
			$errorMessages[] = "Something is wrong with the SetRecipeDescription function";
		}
		
		if($recipe->GetSeverity() != $recipeSeverity)
		{
			$errorMessages[] = "Something is wrong with the GetSeverity function";
		}
		
		$recipe->SetSeverity(1);
		if($recipe->GetSeverity() == $recipeSeverity)
		{
			$errorMessages[] = "Something is wrong with the SetSeverity function";
		}
		
		return $errorMessages;
	}
}
