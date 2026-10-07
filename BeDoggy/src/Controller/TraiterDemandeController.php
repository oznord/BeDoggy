<?php

namespace App\Controller;

use App\Entity\Demande;
use App\Entity\Statut;
use App\Repository\DemandeRepository;
use App\Repository\StatutRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TraiterDemandeController extends AbstractController
{
    #[Route('/traiter/demande', name: 'app_traiter_demande', methods: ['GET', 'POST'])]
    public function index(
        Request $request,
        DemandeRepository $demandeRepository,
        StatutRepository $statutRepository,
        EntityManagerInterface $entityManager
    ): Response {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $utilisateur = $this->getUser();
        if ($utilisateur === null || $utilisateur->getRole()?->getId() !== 1) {
            throw $this->createAccessDeniedException('Cette page est réservée aux administrateurs.');
        }

        $statuts = $statutRepository->findBy([], ['id' => 'ASC']);

        if ($request->isMethod('POST')) {
            $demandeId = filter_var($request->request->get('demande_id'), FILTER_VALIDATE_INT);
            $statutId = filter_var($request->request->get('statut'), FILTER_VALIDATE_INT);
            $demande = $demandeId ? $demandeRepository->find($demandeId) : null;
            $statut = $statutId ? $statutRepository->find($statutId) : null;

            if (
                !$this->isCsrfTokenValid('traiter_demande_' . $demandeId, $request->request->get('_token'))
                || !$demande instanceof Demande
                || !$statut instanceof Statut
            ) {
                $this->addFlash('error', 'La modification de la demande est invalide.');
            } else {
                $demande->setStatut($statut);
                $demande->setReponse(trim((string) $request->request->get('reponse')));
                $entityManager->flush();

                $this->addFlash('success', 'La demande a été mise à jour.');

                return $this->redirectToRoute('app_traiter_demande');
            }
        }

        $demandes = $demandeRepository->createQueryBuilder('demande')
            ->leftJoin('demande.utilisateur', 'utilisateur')
            ->addSelect('utilisateur')
            ->leftJoin('demande.Correspondre', 'prestation')
            ->addSelect('prestation')
            ->orderBy('demande.date', 'DESC')
            ->getQuery()
            ->getResult();

        return $this->render('traiter_demande/index.html.twig', [
            'demandes' => $demandes,
            'statuts' => $statuts,
        ]);
    }
}
