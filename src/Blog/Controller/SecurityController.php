<?php
/**
 * Created by PhpStorm.
 * User: dgilan
 * Date: 10/17/14
 * Time: 12:41 PM
 */

namespace Blog\Controller;

use Blog\Model\User;
use Framework\Controller\Controller;
use Framework\DI\Service;
use Framework\Exception\DatabaseException;
use Framework\Response\ResponseRedirect;

class SecurityController extends Controller
{

    public function loginAction()
    {
        if ( $isAuthenticated = Service::get('security')->isAuthenticated()) {
            return new ResponseRedirect($this->buildRoute('home'));
        }
        $errors = array();

        if ($this->getRequest()->isPost()) {

            if ($user = User::findByEmail($this->getRequest()->post('email'))) {
                if ($user->password == $this->getRequest()->post('password')) {
                    Service::get('security')->setUser($user);
                    $returnUrl = Service::get('session')->returnUrl;
                    unset(Service::get('session')->returnUrl);
                    $response = $this->redirect(
                        !is_null($returnUrl)
                            ? $returnUrl
                            : Service::get('router')->buildRoute('home')
                    );
                    return $response;
                }
            }

            array_push($errors, 'Invalid username or password');
        }

        return $this->render('login.html', array('errors' => $errors));
    }

    public function logoutAction()
    {
        Service::get('security')->clear();
        return $this->redirect(Service::get('router')->buildRoute('login'));
    }

    public function signinAction()
    {
        if (Service::get('security')->isAuthenticated()) {
            return new ResponseRedirect(Service::get('router')->buildRoute('home'));
        }
        $errors = array();

        if ($this->getRequest()->isPost()) {
            try{
                $user           = new User();
                $user->email    = $this->getRequest()->post('email');
                $user->password = $this->getRequest()->post('password');
                $user->role     = 'ROLE_USER';
                $user->save();
                return $this->redirect($this->generateRoute('home'));
            } catch(DatabaseException $e){
                $errors = array($e->getMessage());
            }
        }

        return $this->render('signin.html', array('errors' => $errors));
    }
}