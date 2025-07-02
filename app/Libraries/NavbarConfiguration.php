<?php
namespace App\Libraries;
use Config\Services;

 
	class NavbarConfiguration{
		public static function get_navbar($role_name){ 
	    	$routes_permission = Services::getRoleBasedRoutes($role_name);
	        return NavbarConfiguration::set_navbar($routes_permission, $role_name); 
	    } 

	    public static function set_navbar($routes, $role_name){  
	        $all_menus = NavbarConfiguration::get_navbar_menus($role_name);
            $accessable_menus = [];
            foreach ($routes as $key) { 
                if(isset($all_menus[$key])){
                    array_push($accessable_menus, $all_menus[$key]);
                }
            }
	        return $accessable_menus;
	    }

		public static function get_navbar_menus($role_name){
			if($role_name == 'admin'){
				return NavbarConfiguration::get_admin_navbar_menus();
			}else if($role_name == 'account'){
				return NavbarConfiguration::get_account_navbar_menus();
			}
		}

		public static function get_admin_navbar_menus(){
			return [
				'home' => new Menu('home', 'Home', '/home', false, 'fa-home'),
				'registrations' => new Menu('registrations', 'Registrations', '/registrations', false, 'fa-file'),
				'ncet-applications' => new Menu('ncet-applications', 'NCET-Applications', '/ncet-applications', false, 'fa-file'),

				'counselling' => new Menu('counselling', 'Counselling', '', true, 'fa-sitemap', 
					[
                        new SubMenu("Add Counselling", "/counselling/add"), 
                        new SubMenu("Show Counselling", "/counselling/show"),]
				),

                'report' => new Menu('report', 'Reports', '', true, 'fa-sitemap', 
					[
                        new SubMenu("Category Wise Report", "/report/category-wise-report"), 
                        new SubMenu("State Wise Report", "/report/state-wise-report"),
                        new SubMenu("Course Wise Report", "/report/course-wise-report"),]
				),
			];
		}

		public static function get_account_navbar_menus(){
			return [
				'home' => new Menu('home', 'Home', '/home', false, 'fa-home'),
				'counselling' => new Menu('counselling', 'Counselling', '', true, 'fa-sitemap', 
					[new SubMenu("Show Counselling", "/counselling/show"),]
				),
			];
		}

		// public static function get_vender_admin_navbar_menus(){
		// 	return [
		// 		'home' => new Menu('dashboard', 'Dashboard', '/home', false, 'fa-th-large'),
		// 		'my_property' => new Menu('my_property', 'My Property', '/my_property', false, 'fa-th-large'),

		// 		'room' => new Menu('room', 'Rooms', '', true, 'fa-sitemap',
		// 			[new SubMenu("Manage Rooms", "/room"), new SubMenu("Add Room", "/room/add")]
		// 		),

		// 		'orders' => new Menu('orders', 'Orders', '/orders', false, 'fa-th-large'),

		// 		'rules' => new Menu('rules', 'Property Rule', '/rules', false, 'fa-th-large'),

		// 		'bookings' => new Menu('bookings', 'Bookings', '/bookings', false, 'fa-th-large'),

		// 		'feedback' => new Menu('feedback', 'Customer Review', '/feedback', false, 'fa-weixin'),

		// 		'vendor_membership' => new Menu('vendor_membership', 'Membership', '/vendor_membership', false, 'fa-th-large'),

		// 	];
		// }//function close

	}//class close

	class Menu{
		//attributes
	   public $routes_name;
	   public $title;
	   public $url;
	   public $has_submenu;
	   public $submenus;
	   public $icon;
	  
	   //constructor function
	   function __construct($routes_name, $title, $url, $has_submenu, $icon = 'fa-dot-circle-o', $submenus = null)  {
	   	  $this->routes_name = $routes_name;
	       $this->title = $title;
	       $this->url = $url;
	       $this->has_submenu = $has_submenu;
	       $this->icon = $icon;
	       $this->submenus = $submenus; 
	   }
	}
	
	class SubMenu{
	   public $title;
	   public $url; 
	   //constructor function
	   function __construct($title, $url)  {
	       $this->title = $title;
	       $this->url = $url; 
	   }
	}

?>