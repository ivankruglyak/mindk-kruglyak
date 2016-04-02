<?php
/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 27.03.16
 * Time: 9:44
 */

namespace Framework\Security;

use Blog\Model\User;
use Framework\DI\Service;
//use Framework\Exception\CsrfTokenException;
use Framework\Session\Session;

final class Security
{
    protected $config;

    protected $session;

    protected $csrf_hash = '';
//
//    protected $csrf_expire = 7200;

//    /**
//     * Token name for Cross Site Request Forgery Protection Cookie
//     *
//     * @var string
//     * @access protected
//     */
//    protected $csrf_token_name = 'ci_csrf_token';

//    /**
//     * Cookie name for Cross Site Request Forgery Protection Cookie
//     *
//     * @var string
//     * @access protected
//     */
//    protected $csrf_cookie_name = 'ci_csrf_token';

    protected $user = null;

    public function __construct(array $config = array(), Session $session)
    {
        $this->config  = $config;
        $this->session = $session;
        if ($userId = $this->session->user) {
            $userClass = $config['user_class'];
            $user      = $userClass::find((int)$userId);
            if ($user) {
                $this->user = $user;
            } else {
                unset($this->session->user);
            }
        }
    }

    /**
     * Get user
     *
     * @return null|User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set user
     *
     * @param null|User $user
     */
    public function setUser($user = null)
    {
        $this->user = $user;
        $this->session->setUser($user);
    }

    /**
     * Clear
     */
    public function clear()
    {
        $this->setUser();
    }

    /**
     * is granted
     *
     * @param array $roles
     * @return bool
     */
    public function isGranted(array $roles)
    {
        return ($user = $this->getUser()) && in_array($user->getRole(), $roles);
    }

    /**
     * Is authenticated
     *
     * @return bool
     */
    public function isAuthenticated()
    {
        return is_object($this->getUser());
    }

    /**
     * Get login route
     *
     * @return mixed
     */
    public function getLoginRoute()
    {
        return $this->config['login_route'];
    }

//    public function csrfVerify()
//    {
//        // If it's not a POST request we will set the CSRF cookie
//        if (strtoupper($_SERVER['REQUEST_METHOD']) !== 'POST') {
//            $this->csrfSetHash();
//            return $this->csrfSetCookie();
//        }
//
//        // Do the tokens exist in both the _POST and _COOKIE arrays?
//        if (!isset($_POST[$this->csrf_token_name], $_COOKIE[$this->csrf_cookie_name])) {
//            throw new CsrfTokenException('Action is not allowed!');
//        }
//
//        // Do the tokens match?
//        if ($_POST[$this->csrf_token_name] != $_COOKIE[$this->csrf_cookie_name]) {
//            throw new CsrfTokenException('Action is not allowed!');
//        }
//
//        // We kill this since we're done and we don't want to
//        // polute the _POST array
//        unset($_POST[$this->csrf_token_name]);
//
//        // Nothing should last forever
//        unset($_COOKIE[$this->csrf_cookie_name]);
//        $this->csrfSetHash();
//        $this->csrfSetCookie();
//
//        return $this;
//    }

//    public function csrfSetCookie()
//    {
//        $expire        = time() + $this->csrf_expire;
//        setcookie(
//            $this->csrf_cookie_name,
//            $this->csrf_hash,
//            $expire
//        );
//
//        return $this;
//    }
//
    public function getHash()
    {
        return $this->csrf_hash;
    }
//
//    protected function csrfSetHash()
//    {
//        if ($this->csrf_hash == '') {
//            // If the cookie exists we will use it's value.
//            // We don't necessarily want to regenerate it with
//            // each page load since a page could contain embedded
//            // sub-pages causing this feature to fail
//            if (isset($_COOKIE[$this->csrf_cookie_name]) &&
//                preg_match('#^[0-9a-f]{32}$#iS', $_COOKIE[$this->csrf_cookie_name]) === 1
//            ) {
//                return $this->csrf_hash = $_COOKIE[$this->csrf_cookie_name];
//            }
//
//            return $this->csrf_hash = md5(uniqid(rand(), true));
//        }
//
//        return $this->csrf_hash;
//    }
}