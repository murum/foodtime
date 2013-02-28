<?php

namespace Model;

require_once ('model/object/Recipe.php');

class RecipeDAL
{
	const table_name = "recipe";

	private static $m_db;

	public function __construct(Database $db)
	{
		self::$m_db = $db;
	}

	/**
	 * Get recipes from the databse
	 * 
	 * @return array .. A array with recipe objects
	 */
	public static function GetAllRecipes()
	{
		$table = RecipeDAL::table_name;

		$query = "SELECT * FROM $table";
		$stmt = self::$m_db->Prepare($query);

		try
		{
			$recipesInfo = self::$m_db->SelectAllTable($stmt);
		}
		catch (exception $e)
		{
			return false;
		}

		$recipes = array();
		foreach($recipesInfo as $recipeInfo)
		{
			$recipes[] = new Recipe($recipeInfo);
		}

		return $recipes;
	}

	/**
	 * Get Recipe By ID
	 * 
	 * @param $recipeID, a recipes ID
	 * @return Recipe, a recipe object
	 */
	public static function GetRecipeByID($recipeID)
	{
		$table = RecipeDAL::table_name;
		$query = "SELECT * FROM $table WHERE " . Recipe::RECIPEID . " = ?";

		$stmt = self::$m_db->Prepare($query);

		$stmt->bind_param("i", $recipeID);

		$userInfo = array();

		try
		{
			$recipeInfo = self::$m_db->SelectAll($stmt);
			if(!isset($recipeInfo))
			{
				return false;
			}
		}
		catch (exception $e)
		{
			return false;
		}

		return new Recipe($recipeInfo);
	}

	/**
	 * Get a recipe by name
	 * 
	 * @param $recipename, a recipes recipename
	 * @return Recipe, a recipe object
	 */
	public static function GetRecipeByName($recipename)
	{
		$table = RecipeDAL::table_name;
		$query = "SELECT * FROM $table WHERE " . Recipe::RECIPENAME . " = ?";

		$stmt = self::$m_db->Prepare($query);

		$stmt->bind_param("s", $recipename);

		$userInfo = array();

		try
		{
			$recipeInfo = self::$m_db->SelectAll($stmt);
		}
		catch (exception $e)
		{
			return false;
		}

		return new Recipe($recipeInfo);
	}

	/**
	 * Adds an recipe to the database
	 *
	 * @param $recipe , Recipe object
	 * @return bool
	 */
	public static function AddRecipe(Recipe $recipe)
	{
		$table = RecipeDAL::table_name;
		$sqlQuery = "INSERT INTO $table ("
							. Recipe::USERID . ", "
							. Recipe::RECIPENAME . ", " 
							. Recipe::INGREDIENT . ", " 
							. Recipe::DESCRIPTION . ", " 
							. Recipe::SEVERITY . ") 
						VALUES (?, ?, ?, ?, ?)";

		$stmt = self::$m_db->Prepare($sqlQuery);

		$userId = $recipe->GetUserID();
		$recipeName = $recipe->GetRecipeName();
		$recipeIngredient = $recipe->GetRecipeIngredient();
		$recipeDescription = $recipe->GetRecipeDescription();
		$severity = $recipe->GetSeverity();

		$stmt->bind_param("isssi", $userId, $recipeName, $recipeIngredient, $recipeDescription, $severity);

		try
		{
			$recipeID = self::$m_db->Insert($stmt);
		}
		catch (exception $e)
		{
			return false;
		}

		return $recipeID;
	}

	/**
	 * Update a recipes information
	 * 
	 * @param $recipe , a Recipe object
	 * @return bool
	 */
	public static function UpdateRecipe(Recipe $recipe)
	{
		$table = RecipeDAL::table_name;
		
		$sqlQuery = "UPDATE $table SET " 
						. Recipe::RECIPENAME . "=?, " 
						. Recipe::INGREDIENT . "=?, " 
						. Recipe::DESCRIPTION . "=?, " 
						. Recipe::SEVERITY . "=? 
						WHERE " . Recipe::RECIPEID . "=?";
		
		$stmt = self::$m_db->Prepare($sqlQuery);
		
		$recipeName = $recipe->GetRecipeName();
		$recipeIngredient = $recipe->GetRecipeIngredient();
		$recipeDescription = $recipe->GetRecipeDescription();
		$severity = $recipe->GetSeverity();
		$recipeID = $recipe->GetRecipeID();
		
		$stmt->bind_param("sssii", $recipeName, $recipeIngredient, $recipeDescription, $severity, $recipeID);
		
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
	 * Delete a recipe from the database
	 *
	 * @return bool
	 */
	public static function DeleteRecipe($recipeID)
	{
		$table = RecipeDAL::table_name;
		$sqlQuery = "DELETE FROM $table WHERE " . Recipe::RECIPEID . "=?";

		$stmt = self::$m_db->Prepare($sqlQuery);

		$stmt->bind_param("i", $recipeID);

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
		$errorMessages[] = "RecipeDAL Test";

		$sut = new RecipeDAL($db);

		/**
		 * Test to get a recipe that exists and check the ID
		 * TEST #1
		 */
		$recipe = $sut->GetRecipeByName("BenganBengan2");
		$recipeID = $recipe->GetRecipeID();
		if($recipeID != 17)
		{
			$errorMessages[] = "GetRecipeByName failed (on line: " . __LINE__ . ")";
		}

		/**
		 * Test to get a recipe from the GetRecipeByID
		 * TEST #2
		 */
		if(!$sut->GetRecipeByID(13))
		{
			$errorMessages[] = "GetRecipeByID failed (on line: " . __LINE__ . ")";
		}
		
		/**
		 * Test to update a recipe
		 * TEST #3
		 */
		if(!$sut->UpdateRecipe($recipe))
		{
			$errorMessages[] = "UpdateRecipe failed (on line: " . __LINE__ . ")";
		}

		/**
		 * Add a recipe with a unique recipe name
		 * TEST #4
		 */
		$recipes = $sut->GetAllRecipes();
		try
		{
			$recipeInfo = array(
				Recipe::USERID => 2,
				Recipe::RECIPENAME => 'BenganBengan' . count($recipes),
				Recipe::INGREDIENT => 'Bengan22Bengan22Bengan22Bengan22Bengan22',
				Recipe::DESCRIPTION => 'BengaBenganBenganBengann@telia.com',
				Recipe::SEVERITY => '2',
			);
			$recipe = new Recipe($recipeInfo);
			$recipeIDToRemove = $sut->AddRecipe($recipe); // This recipe will be deleted in TEST #5
			// Testsuccess
		}
		catch (\Exception $e)
		{
			$errorMessages[] = "AddRecipe failed (on line: " . __LINE__ . ")";
		}

		/**
		 * Delete a recipe
		 * TEST #5
		 */
		if(!$sut->DeleteRecipe($recipeIDToRemove))
		{
			$errorMessages[] = "DeleteRecipe failed (on line: " . __LINE__ . ")";
		}

		/**
		 * Delete a recipe that not exists
		 * TEST #6
		 */
		if($sut->DeleteRecipe($recipeIDToRemove))
		{
			$errorMessages[] = "DeleteRecipe failed (on line: " . __LINE__ . ")";
		}

		return $errorMessages;
	}

}
