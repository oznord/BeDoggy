<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'app_profil', methods: ['GET', 'POST'])]
    #[Route('/Profil', name: 'app_profil_majuscule', methods: ['GET', 'POST'])]
    public function index(
        Request $request,
        EntityManagerInterface $entityManager,
        UtilisateurRepository $utilisateurRepository
    ): Response
    {
        $utilisateur = $this->getUser();

        if (!$utilisateur instanceof Utilisateur) {
            return $this->redirectToRoute('app_login');
        }

        if ($request->isMethod('POST')) {
            $action = $request->request->get('action');

            if ($action === 'modifier_profil') {
                if (!$this->isCsrfTokenValid('modifier_profil', $request->request->get('_token'))) {
                    $this->addFlash('error', 'La modification du profil est invalide.');
                } else {
                    $prenom = trim((string) $request->request->get('prenom'));
                    $nom = trim((string) $request->request->get('nom'));
                    $mail = trim((string) $request->request->get('mail'));
                    $tel = trim((string) $request->request->get('tel'));
                    $autreUtilisateur = $utilisateurRepository->findOneBy(['mail' => $mail]);

                    if (
                        $prenom === '' ||
                        $nom === '' ||
                        !filter_var($mail, FILTER_VALIDATE_EMAIL) ||
                        $tel === '' ||
                        ($autreUtilisateur !== null && $autreUtilisateur->getId() !== $utilisateur->getId())
                    ) {
                        $this->addFlash('error', 'Veuillez remplir correctement toutes les informations.');
                    } else {
                        $utilisateur->setPrenom($prenom);
                        $utilisateur->setNom($nom);
                        $utilisateur->setMail($mail);
                        $utilisateur->setTel($tel);
                        $entityManager->flush();

                        $this->addFlash('success', 'Vos informations ont été modifiées.');

                        return $this->redirectToRoute('app_profil');
                    }
                }
            } elseif ($action === 'supprimer_compte') {
                if (!$this->isCsrfTokenValid('supprimer_compte', $request->request->get('_token'))) {
                    $this->addFlash('error', 'La suppression du compte est invalide.');
                } else {
                    foreach ($utilisateur->getDemandes() as $demande) {
                        $entityManager->remove($demande);
                    }

                    foreach ($utilisateur->getChiens() as $chien) {
                        $entityManager->remove($chien);
                    }

                    $entityManager->remove($utilisateur);
                    $entityManager->flush();

                    return $this->redirectToRoute('app_login');
                }
            }
        }

        return $this->render('profil/index.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }
}
