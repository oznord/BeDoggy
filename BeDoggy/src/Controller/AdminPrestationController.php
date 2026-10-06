<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AdminPrestationController extends AbstractController
{
    #[Route('/admin/prestation', name: 'app_admin_prestation')]
    public function index(): Response
    {
        $imagesPath = $this->getParameter('kernel.project_dir').'/public/assets/images/prestations';

        //array_filter : Filtre des éléments, si ces éléments correspondent à la condition, on les garde, sinon non
        //fn ($file) => : Pour chaque fichier on regarde la condition, ici, on regarde la fin des noms des fichiers
        // et on vérifie qu'ils terminent bien par des extensions d'images
        $images = array_values(array_filter(
            scandir($imagesPath),
            fn ($file) => in_array(
                strtolower(pathinfo($file, PATHINFO_EXTENSION)),
                ['jpg', 'jpeg', 'png']
            )
        ));

        return $this->render('admin_prestation/index.html.twig', [
            'images' => $images,
        ]);
    }

    #[Route('/admin/prestation', name: 'app_admin_prestation_ajout')]
    public function ajoutPrestation(Request $request): Response
    {
        $message = null;

        //Récupération des informations
        //trim enlève les espaces au début et à la fin
        $libelle = trim($request->request->get('libelle', ''));  //Le 2e champ est la valeur par défaut
        $description = trim($request->request->get('description', ''));
        $nbSeances = $request->request->get('nbseances');
        $prix = $request->request->get('prixSeance');
        $tempsSeance = $request->request->get('tempsSeance');
        $image = $request->request->get('image');

        //Verif libelle
        if ($libelle === "") {
            $message = "Le libellé est obligatoire";
        }
        //Verif description
        if ($description === "") {
            $message = "La description est obligatoire";
        }
        //Verif nbSeances
        if ($nbSeances !== null) {
            if (!is_numeric($nbSeances) <= 0){
                $message = "Vérifiez le nombre de séances.";
            }
        }
        //Verif tempsSeance
        if ($tempsSeance !== null) {
            if (!is_numeric($tempsSeance) <= 0){
                $message = "Vérifiez le nombre de séances.";
            }
        }
        //Verif prix
        if ($prix !== null) {
            if ($prix <= 0 || !preg_match('/^\d+(\.\d{1,2})?$/', $prix)){
                $message = "Prix invalide";
            }
        }



        if ($message !== null) {
            return $this->render('admin_prestation/index.html.twig', [
                'message' => $message,
            ]);
        }
    }


}
