<?php

namespace Common;

class String
{
	// Sessions & Cookies
	const SESSION_LOGGEDIN = "loggedin";
	const SESSION_RECIPE = "recipe";
	const COOKIE_USER = "cookie_user";
	const COOKIE_LIFETIME = "1800";
	
	// User
	const USERNAME = "username";
	const EMAIL = "email";
	const PASSWORD = "password";
	const REPEAT_PASSWORD = "repeatPassword";
	const REMEMBER = "remember";
	const SKILL = "skill";
		const SKILL_MAX = "5";
		const SKILL_MIN = "1";
	const IS_ADMIN = 1;
	const IS_NOT_ADMIN = 0;
	
	// Recipe
	const RECIPE_ID = "recipeID";
	const RECIPE_NAME = "recipeName";
	const RECIPE_INGREDIENT = "recipeIngredient";
	const RECIPE_DESCRIPTION = "recipeDescription";
	const RECIPE_SEVERITY = "severity";
		const SEVERITY_MIN = 1;
		const SEVERITY_MAX = 5;
	
	// Submits
	const LOGIN_SUBMIT = "logInSubmit";
	const LOGOUT_SUBMIT = "logOutSubmit";
	const REGISTER_SUBMIT = "registerSubmit";
	const EDIT_PROFILE_SUBMIT = "editProfileSubmit";
	const DELETE_PROFILE_SUBMIT = "deleteProfileSubmit";
	const ADD_RECIPE_SUBMIT = "addRecipeSubmit";
	const EDIT_RECIPE_SUBMIT = "editRecipeSubmit";
	
	// Strings a user can see
	const LOGIN_SUBMIT_TEXT = "Logga in";
	const LOGOUT_SUBMIT_TEXT = "Logga ut";
	const REGISTER_SUBMIT_TEXT = "Registrera";
	const EDIT_PROFILE_SUBMIT_TEXT = "Spara Profil";
	const DELETE_PROFILE_SUBMIT_TEXT = "Avregistrera mig";
	const ADD_RECIPE_TEXT = "Lägg till recept";
	const EDIT_RECIPE_SUBMIT_TEXT = "Spara recept";
		// User labels & more
		const USERNAME_TEXT = "Användarnamn";
		const PASSWORD_TEXT = "Lösenord";
		const PASSWORD_REPEAT_TEXT = "Lösenord igen";
		const REMEMBER_TEXT = "Kom ihåg mig";
		const EMAIL_TEXT = "Email";
		const SKILL_TEXT = "Kunskapsnivå";
		const REGISTER_HERE_TEXT = "Registrera dig";
		public static $skillText = array(1 => "Rätt kass", 2 => "Duglig", 3 => "Matälskare", 4 => "Krögare", 5 => "Mästerkock");
		// Recipe labels & more
		const RECIPE_NAME_TEXT = "Receptnamn";
		const RECIPE_INGREDIENT_TEXT = "Ingredienser";
		const RECIPE_DESCRIPTION_TEXT = "Instruktioner";
		const RECIPE_SEVERITY_TEXT = "För att klara av detta recept bör du kunna";
		public static $recipeSeverityText = array(1 => "öppna kylskåpet", 2 => "koka tevatten", 3 => "skala potatis", 4 => "filéa en fisk", 5 => "vinna sveriges mästerkock");
		public static $severityButtonText  = array(1 => "Superlätt", 2 => "Lätt", 3 => "Medelsvårt", 4 => "Svårt", 5 => "Supersvårt");
		const EDIT_RECIPE_TEXT = "Editera recept";
		const DELETE_RECIPE_TEXT = "Ta bort recept";
		// Error messages
		const NOT_LOGGED_IN = "Det krävs inloggning för denna sida.";
		const NO_RECIPE_ID = "Det krävs ett receptID för att editera ett recept.";
		const NO_RECIPES_MATCHES = "Det finns inga recept i den valda kategorin";
		const UPDATE_RECIPE_NOT_YOURS = "Receptet du försökte editera är inte skapat av dig.";
		const DELETE_RECIPE_NOT_YOURS = "Receptet du försökte ta bort är inte skapat av dig.";
		const RECIPE_DOES_NOT_EXIST = "Receptet finns inte.";
		const NORIGHTS_ADMIN = "Du är inte administratör.";
		const WRONG_PASSWORD_OR_USERNAME = "Felaktigt lösenord eller användarnamn";
		const FAIL_GET_USER_PROFILE = "Någonting gick fel med visningen av profil";
		const FAIL_GET_USERS = "Det gick inte att hämta alla användare.";
		const FAIL_GET_RECIPE = "Det gick inte att visa de valda receptet";
		const FAIL_GET_RECIPES = "Det fanns inga recept att hämta.";
		const FAIL_SEVERITY_MATCH = "Det finns inga recept som matchar svårighetsgraden";
		// Success Messages
		const LOGIN_SUCCESS = "Du loggades in.";
		const REGISTER_SUCCESS = "Du registrerades.";
		const UPDATE_PROFILE_SUCCESS = "Du uppdaterade din profil.";
		const DELETE_PROFILE_SUCCESS = "Du avregistrerades.";
		const RIGHTS_ADMIN = "Du är administratör.";
		const SUCCESS_ADD_RECIPE = "Du skapade ett recept";
		const SUCCESS_EDIT_RECIPE = "Du uppdaterade receptet";
		const SUCCESS_DELETE_RECIPE = "Du tog bort receptet";
		// Validator errors
		const USERNAME_OR_EMAIL_WITH_TAG = "Ditt användarnamn eller email innehåller ogiltiga tecken.";
		const STARTLETTER_RECIPENAME = "Receptnamnet måste börja med en bokstav";
		const STARTLETTER_INGREDIENT = "Ingrediensen måste börja med en bokstav";
		const STARTLETTER_DESCRIPTION = "Instruktionen måste börja med en bokstav";
		const RECIPENAME_LENGTH = "Receptnamnet måste vara minst 5 tecken och max 75 tecken långt";
		const INGREDIENT_LENGTH = "Ingrediensen måste vara minst 21 tecken och max 4000 tecken långt och får bara innehålla \"a-ö, 0-9 samt .,:;\"";
		const DESCRIPTION_LENGTH = "Instruktionerna måste vara minst 21 tecken och max 4000 tecken långt och får bara innehålla \"a-ö, 0-9 samt .,:;\"";
		const EMAIL_FORMAT = "Felaktigt inmatad Emailadress";
		const USERNAME_EXISTS = "Användarnamnet finns redan";
		const USERNAME_NOT_NULL = "Användarnamnet får inte vara null.";
		const USERNAME_LENGTH = "Användarnamnet måste vara längre än tre tecken och kortare än 51 tecken";
		const PASSWORD_FORMAT = "Lösenordet måste innehålla minst en versal, en gemen och en siffra och vara minst åtta tecken långt.";
		const REPEAT_PASSWORD_NOT_MATCH = "Lösenorden matchar inte varandra";
		const PASSWORD_MATCH_UPDATE_PROFILE = "Lösenordet matchar inte det som du loggade in med.";
		const SKILL_NAN = "Värdet för din kunskapsnivå är inte en siffra.";
		const SKILL_TO_HIGH = "Värde för din kunskapsnivå är för högt (Max: 1).";
		const SKILL_TO_LOW = "Värde för din kunskapsnivå är för lågt (Min: 1).";
		// Links
		const MY_PROFILE_LINK = "Min profil";
		const EDIT_MY_PROFILE_LINK = "Inställningar";
			// Menu
			const HOME_LINK_TEXT = "Hem";
			const RECIPE_LINK_TEXT = "Recept";
			const USER_LINK_TEXT = "Användare";
			const ADMIN_LINK_TEXT = "Admin";
		// Information Text
		const DELETE_PROFILE_TEXT = "Klicka på knappen här nedanför för att bekräfta din avregistrering";
}
