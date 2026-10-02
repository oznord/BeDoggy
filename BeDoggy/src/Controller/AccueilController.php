<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\PrestationRepository;

final class AccueilController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]
    public function index(PrestationRepository $prestationRepository): Response
    {
        $prestations = $prestationRepository->findBy(
            [],
            ['id' => 'ASC'],
            3
            );

        return $this->render('accueil/index.html.twig', [
            'controller_name' => 'AccueilController',
            'prestations' => $prestations,
        ]);
    }
}
