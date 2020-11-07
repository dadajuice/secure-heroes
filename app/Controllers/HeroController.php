<?php namespace Controllers;

use Models\Brokers\HeroBroker;
use Zephyrus\Security\Cryptography;

class HeroController extends Controller
{
    public function initializeRoutes()
    {
        $this->get("/heroes", "index");
        //$this->get("/heroes", "testJson");
    }

    public function index()
    {
        $heroes = (new HeroBroker())->findAll();
        return $this->render('heroes', [
            'heroes' => $heroes
        ]);
    }

    public function testJson()
    {
        $heroes = (new HeroBroker())->findAll();
        return $this->json([
            'heroes' => $heroes
        ]);
    }
}
