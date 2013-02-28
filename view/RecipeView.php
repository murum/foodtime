<?php

namespace View;

class RecipeView
{
	private $m_validator;
	private $m_errorMessages = array();

	public function __construct()
	{
		$this->m_validator = new \Model\Validator();
	}
	
	public function SetErrorMessages()
	{
		foreach($this->m_validator->GetErrorMessages() as $errorMessage)
		{
			$this->AddErrorMessage($errorMessage);
		}
	}

	public function AddErrorMessage($error)
	{
		$this->m_errorMessages[] = $error;
	}

	/**
	 * Get all error messages
	 * 
	 * @return array, error messages
	 */
	public function GetErrorMessages()
	{
		return $this->m_errorMessages;
	}
	
	/**
	 * Do errorlist
	 *
	 * @param $errorMessages, string array
	 */
	public function DoErrorList($errorMessages)
	{
		foreach($errorMessages as $message)
		{
			\Common\Page::AddErrorMessage($message);
		}
	}
	
	/**
	 * Get the recipeID from the recipe query
	 * @return $recipeID .. the value of the query
	 */
	public function GetRecipeID()
	{
		return NavigationView::IsRecipeQuery() ? NavigationView::GetRecipeQuery() : NULL;
	}

	/**
	 * Get the recipeID from the recipeID Query
	 * @return $recipeID , the value of the query
	 */
	public function GetRecipeIDQuery()
	{
		return NavigationView::IsRecipeIDQuery() ? NavigationView::GetRecipeIDQuery() : NULL;
	}

	/**
	 * Check if the user tried to add a recipe
	 *
	 * @return boolean
	 */
	public function TriedToAddRecipe()
	{
		return isset($_POST[\Common\String::ADD_RECIPE_SUBMIT]);
	}

	/**
	 * Check if the user tried to add a recipe
	 *
	 * @return boolean
	 */
	public function TriedToEditRecipe()
	{
		return isset($_POST[\Common\String::EDIT_RECIPE_SUBMIT]);
	}

	/**
	 * Get recipe name
	 */
	public function GetRecipeName()
	{
		return isset($_POST[\Common\String::RECIPE_NAME]) ? $_POST[\Common\String::RECIPE_NAME] : NULL;
	}

	/**
	 * Get recipe ingredients
	 */
	public function GetRecipeIngredient()
	{
		return isset($_POST[\Common\String::RECIPE_INGREDIENT]) ? $_POST[\Common\String::RECIPE_INGREDIENT] : NULL;
	}

	/**
	 * Get recipe description
	 */
	public function GetRecipeDescription()
	{
		return isset($_POST[\Common\String::RECIPE_DESCRIPTION]) ? $_POST[\Common\String::RECIPE_DESCRIPTION] : NULL;
	}

	/**
	 * Get recipe severity
	 */
	public function GetSeverity()
	{
		return isset($_POST[\Common\String::RECIPE_SEVERITY]) ? $_POST[\Common\String::RECIPE_SEVERITY] : NULL;
	}

	/**
	 * Validate a recipe
	 * 
	 * @return boolean
	 */
	public function ValidateRecipe()
	{
		$this->m_validator->ValidateRecipeName($this->GetRecipeName());
		$this->m_validator->ValidateIngredient($this->GetRecipeIngredient());
		$this->m_validator->ValidateDescription($this->GetRecipeDescription());
		
		$this->SetErrorMessages();

		if(count($this->GetErrorMessages()) != 0)
		{
			return false;
		}
		
		return true;
	}
	/**
	 * Recipe layout
	 *
	 * @return $html, generated html code
	 */
	public function DoRecipe(\Model\Recipe $recipe, $isAuthor, $isAdmin)
	{
		$html = "
			<h2>" . $recipe->GetRecipeName() . "</h2>
			<dl>
				<dt>Ingredienser</dt>
				<dd>" . nl2br($recipe->GetRecipeIngredient()) . "</dd>
				<dt>Instruktioner</dt>
				<dd>" . nl2br($recipe->GetRecipeDescription()) . "</dd>
				<dt>Svårighetsgrad</dt>
					<dd><meter value=\"" . $recipe->GetSeverity() . "\" min=\"0\" max=\"5\">/meter></dd>
			</dl>
			<p>Skapad av: " . $recipe->GetAuthor() . "</p>
		";
		if($isAuthor || $isAdmin)
		{
			$html .= "<a href=\"" . NavigationView::GetRecipeUpdateLink($recipe->GetRecipeID()) . "\"><button class=\"left secondButton\">" . \Common\String::EDIT_RECIPE_TEXT . "</button></a>";
			$html .= "<a href=\"" . NavigationView::GetRecipeDeleteLink($recipe->GetRecipeID()) . "\"><button class=\"left secondButton\">" . \Common\String::DELETE_RECIPE_TEXT . "</button></a>";
		}
		return $html;
	}

	/**
	 * Form to add a recipe
	 *
	 * @return $html, generated html code.
	 */
	public function DoAddRecipeForm()
	{
		$recipeName = ($this->GetRecipeName() == null) ? "" : $this->GetRecipeName();
		$recipeIngredient = ($this->GetRecipeIngredient() == null) ? "" : $this->GetRecipeIngredient();
		$recipeDescription = ($this->GetRecipeDescription() == null) ? "" : $this->GetRecipeDescription();
		$html = "
			<form class=\"addRecipe\" method=\"post\">
				<label for=\"" . \Common\String::RECIPE_NAME . "\">" . \Common\String::RECIPE_NAME_TEXT . "</label>
				<input type=\"text\" id=\"" . \Common\String::RECIPE_NAME . "\" name=\"" . \Common\String::RECIPE_NAME . "\" value=\"$recipeName\" />
				<label for=\"" . \Common\String::RECIPE_INGREDIENT . "\">" . \Common\String::RECIPE_INGREDIENT_TEXT . "</label>
				<textarea id=\"" . \Common\String::RECIPE_INGREDIENT . "\" name=\"" . \Common\String::RECIPE_INGREDIENT . "\">$recipeIngredient</textarea>
				<label for=\"" . \Common\String::RECIPE_DESCRIPTION . "\">" . \Common\String::RECIPE_DESCRIPTION_TEXT . "</label>
				<textarea id=\"" . \Common\String::RECIPE_DESCRIPTION . "\" name=\"" . \Common\String::RECIPE_DESCRIPTION . "\">$recipeDescription</textarea>
				<label for=\"" . \Common\String::RECIPE_SEVERITY . "\">" . \Common\String::RECIPE_SEVERITY_TEXT . "</label>
				<select name=\"" . \Common\String::RECIPE_SEVERITY . "\" id=\"" . \Common\String::RECIPE_SEVERITY . "\">";
			for($i = 1; $i <= 5; $i++)
				{
					$html .= "<option value=\"$i\">" . \Common\String::$recipeSeverityText[$i] . "</option>";
				}
			$html .= "
				</select><br />
				<input class=\"left\" type=\"submit\" name=\"" . \Common\String::ADD_RECIPE_SUBMIT . "\" value=\"" . \Common\String::ADD_RECIPE_TEXT . "\" />
			</form>";
		return $html;
	}

	/**
	 * Form to add a recipe
	 *
	 * @return $html, generated html code.
	 */
	public function DoEditRecipeForm(\Model\Recipe $recipe)
	{
		$selectedSeverity = $recipe->GetSeverity();
		$html = "
			<form class=\"editRecipe\" method=\"post\">
				<label for=\"" . \Common\String::RECIPE_NAME . "\">" . \Common\String::RECIPE_NAME_TEXT . "</label>
				<input type=\"text\" value=\"" . $recipe->GetRecipeName() . "\" id=\"" . \Common\String::RECIPE_NAME . "\" name=\"" . \Common\String::RECIPE_NAME . "\" />
				<label for=\"" . \Common\String::RECIPE_INGREDIENT . "\">" . \Common\String::RECIPE_INGREDIENT_TEXT . "</label>
				<textarea id=\"" . \Common\String::RECIPE_INGREDIENT . "\" name=\"" . \Common\String::RECIPE_INGREDIENT . "\">" . $recipe->GetRecipeIngredient() . "</textarea>
				<label for=\"" . \Common\String::RECIPE_DESCRIPTION . "\">" . \Common\String::RECIPE_DESCRIPTION_TEXT . "</label>
				<textarea id=\"" . \Common\String::RECIPE_DESCRIPTION . "\" name=\"" . \Common\String::RECIPE_DESCRIPTION . "\">" . $recipe->GetRecipeDescription() . "</textarea>
				<label for=\"" . \Common\String::RECIPE_SEVERITY . "\">" . \Common\String::RECIPE_SEVERITY_TEXT . "</label>
				<select name=\"" . \Common\String::RECIPE_SEVERITY . "\" id=\"" . \Common\String::RECIPE_SEVERITY . "\">";
		// Write option to each severitylevel.
		for($i = \Common\String::SEVERITY_MIN; $i <= \Common\String::SEVERITY_MAX; $i++)
		{
			// If the severity is the selected to the recipe make it start as selected.
			if($i == $selectedSeverity)
			{
				$html .= "<option selected=\"selected\" value=\"$i\">" . \Common\String::$recipeSeverityText[$i] . "</option>";
			}
			// else not selected from start.
			else
			{
				$html .= "<option value=\"$i\">" . \Common\String::$recipeSeverityText[$i] . "</option>";
			}
		}
		$html .= "
				</select><br />
				<input class=\"left\" type=\"submit\" name=\"" . \Common\String::EDIT_RECIPE_SUBMIT . "\" value=\"" . \Common\String::EDIT_RECIPE_SUBMIT_TEXT . "\" />
			</form>";
		return $html;
	}

	/**
	 * Make HTML for a recipe add button
	 *
	 * @return $html, generated html code.
	 */
	public function DoAddRecipeButton()
	{
		$html = "<a href=\"" . NavigationView::GetRecipeAddLink() . "\"><button class=\"left\">" . \Common\String::ADD_RECIPE_TEXT . "</button></a>";
		return $html;
	}

	/**
	 * Do the recipe menu
	 * 
	 * @param $isLoggedIn.. if the user is logged in show the Match your Level.
	 * @param $chosedSeverity... if the user choosed a severity make that severity button selected.
	 */
	public function DoRecipeMenu($isLoggedIn, $chosedSeverity = -1)
	{
		$html = "
			<ul class=\"left severity\">";
		// if the user is logged in show the Match your Level.
		if($isLoggedIn)
		{
			$html .= "<li><a href=\"" . NavigationView::GetRecipeSeverityLink(\View\NavigationView::YOUR_SEVERITY) . "\"><button class=\"recipeMatchYourLevel\">Matcha din nivå</button></a></li>";
		}
		
		// Make buttons for each severity level.
		for($severity = \Common\String::SEVERITY_MIN; $severity <= \Common\String::SEVERITY_MAX; $severity++)
		{
			// if the severity == the chosen one make that button visually selected.
			if($severity == $chosedSeverity)
				$html .= "<li><a href=\"" . NavigationView::GetRecipeSeverityLink($severity) . "\"><button class=\"recipeSeverityButton chosedSeverity\">" . \Common\String::$severityButtonText[$severity] . "</button></a></li>";
			else 
				$html .= "<li><a href=\"" . NavigationView::GetRecipeSeverityLink($severity) . "\"><button class=\"recipeSeverityButton\">" . \Common\String::$severityButtonText[$severity] . "</button></a></li>";
		}
		$html .= "
			</ul>";
		return $html;		
	}

	/**
	 * List all recipes
	 *
	 * @return $html, generated html code
	 */
	public function DoRecipeList($recipes)
	{
		$html = "
			<ul class=\"recipeList\">";
		// do links to all recipes. 
		foreach($recipes as $recipe)
		{
			$html .= "<li><a href=\"" . NavigationView::GetRecipeLink($recipe->GetRecipeID()) . "\"><span class=\"severity" . $recipe->GetSeverity() . "\">" . $recipe->GetRecipeName() . "</span></a></li>";
		}
		$html .= "
			</ul>
		";
		return $html;
	}
	/**
	 * List all recipes with a selected severity
	 * 
	 * @return $html, generated html code 
	 */
	public function DoSelectedRecipeList($recipes, $severity)
	{
		$recipeToShow = null;
		$html = "<ul class=\"recipeList\">";
		foreach($recipes as $recipe)
		{
			if(NavigationView::GetSeverityQuery() == NavigationView::YOUR_SEVERITY)
			{
				if($recipe->GetSeverity() <= $severity)
				{
					$recipeToShow[] = $recipe;
				}
			}
			else {
				if($recipe->GetSeverity() == $severity)
				{
					$recipeToShow[] = $recipe;
				}
			}
		}
		if(count($recipeToShow) > 0)
		{
			foreach($recipeToShow as $recipe)
			{
				$html .= "<li><a href=\"" . NavigationView::GetRecipeLink($recipe->GetRecipeID()) . "\"><span class=\"severity" . $recipe->GetSeverity() . "\">" . $recipe->GetRecipeName() . "</span></a></li>";
			}
		}
		else {
			throw new \Exception(\Common\String::NO_RECIPES_MATCHES);
		}
		$html .= "</ul>";
		return $html;
	}
}
