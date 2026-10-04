<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AdminPrestationController extends AbstractController
{
    #[Route('/admin/prestation', name: 'app_admin_prestation')]
    public function index(): Response
    {
        return $this->render('admin_prestation/index.html.twig', [
            'controller_name' => 'AdminPrestationController',
        ]);
    }
}
