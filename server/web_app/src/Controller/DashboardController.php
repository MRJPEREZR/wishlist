<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/dashboard')]
class DashboardController extends AbstractController
{
    #[Route('', name: 'dashboard')]
    public function index(): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('error');
        }

        return $this->render('dashboard.html.twig');
    }

    #[Route('/users', name: 'manageUsers')]
    public function users(): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('error');
        }

        return $this->render('usersManagement.html.twig');
    }

    #[Route('/insights', name: 'manageInsights')]
    public function items(): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('error');
        }

        return $this->render('insights.html.twig');
    }
}
