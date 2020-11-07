<?php namespace Controllers;

use Models\Brokers\UserBroker;
use Zephyrus\Application\Configuration;
use Zephyrus\Application\Flash;
use Zephyrus\Application\Session;
use Zephyrus\Security\Cryptography;

class AuthenticateController extends Controller
{
    public function initializeRoutes()
    {
        $this->get("/", "index");
        $this->post("/", "authenticate");
        $this->get("/logout", "logout");
    }

    public function index()
    {
        return $this->render("login");
    }

    public function authenticate()
    {
        $username = $this->request->getParameter('username');
        $password = $this->request->getParameter('password');
        $userBroker = new UserBroker();
        $user = $userBroker->authenticate($username, $password);
        if (is_null($user)) {
            Flash::error("Informations d'authentification invalide");
            return $this->redirect("/");
        }
        Session::getInstance()->set('user', $user);
        return $this->redirect("/heroes");
    }

    public function logout()
    {
        Session::getInstance()->destroy();
        return $this->redirect("/");
    }
}
