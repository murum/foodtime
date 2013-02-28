<?php

namespace Controller;

require_once('view/RecipeView.php');
require_once('model/RecipeModel.php');
require_once('model/UserModel.php');
require_once('model/object/Recipe.php');
require_once('model/object/User.php');
require_once('view/NavigationView.php');

class RecipeController
{
	private $m_recipeView;
	private $m_recipeModel;
	private $m_userModel;
	private $m_userInSession;
	private $m_output = "";
	public function __construct(\Model\Database $db)
	{
		$this->m_recipeView = new \View\RecipeView();
		$this->m_recipeModel = new \Model\RecipeModel($db);
		$this->m_userModel = new \Model\UserModel($db);
		$this->m_userInSession = \Model\User::GetUserSession();
	}
	
	public function DoControll()
	{		
		/**
		 * List recipes
		 */
		if(\View\NavigationView::GetRecipeQuery() == \View\NavigationView::START || \View\NavigationView::GetRecipeQuery() == \View\NavigationView::LISTNING)
		{
			$recipes = $this->m_recipeModel->GetRecipes();
			// Any recipes to show...
			if($recipes)
			{
				$this->m_output = $this->m_recipeView->DoRecipeMenu(isset($this->m_userInSession));
				$this->m_output .= $this->m_recipeView->DoRecipeList($recipes);
				
				// If the user choosed to show recipes after a severity
				if(\View\NavigationView::IsSeverityQuery())
				{
					// Which severity
					$severity = \View\NavigationView::GetSeverityQuery();
					
					// Do the recipe menu with a selected severity.
					$this->m_output = $this->m_recipeView->DoRecipeMenu(isset($this->m_userInSession), $severity);
					
					// If the severity is a severity that we accept.
					if($severity <= 5 && $severity >= 1)
					{
						// If no recipes matches
						try
						{
							$this->m_output .= $this->m_recipeView->DoSelectedRecipeList($recipes, $severity);
						}
						// show the user information about it.
						catch (\Exception $e)
						{
							\Common\Page::AddErrormessage($e->getMessage());
						}
					}
					// If the user choosed to match his skill level.
					else if($severity == \View\NavigationView::YOUR_SEVERITY)
					{
						$severity = $this->m_userInSession->GetSkill();
						$this->m_output .= $this->m_recipeView->DoSelectedRecipeList($recipes, $severity);
					}
					// Not a valid severity value.
					else 
					{
						\Common\Page::AddErrormessage(\Common\String::FAIL_SEVERITY_MATCH);
					}
				}
			}
			// No recipes to show..
			else
			{
				\Common\Page::AddErrormessage(\Common\String::FAIL_GET_RECIPES);					
			}
			// If user is logged in he can add a recipe
			if(isset($this->m_userInSession))
			{
				$this->m_output .= $this->m_recipeView->DoAddRecipeButton();
			}
		}

		/**
		 * Add recipes
		 */
		else if (\View\NavigationView::GetRecipeQuery() == \View\NavigationView::ADD)
		{
			// If user is logged in...
			if(isset($this->m_userInSession))
			{
				$this->m_output = $this->m_recipeView->DoAddRecipeForm();
				if($this->m_recipeView->TriedToAddRecipe())
				{
					// Validate the new recipe.
					if($this->m_recipeView->ValidateRecipe())
					{
						// If the recipe is valid.. add the information to a recipe information array.
						try
						{
							$recipeInfo = array(
								\Model\Recipe::USERID => $this->m_userInSession->GetUserID(),
								\Model\Recipe::RECIPENAME => $this->m_recipeView->GetRecipeName(),
								\Model\Recipe::INGREDIENT => $this->m_recipeView->GetRecipeIngredient(),
								\Model\Recipe::DESCRIPTION => $this->m_recipeView->GetRecipeDescription(),
								\Model\Recipe::SEVERITY => $this->m_recipeView->GetSeverity(),
							);
							// add the recipe
							$recipe = new \Model\Recipe($recipeInfo);
							if($this->m_recipeModel->DoAddRecipe($recipe))
							{
								// Add recipe succes..
								\Common\Page::AddSuccessmessage(\Common\String::SUCCESS_ADD_RECIPE);
							}
						}
						// Something went wrong in the add recipe... inform the user about it.
						catch(\Exception $e)
						{
							\Common\Page::AddErrormessage($e->getMessage());
						}
					}
					// Validation errors when the user tried to add a recipe.
					else
					{
						\Common\Page::AddErrorMessage($this->m_recipeView->DoErrorList($this->m_recipeView->GetErrorMessages()));
					}
				}
			}
			// if user ain't logged in.. show the user a errormessage.
			else 
			{
				\Common\Page::AddErrormessage(\Common\String::NOT_LOGGED_IN);
			}
		}

		/**
		 * Edit recipes
		 */
		else if (\View\NavigationView::GetRecipeQuery() == \View\NavigationView::EDIT)
		{
			//local variable
			$recipeID = \View\NavigationView::GetRecipeIDQuery();
			
			// If the user is logged in and the recipeID is set.
			if(isset($recipeID) && isset($this->m_userInSession))
			{
				// Get the recipe to edit
				$recipe = $this->m_recipeModel->GetRecipeByID($recipeID);
				
				// If the recipe author is the same as the user who is logged in.
				if($recipe->GetUserID() == $this->m_userInSession->GetUserID())
				{
					// set a session with the recipe to save the recipe ID.
					\Model\Recipe::SetRecipeSession($recipe);
					
					$this->m_output = $this->m_recipeView->DoEditRecipeForm($recipe);
					
					// Did the user try to save the recipe
					if($this->m_recipeView->TriedToEditRecipe())
					{
						// Validate the recipe values.
						if($this->m_recipeView->ValidateRecipe())
						{
							try
							{
								// Get the recipe stored in the session.
								$recipeInSession = \Model\Recipe::GetRecipeSession();
								
								$recipeInfo = array(
									\Model\Recipe::RECIPEID => $recipeInSession->GetRecipeID(),
									\Model\Recipe::RECIPENAME => $this->m_recipeView->GetRecipeName(),
									\Model\Recipe::INGREDIENT => $this->m_recipeView->GetRecipeIngredient(),
									\Model\Recipe::DESCRIPTION => $this->m_recipeView->GetRecipeDescription(),
									\Model\Recipe::SEVERITY => $this->m_recipeView->GetSeverity(),
								);
								// make a recipe object
								$recipe = new \Model\Recipe($recipeInfo);
								// update the recipe.
								if($this->m_recipeModel->DoUpdateRecipe($recipe))
								{
									// update success
									\Common\Page::AddSuccessmessage(\Common\String::SUCCESS_EDIT_RECIPE);
								}
							}
							// error in the update process.
							catch(\Exception $e)
							{
								\Common\Page::AddErrormessage($e->getMessage());
							}
						}
						// Validation error in the update form. 
						else 
						{
							\Common\Page::AddErrorMessage($this->m_recipeView->DoErrorList($this->m_recipeView->GetErrorMessages()));
						}
					}
				}
				// Show the user he/she's not the author of the recipe
				else
				{
					\Common\Page::AddErrormessage(\Common\String::UPDATE_RECIPE_NOT_YOURS);
				}
			}
			// No recipeID in the query.. show the user errormessage.
			else if(!isset($recipeID))
			{
				\Common\Page::AddErrormessage(\Common\String::NO_RECIPE_ID);
			}
			// user aint logged in.. show the user errormessage..
			else
			{
				\Common\Page::AddErrormessage(\Common\String::NOT_LOGGED_IN);
			}
		}

		/**
		 * Delete recipes
		 */
		else if (\View\NavigationView::GetRecipeQuery() == \View\NavigationView::DELETE)
		{
			// Localvariable..
			$recipeID = \View\NavigationView::GetRecipeIDQuery();
			
			// If the user is logged in and the recipeID is set.
			if(isset($recipeID) && isset($this->m_userInSession))
			{
				// store the recipe
				$recipe = $this->m_recipeModel->GetRecipeByID($recipeID);
				// if recipe exists
				if($recipe)
				{
					// If the recipe author is the same as the inlogged user.
					if($recipe->GetUserID() == $this->m_userInSession->GetUserID())
					{
						// Try to delete the recipe.
						try
						{
							if($this->m_recipeModel->DoDeleteRecipe($recipeID))
							{
								// Delete success..
								\Common\Page::AddSuccessmessage(\Common\String::SUCCESS_DELETE_RECIPE);
							}
						}
						// Error in the delete process
						catch(\Exception $e)
						{
							\Common\Page::AddErrormessage($e->getMessage());
						}
					}
					// Tried to delete a recipe that's not yours.. show errormessage..
					else
					{
						\Common\Page::AddErrormessage(\Common\String::DELETE_RECIPE_NOT_YOURS);
					}
				}
				// THe recipes doesn't exist... show error message..
				else 
				{
					\Common\Page::AddErrormessage(\Common\String::RECIPE_DOES_NOT_EXIST);
				}
			}
			// No recips id in the query.. Show errormessage.. 
			else if(!isset($recipeID))
			{
				\Common\Page::AddErrormessage(\Common\String::NO_RECIPE_ID);
			}
			// The user aint logged in... show errormessage..
			else
			{
				\Common\Page::AddErrormessage(\Common\String::NOT_LOGGED_IN);
			}
		}
		
		/**
		 * Show detailed recipe
		 */
		else
		{
			$recipeID = $this->m_recipeView->GetRecipeID();
			$recipe = $this->m_recipeModel->GetRecipeByID($recipeID);
			
			// if the recipe Exists
			if($recipe)
			{
				if(isset($this->m_userInSession))
				{
					// store a boolean to see if the user is the author
					$isAdmin = ($this->m_userInSession->GetIsAdmin() == \Common\String::IS_ADMIN) ? true : false;
					$isAuthor = $this->m_recipeModel->IsAuthor($this->m_userInSession, $recipe->GetUserID());
				}
				else {
					// if not logged in you're defintly not the author
					$isAuthor = false;
				}
				
				// store the authors username
				$user = $this->m_userModel->GetUserByID($recipe->GetUserID());
				$recipe->SetAuthor($user->GetUsername());
				
				// make the recipe
				$this->m_output = $this->m_recipeView->DoRecipe($recipe, $isAuthor, $isAdmin);	
			}
			// Not a valid recipe ID in the query..
			else
			{
				\Common\Page::AddErrormessage(\Common\String::FAIL_GET_RECIPE);				
			}
		}
		
		return $this->m_output;
	}
}