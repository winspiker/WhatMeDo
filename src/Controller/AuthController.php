<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AuthController extends AbstractController
{


    public function login() {
        return $this->render('auth/login.html.twig');
    }
}