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
                $prestationIds = $request->request->all('prestation');
                $prestationIds = array_values(array_unique(array_filter(
                    array_map(
                        static fn (mixed $id): int|false => filter_var($id, FILTER_VALIDATE_INT),
                        $prestationIds
                    ),
                    static fn (int|false $id): bool => $id !== false && $id > 0
                )));
                $nom = trim((string) $request->request->get('nom'));
                $race = trim((string) $request->request->get('race'));
                $age = filter_var($request->request->get('age'), FILTER_VALIDATE_INT);
                $genre = trim((string) $request->request->get('genre'));
                $description = trim((string) $request->request->get('description'));
                $date = \DateTime::createFromFormat('Y-m-d', (string) $request->request->get('date'));
                $dateErrors = \DateTime::getLastErrors();
                $dateIsValid = $date !== false && ($dateErrors === false || $dateErrors['warning_count'] === 0 && $dateErrors['error_count'] === 0);
                $nomIsValid = preg_match("/^[\\p{L}]+(?:[ '-][\\p{L}]+)*$/u", $nom) === 1;
                $raceIsValid = preg_match("/^[\\p{L}]+(?:[ '-][\\p{L}]+)*$/u", $race) === 1;

                $prestations = $prestationIds !== []
                    ? $prestationRepository->findBy(['id' => $prestationIds])
                    : [];
                $statut = $entityManager->getRepository(Statut::class)->findOneBy(['libelle' => 'En attente']);

                if (
                    count($prestations) !== count($prestationIds) ||
                    $prestations === [] ||
                    !$nomIsValid ||
                    !$raceIsValid ||
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
                    foreach ($prestations as $prestation) {
                        $demande->addCorrespondre($prestation);
                    }

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
