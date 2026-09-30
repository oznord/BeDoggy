<?php
// filepath: c:\Users\gratienk\OneDrive - Groupement de l'enseignement catholique du pays de Vernon\Bureau\D\Gau\WorkSpace\BeDoggy\BeDoggy\src\Controller\InscriptionController.php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use App\Security\UtilisateurAuthenticator;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class InscriptionController extends AbstractController
{
    #[Route('/inscription', name: 'app_inscription', methods: ['GET', 'POST'])]
    public function index(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        UserAuthenticatorInterface $userAuthenticator,
        UtilisateurAuthenticator $authenticator
    ): Response {
        if ($request->isMethod('POST')) {
            if (!$this->isCsrfTokenValid(
                'inscription',
                $request->request->get('_token')
            )) {
                $this->addFlash('error', 'Le formulaire est invalide.');

                return $this->render('inscription/index.html.twig');
            }

            $nom = trim((string) $request->request->get('nom'));
            $prenom = trim((string) $request->request->get('prenom'));
            $tel = trim((string) $request->request->get('tel'));
            $mail = strtolower(trim((string) $request->request->get('mail')));
            $mdp = (string) $request->request->get('mdp');

            if (
                $nom === '' ||
                $prenom === '' ||
                $tel === '' ||
                !filter_var($mail, FILTER_VALIDATE_EMAIL) ||
                strlen($mdp) < 12
            ) {
                $this->addFlash(
                    'error',
                    'Veuillez remplir correctement tous les champs.'
                );

                return $this->render('inscription/index.html.twig');
            }

            $utilisateurExiste = $entityManager
                ->getRepository(Utilisateur::class)
                ->findOneBy(['mail' => $mail]);

            if ($utilisateurExiste !== null) {
                $this->addFlash(
                    'error',
                    'Cette adresse e-mail est déjà utilisée.'
                );

                return $this->render('inscription/index.html.twig');
            }

            $utilisateur = new Utilisateur();
            $utilisateur->setNom($nom);
            $utilisateur->setPrenom($prenom);
            $utilisateur->setTel($tel);
            $utilisateur->setMail($mail);

            // Le mot de passe est hashé avant son enregistrement en base.
            $utilisateur->setMdp(
                $passwordHasher->hashPassword($utilisateur, $mdp)
            );

            $role = $entityManager
                ->getRepository(Role::class)
                ->findOneBy(['libelle' => 'Utilisateur']);

            if ($role !== null) {
                $utilisateur->setRole($role);
            }

            $entityManager->persist($utilisateur);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Votre compte a été créé avec succès.'
            );

            return $userAuthenticator->authenticateUser(
                $utilisateur,
                $authenticator,
                $request
            );
        }

        return $this->render('inscription/index.html.twig');
    }
}