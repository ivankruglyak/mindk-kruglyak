<?php
/**
 * Application.php
 *
 * PHP version 5
 *
 * @category   Category Name
 * @package    Package Name
 * @subpackage Subpackage name
 * @author     dimmask <ddavidov@mindk.com>
 * @copyright  2011-2013 mindk (http://mindk.com). All rights reserved.
 * @license    http://mindk.com Commercial
 * @link       http://mindk.com
 */

namespace Framework;


use Framework\Router\Router;
use Framework\Exception\HttpNotFoundException;
use Framework\Response\Response;
use Framework\Session\Session;

class Application {

	public function __construct($config){
		Service::set(new Session());
	}

	public function run(){

		$router = new Router(include('../app/config/routes.php'));

		$route =  $router->parseRoute();
		require_once('../src/Blog/Controller/PostController.php');

        try{
	        if(!empty($route)){
		        $controllerReflection = new \ReflectionClass($route['controller']);
		        $action = $route['action'] . 'Action';
		        if($controllerReflection->hasMethod($action)){

			        $controller = $controllerReflection->newInstance();
			        $actionReflection = $controllerReflection->getMethod($action);
			        $response = $actionReflection->invokeArgs($controller, $route['params']);

			        if($response instanceof Response){
				    	// ...

			        } else {
				        throw new BadResponseTypeException('Ooops');
			        }
		        }
			} else {
		        throw new HttpNotFoundException('Route not found');
			}
        }catch(HttpNotFoundException $e){
	         // Render 404 or just show msg
        }
        catch(AuthRequredException $e){
	    	// Reroute to login page
	        //$response = new RedirectResponse(...);
        }
        catch(\Exception $e){
	        // Do 500 layout...
	        echo $e->getMessage();
        }

		$response->send();
	}
}