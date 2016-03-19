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


use Framework\Renderer\Renderer;
use Framework\Router\Router;
use Framework\Exception\HttpNotFoundException;
use Framework\Response\Response;
use Framework\Session\Session;
use Framework\DI\Service;

class Application {

	public function __construct($config){
		$config = new \ArrayObject(include $config, \ArrayObject::ARRAY_AS_PROPS);
//		var_dump($config->pdo); die;
        ini_set('xdebug.var_display_max_depth', -1);
        ini_set('xdebug.var_display_max_children', -1);
        ini_set('xdebug.var_display_max_data', -1);
		Service::set('session', new Session());
		Service::set('renderer', new Renderer('../src/Blog/layout.html.php'));
		Service::set('db', new \PDO($config->pdo['dns'], $config->pdo['user'], $config->pdo['password']));
//		var_dump(Service::get('db')); die;
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
//					var_dump($controller); die;
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
	        //response = new RedirectResponse(...);
        }
        catch(\Exception $e){
	        // Do 500 layout...
	        echo $e->getMessage();
        }

		$response->send();
	}
}