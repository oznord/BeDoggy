<?php

namespace App\Controller;

use App\Entity\Prestation;
use App\Repository\PrestationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AdminPrestationController extends AbstractController
{
    #[Route('/admin/prestation', name: 'app_admin_prestation')]
    public function index(PrestationRepository $prestationRepository): Response
    {
        $images = $this->getImages();
        $prestations = $prestationRepository->findAll();

        return $this->render('admin_prestation/index.html.twig', [
            'images' => $images,
            'prestations' => $prestations
        ]);
    }

    #[Route('/admin/prestation/ajout', name: 'app_admin_prestation_ajout', methods: ['POST'])]
    public function ajoutPrestation(
        PrestationRepository $prestationRepository,
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
            $message = "Erreur lors de l'ajout : Le libellé est obligatoire.";
        }
        //Verif description
        if ($description === "") {                              //Ne doit pas être vide
            $message = "Erreur lors de l'ajout : La description est obligatoire.";
        }
        //Verif nbSeances
        if ($nbSeances !== null && $nbSeances !== '') {                              //Doit être un nombre entier
            if (!is_numeric($nbSeances) || $nbSeances <= 0){
                $message = "Erreur lors de l'ajout : Vérifiez le nombre de séances.";
            }
            else {
                //Convertion en int
                $nbSeances = (int) $nbSeances;
            }
        }
        else {
            $nbSeances = null;
        }
        //Verif tempsSeance
        if ($tempsSeance !== null && $tempsSeance != '') {
            if (!is_numeric($tempsSeance) || $tempsSeance <= 0){                //Doit être un nombre entier
                $message = "Erreur lors de l'ajout : Vérifiez le nombre de séances.";
            }
            else {
                //Convertion en int
                $tempsSeance = (int) $tempsSeance;
            }
        }
        else {
            $tempsSeance = null;
        }
        //Verif prix 
        if ($prix !== null && $prix != '') {                                   
            if ($prix <= 0 || !preg_match("/"."^\d+([.,]\d{1,2})?$/", $prix)){           //Doit avoir au maximum 2 chiffres après la virgule
                $message = "Erreur lors de l'ajout : Prix invalide.";
            }
            else {
                //Si prix valide, on le transforme en float avec un point
                $prix = (float) str_replace(',', '.', $prix);
            }
        }
        else {
            $prix = null;
        }

        

        //Si au moins une des vérifs n'est pas passée, on renvoie un message d'erreur
        if ($message !== null) {
            //Récupération des images
            $images = $this->getImages();
            //On récupère les prestations pour les afficher sur la page, erreur de traitement ou non
            $prestations = $prestationRepository->findAll();

            return $this->render('admin_prestation/index.html.twig', [
                'message' => $message,
                'images' => $images,
                'prestations' => $prestations,
                'typeMessage' => 'erreur'
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
            $message = "Une erreur est survenue lors de l'enregistrement.";
        }

        //Récupération des images
        $images = $this->getImages();
        //On récupère les prestations pour les afficher sur la page, erreur de traitement ou non
        $prestations = $prestationRepository->findAll();
        
        return $this->render('admin_prestation/index.html.twig', [
            'message' => $message,
            "images" => $images,
            'prestations' => $prestations,
            'typeMessage' => 'ajout',
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

    #[Route('/admin/prestation/suppression', name: 'app_admin_prestation_suppression', methods: ['POST'])]
    public function suppressionPrestation(
        Request $request,
        PrestationRepository $prestationRepository,
        EntityManagerInterface $entityManager
    ): Response
    {
        //Récupération de l'id de la prestation concernée
        $id = $request->request->get('id');

        //On recherche la prestation portant cet id
        $prestation = $prestationRepository->find($id);

        if ($prestation === null) {
            $message = "Erreur lors de la suppression.";
            $typeMessage = "erreur";
        }
        else {
            $entityManager->remove($prestation);
            $entityManager->flush();

            $message = "La prestation a bien été supprimée";
            $typeMessage = "suppression";
        }

        // On récupère les données à afficher
        $images = $this->getImages();
        $prestations = $prestationRepository->findAll();

        return $this->render('admin_prestation/index.html.twig', [
            'messageOperation' => $message,
            'images' => $images,
            'prestations' => $prestations,
            'typeMessageOperation' => $typeMessage,
        ]); 
    }
}
