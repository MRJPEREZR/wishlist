<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class ErrorController extends AbstractController
{
    #[Route('/error', name: 'error')]
    public function index(): Response
    {
        return $this->render('error.html.twig');
    }
}
