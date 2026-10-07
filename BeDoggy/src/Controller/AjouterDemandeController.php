<?php

namespace App\Controller;

use App\Entity\Chien;
use App\Entity\Demande;
use App\Entity\Statut;
use App\Repository\PrestationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AjouterDemandeController extends AbstractController
{
    #[Route('/ajouter/demande', name: 'app_ajouter_demande', methods: ['GET', 'POST'])]
    public function index(
        Request $request,
        EntityManagerInterface $entityManager,
        PrestationRepository $prestationRepository
    ): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $utilisateur = $this->getUser();

        if ($request->isMethod('POST')) {
            if (!$this->isCsrfTokenValid('ajouter_demande', $request->request->get('_token'))) {
                $this->addFlash('error', 'Le formulaire est invalide. Veuillez réessayer.');
            } else {
                $prestationId = filter_var($request->request->get('prestation'), FILTER_VALIDATE_INT);
                $nom = trim((string) $request->request->get('nom'));
                $race = trim((string) $request->request->get('race'));
                $age = filter_var($request->request->get('age'), FILTER_VALIDATE_INT);
                $genre = trim((string) $request->request->get('genre'));
                $description = trim((string) $request->request->get('description'));
                $date = \DateTime::createFromFormat('Y-m-d', (string) $request->request->get('date'));
                $dateErrors = \DateTime::getLastErrors();
                $dateIsValid = $date !== false && ($dateErrors === false || $dateErrors['warning_count'] === 0 && $dateErrors['error_count'] === 0);

                $prestation = $prestationId ? $prestationRepository->find($prestationId) : null;
                $statut = $entityManager->getRepository(Statut::class)->findOneBy(['libelle' => 'En attente']);

                if (
                    $prestation === null ||
                    $nom === '' ||
                    $race === '' ||
                    $age === false ||
                    $age < 0 ||
                    $genre === '' ||
                    $description === '' ||
                    !$dateIsValid ||
                    $date < new \DateTime('today') ||
                    $statut === null
                ) {
                    $this->addFlash('error', 'Veuillez remplir correctement tous les champs.');
                } else {
                    $chien = new Chien();
                    $chien->setNom($nom);
                    $chien->setRace($race);
                    $chien->setAge($age);
                    $chien->setGenre($genre);
                    $chien->setUtilisateur($utilisateur);

                    $demande = new Demande();
                    $demande->setDescription($description);
                    $demande->setDate($date);
                    $demande->setReponse('');
                    $demande->setUtilisateur($utilisateur);
                    $demande->setStatut($statut);
                    $demande->addCorrespondre($prestation);

                    $entityManager->persist($chien);
                    $entityManager->persist($demande);
                    $entityManager->flush();

                    $this->addFlash('success', 'Votre demande a été envoyée.');

                    return $this->redirectToRoute('app_gerer_demande');
                }
            }
        }

        return $this->render('ajouter_demande/index.html.twig', [
            'prestations' => $prestationRepository->findAll(),
        ]);
    }
}
