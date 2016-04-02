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
use Framework\Request\Request;
use Framework\Router\Router;
use Framework\Exception\HttpNotFoundException;
use Framework\Response\Response;
use Framework\Security\Security;
use Framework\Session\Session;
use Framework\DI\Service;
use Framework\Controller\Controller;
use Framework\Exception\AuthRequiredException;
use Framework\Response\ResponseRedirect;
use Framework\Exception\AccessDeniedException;

class Application {

	public function __construct($config){
		$config = new \ArrayObject(include $config, \ArrayObject::ARRAY_AS_PROPS);
        $session = new Session();

        ini_set('xdebug.var_display_max_depth', -1);
        ini_set('xdebug.var_display_max_children', -1);
        ini_set('xdebug.var_display_max_data', -1);

		Service::set('session', $session);
		Service::set('renderer', new Renderer('../src/Blog/views/layout.html.php'));
		Service::set('db', new \PDO($config->pdo['dns'], $config->pdo['user'], $config->pdo['password']));
        Service::set('request', new Request());
        Service::set('config', $config);
        Service::set('router', new Router($config->routes));
		Service::set('application', $this);
		Service::set('security', new Security($config->security, $session));
    }

	/**
	 * @throws BadResponseTypeException
	 * @throws Exception\RendererException
     */
	public function run(){

		$router = Service::get('router');
		$route =  $router->parseRoute();
        $renderer = Service::get('renderer');
        $renderer->assign(
            array(
//                'content' => $response->getContent(),
                'user'    => Service::get('security')->getUser(),
                'flush'   => Service::get('session')->getFlush()
            )
        );
		require_once('../src/Blog/Controller/PostController.php');

        try{
            $response = $this->processRoute();
            if (empty($route)) {
                throw new HttpNotFoundException('Route not found');
            }

            if (isset($route['security'])) {
                if (!Service::get('security')->isAuthenticated() /*&& Service::get('request')->getUri() != Service::get('router')->buildRoute('login')*/) {
                    throw new AuthRequiredException('Please. login first!');
                } elseif (!Service::get('security')->isGranted($route['security'])) {
                    throw new AccessDeniedException('Access denied!');
                }
            }

            $controllerReflection = new \ReflectionClass($route['controller']);
            $action = $route['action'] . 'Action';
            if($controllerReflection->hasMethod($action)){

                $controller = $controllerReflection->newInstance();
//					var_dump($controller); die;
                $actionReflection = $controllerReflection->getMethod($action);
                $response = $actionReflection->invokeArgs($controller, $route['params']);
            }

        } catch(HttpNotFoundException $e){
            echo $e->getMessage();
        }
        catch(AuthRequiredException $e){
			$url      = Service::get('router')->buildRoute(Service::get('security')->getLoginRoute());
			$response = new ResponseRedirect($url, null, 'Please, login first!');
//            var_dump(Service::get('request')->getUri()); die;
			$response->setReturnUrl(Service::get('request')->getUri());
		}
        catch(AccessDeniedException $e) {
            echo $e->getMessage();
        }
        catch(\Exception $e){
			$renderer = new Renderer(Service::get('config')->error_500);
			$renderer->assign(array('message' => $e->getMessage(), 'code' => $e->getCode()));
			$response = new Response($renderer->render(), $e->getCode(), 'Internal Server Error');
	        echo $e->getMessage();
        }

		$response->send();
	}

	public function callControllerAction($controller, $action, $args = array())
	{
		$controller       = $this->getController($controller);
		$reflection       = new \ReflectionClass($controller);
		$reflectionMethod = $reflection->getMethod($action.'Action');
		$response         = $reflectionMethod->invokeArgs($controller, $args);

		if (!$response instanceof Response) {
			throw new BadTypeException(sprintf('Response must extend "%s"', 'Framework\\Response\\Response'));
		}
		return $response;
	}

	public function getController($class)
	{
		$controller = new $class;
		if (!$controller instanceof Controller) {
			throw new FactoryException(sprintf(
				'Class "%s" must extend class "%s"',
				$class,
				'Framework\\Controller\\Controller'
			));
		}
		return $controller;
	}

}