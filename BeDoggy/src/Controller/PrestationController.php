<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\PrestationRepository;

final class PrestationController extends AbstractController
{
    #[Route('/prestation', name: 'app_prestation')]
    public function index(PrestationRepository $prestationRepository): Response
    {
        // Récupération des données de toutes les prestations
        $prestations = $prestationRepository->findAll();

        return $this->render('prestation/index.html.twig', [
            'prestations' => $prestations,
        ]);
    }
}
