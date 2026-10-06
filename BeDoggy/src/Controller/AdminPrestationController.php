<?php

namespace App\Controller;

use App\Entity\Prestation;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AdminPrestationController extends AbstractController
{
    #[Route('/admin/prestation', name: 'app_admin_prestation')]
    public function index(): Response
    {
        $images = $this->getImages();

        return $this->render('admin_prestation/index.html.twig', [
            'images' => $images,
        ]);
    }

    #[Route('/admin/prestation', name: 'app_admin_prestation_ajout', methods: ['POST'])]
    public function ajoutPrestation(
        Request $request,
        EntityManagerInterface $entityManager
        ): Response
    {
        $message = null;

        //Récupération des informations
        //trim enlève les espaces au début et à la fin
        $libelle = trim($request->request->get('libelle', ''));  //Le 2e champ est la valeur par défaut
        $description = trim($request->request->get('description', ''));
        $nbSeances = $request->request->get('nbSeances');
        $prix = $request->request->get('prixSeance');
        $tempsSeance = $request->request->get('tempsSeance');
        $image = $request->request->get('image');

        //Verif libelle
        if ($libelle === "") {                                  //Ne doit pas être vide
            $message = "Le libellé est obligatoire";
        }
        //Verif description
        elseif ($description === "") {                              //Ne doit pas être vide
            $message = "La description est obligatoire";
        }
        //Verif nbSeances
        elseif ($nbSeances !== null && $nbSeances !== '') {                              //Doit être un nombre entier
            if (!is_numeric($nbSeances) || $nbSeances <= 0){
                $message = "Vérifiez le nombre de séances.";
            }
        }
        //Verif tempsSeance
        elseif ($tempsSeance !== null) {
            if (!is_numeric($tempsSeance) || $tempsSeance <= 0){                //Doit être un nombre entier
                $message = "Vérifiez le nombre de séances.";
            }
        }
        //Verif prix 
        elseif ($prix !== null && $prix != '') {                                   
            if ($prix <= 0 || !preg_match('/^\d+(\.\d{1,2})?$/', $prix)){           //Doit avoir au maximum 2 chiffres après la virgule
                $message = "Prix invalide";
            }
        }

        //Récupération des images
        $images = $this->getImages();

        //Si au moins une des vérifs n'est pas passée, on renvoie un message d'erreur
        if ($message !== null) {
            return $this->render('admin_prestation/index.html.twig', [
                'message' => $message,
            ]);
        }

        //Si toutes les vérifs sont passées, on peut enregistrer les données
        $prestation = new Prestation();
        $prestation->setLibelle($libelle);
        $prestation->setDescription($description);
        $prestation->setNbSeance($nbSeances);
        $prestation->setPrixSeance($prix);
        $prestation->setTempsSeance($tempsSeance);
        $prestation->setImage($image);

        try {
            //On peut enregistrer en base de données
            $entityManager->persist($prestation);
            $entityManager->flush();

            //Messgae positif car tout s'est bien passé
            $message = "L'ajout a bien été effectué.";
        }
        catch (Exception $e) {
            $message = "Une erreur est survenue lors de l'enregistrement";
        }
        
        return $this->render('admin_prestation/index.html.twig', [
            'message' => $message,
            "images" => $images
        ]);
    }



    //Méthodes
    private function getImages(): array
    {
        $imagesPath = $this->getParameter('kernel.project_dir').'/public/assets/images/prestations';

        //array_filter : Filtre des éléments, si ces éléments correspondent à la condition, on les garde, sinon non
        //fn ($file) => : Pour chaque fichier on regarde la condition, ici, on regarde la fin des noms des fichiers
        // et on vérifie qu'ils terminent bien par des extensions d'images
        return array_values(array_filter(
            scandir($imagesPath),
            fn ($file) => in_array(
                strtolower(pathinfo($file, PATHINFO_EXTENSION)),
                ['jpg', 'jpeg', 'png']
            )
        ));
    }

}
