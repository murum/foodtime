<?php

session_start();

// View
require_once('view/NavigationView.php');
require_once('common/Page.php');
require_once('common/String.php');
require_once('view/MasterView.php');

// Model
require_once('model/database/Database.php');
require_once('model/database/DBSettings.php');
require_once('model/crypt/Krypter.php');
require_once('model/validate/Validator.php');

// Controller
require_once('controller/AdminController.php');
require_once('controller/AuthController.php');
require_once('controller/RecipeController.php');
require_once('controller/UserController.php');

class MasterController 
{
	private static $m_title = "Foodtime";	
	/**
	 * What happens in the application
	 */
	public static function doControll() {
		
		// Page
		$page = new \Common\Page();
		$page->AddStylesheet('/frontend/css/style.css');
		$page->AddJavascript('http://code.jquery.com/jquery-latest.js');
		$page->AddJavascript('/frontend/js/main.js');
		
		// Database
		$dbSettings = new \Model\DBSettings();
  		$db = new \Model\Database();
		$db->Connect($dbSettings);
		
		// DAL
		$userDAL = new \Model\UserDAL($db);
		$recipeDAL = new \Model\RecipeDAL($db);
		
		// Instances
		$masterView = new \View\MasterView();
		$authController = new \Controller\AuthController($db);
		$recipeController = new \Controller\RecipeController($db);
		$adminController = new \Controller\AdminController($db);
		$userController = new \Controller\UserController($db);
		
		/**
		 * Standard views
		 */
		self::$m_title = $masterView->DoSiteTitle();
		$page->SetTitle(self::$m_title);
		$bodyHeader = $masterView->DoHeader();
		$bodyAuth = $authController->DoAuthControll();
		$bodyFooter = $masterView->DoFooter();
		$bodyContentRight = $masterView->DoSidebar();
		
		if(\View\NavigationView::IsAdminQuery())
		{
			$bodyNavigation = \View\NavigationView::GetAdminNavigation();
			$bodyContentLeft = $adminController->DoControll();
		}
		else if(\View\NavigationView::IsUserQuery() || \View\NavigationView::IsProfileQuery())
		{
			$bodyNavigation = \View\NavigationView::GetUserNavigation();
			$bodyContentLeft = $userController->DoControll();
		}
		else if(\View\NavigationView::IsRecipeQuery())
		{
			$bodyNavigation = \View\NavigationView::GetRecipeNavigation();
			$bodyContentLeft = $recipeController->DoControll();
		}
		else if(\View\NavigationView::IsRegisterQuery())
		{
			$bodyNavigation = \View\NavigationView::GetStartNavigation();
			$bodyContentLeft = $authController->DoRegisterControll();
		}
		else 
		{
			$bodyNavigation = \View\NavigationView::GetStartNavigation();
			$bodyContentLeft = $masterView->DoMainContent();
		}
		
		$db->Close();
		
		return $page->GenerateHTML5Page($bodyHeader, $bodyAuth, $bodyNavigation, $bodyContentLeft, $bodyContentRight, $bodyFooter);
	}
}

echo MasterController::doControll();
