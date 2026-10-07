<?php

namespace App\Controller;

use App\Entity\Demande;
use App\Repository\DemandeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class GererDemandeController extends AbstractController
{
    #[Route('/gerer/demande', name: 'app_gerer_demande')]
    public function index(): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $user = $this->getUser();
        $demandes = $user->getDemandes();

        return $this->render('gerer_demande/index.html.twig', [
            'demandes' => $demandes,
        ]);
    }

    #[Route('/gerer/demande/{id}', name: 'app_gerer_demande_detail', methods: ['GET'])]
    public function detail(int $id, DemandeRepository $demandeRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $demande = $demandeRepository->find($id);
        if (!$demande instanceof Demande || $demande->getUtilisateur()?->getId() !== $this->getUser()->getId()) {
            throw $this->createNotFoundException('Cette demande n’existe pas.');
        }

        return $this->render('gerer_demande/detail.html.twig', [
            'demande' => $demande,
        ]);
    }
}
