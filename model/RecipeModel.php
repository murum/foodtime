<?php

namespace Model;

require_once ('model/DAL/RecipeDAL.php');

class RecipeModel
{
	private $m_db = NULL;

	public function __construct(Database $db)
	{
		$this->m_db = $db;
	}
	
	/**
	 * Adds a recipe into the database.
	 *
	 * @param $recipe, Recipe Object
	 * @return $addStatus, Boolean
	 */
	public function DoAddRecipe(Recipe $recipe)
	{
		$addStatus = true;
		if(!RecipeDAL::AddRecipe($recipe))
		{
			$addStatus = false;			
		}

		return $addStatus;
	}
	
	/**
	 * update a recipe in the database.
	 *
	 * @param $recipe, Recipe Object
	 * @return $editStatus, Boolean
	 */
	public function DoUpdateRecipe(Recipe $recipe)
	{
		$editStatus = true;
		if(!RecipeDAL::UpdateRecipe($recipe))
		{
			$editStatus = false;			
		}

		return $editStatus;
	}
	
	/**
	 * Delete a recipe that's in the database.
	 *
	 * @param $recipeID, user id
	 * @return $deleteStatus, Boolean
	 */
	public function DoDeleteRecipe($recipeID)
	{
		$deleteStatus = true;
		
		if(!RecipeDAL::DeleteRecipe($recipeID))
		{
			$deleteStatus = false;
		}

		return $deleteStatus;
	}
	
	/**
	 * Get a recipe from the database
	 * 
	 * @param $recipeID, user id
	 * @return the user
	 */
	public function GetRecipeByID($recipeID)
	{
		$recipe = RecipeDAL::GetRecipeByID($recipeID);
		if($recipe)
		{
			return $recipe;
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * Get a recipes from the database
	 * 
	 * @return the recipes
	 */
	public function GetRecipes()
	{
		$recipes = RecipeDAL::GetAllRecipes();
		if($recipes)
		{
			return $recipes;
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * look up if the inlogged user is the author of the recipe
	 * 
	 * @param $userInSession, the inlogged user
	 * @param $recipeAuthor, the recipe's author
	 * @return boolean
	 */
	public function IsAuthor(User $userInSession, $recipeAuthor)
	{
		if($userInSession->GetUserID() == $recipeAuthor)
		{
			return true;
		}
		else 
		{
			return false;
		}
	}
	
	public static function test(Database $db)
	{
		// Errormessages is saved in this array
		$errorMessages = array();
		$errorMessages[] = "RecipeModel Test";
		
		$sut = new RecipeModel($db);
		$recipeDAL = new RecipeDAL($db);
		
		/**
		 * Get all recipes
		 */
		$recipes = $sut->GetRecipes();
		if(count($recipes) != 6)
			$errorMessages[] = "Something is wrong with the GetRecipes() function.";
		
		/**
		 * Get recipes by ID
		 */
		$recipe = $sut->GetRecipeByID(13);
		if($recipe->GetRecipeID() != 13)
			$errorMessages[] = "Something is wrong with the GetRecipeByID() function.";
		
		/**
		 * Add a recipes
		 */
		$recipeToAdd = $sut->GetRecipeByID(13);
		$recipeToAdd->SetRecipeID(-1);
		if(!$sut->DoAddRecipe($recipeToAdd))
			$errorMessages[] = "Something is wrong with the DoAddRecipe() function.";
		
		/**
		 * Remove the recipe from above
		 */
		$recipes = $sut->GetRecipes();
		$index = count($recipes);
		$recipeToRemove = $recipes[$index-1];
		$recipeIDToRemove = $recipeToRemove->GetRecipeID();
		if(!$sut->DoDeleteRecipe($recipeIDToRemove))
			$errorMessages[] = "Something is wrong with the DoDeleteRecipe() function.";
		
		/**
		 * Update a recipe
		 */
		$recipeBeforeUpdate = $sut->GetRecipeByID(13);
		$recipeNameBeforeUpdate = $recipeBeforeUpdate->GetRecipeName();
		$recipeBeforeUpdate->SetRecipeName("Fiskbullens soppa");
		$sut->DoUpdateRecipe($recipeBeforeUpdate);
		$recipeAfterUpdate = $sut->GetRecipeByID(13);
		
		if($recipeAfterUpdate->GetRecipeName() == $recipeNameBeforeUpdate)
			$errorMessages[] = "Something is wrong with the DoUpdateRecipe() function.";
		
		$recipeAfterUpdate->SetRecipeName($recipeNameBeforeUpdate);
		$sut->DoUpdateRecipe($recipeAfterUpdate);
			
		/**
		 * isAuthor test
		 */
		$recipeToCheckAuthor = $sut->GetRecipeByID(13);
		$authorOfRecipe = $recipeToCheckAuthor->GetUserID();
		
		$userInfo = array(
						\Model\User::USERID => 50,
						\Model\User::USERNAME => "Fiskbullefisken",
						\Model\User::EMAIL => "Fiskbullefisken@hotmail.se",
						\Model\User::PASSWORD => "Fiskbullefisken",
						\Model\User::SKILL => 2,
						\Model\User::UPDATED => "2012-12-03 14:42:22",
						\Model\User::ISADMIN => 0
		);
		
		$user = new User($userInfo);
		if($sut->IsAuthor($user, $authorOfRecipe))
			$errorMessages[] = "Something is wrong with the IsAuthor() function.";
		
		return $errorMessages;
	}
}











