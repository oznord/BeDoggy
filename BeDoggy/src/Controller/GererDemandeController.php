<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class GererDemandeController extends AbstractController
{
    #[Route('/gerer/demande', name: 'app_gerer_demande')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $demandes = $user->getDemandes();

        return $this->render('gerer_demande/index.html.twig', [
            'demandes' => $demandes,
        ]);

        




    }
}
