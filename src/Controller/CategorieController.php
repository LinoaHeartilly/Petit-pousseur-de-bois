<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\ArticleRepository;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategorieController extends AbstractController
{
    /**
     * @Route("/categorie", name="categorie")
     */
    public function index(Request $request, CategorieRepository $cr, ArticleRepository $ar): Response
    {
        // instanciation de la class form categorie
        $form = $this->createForm(CategorieType::class);

        // On stock le retour de la requete
        $form->handleRequest($request);

        $categorie = new Categorie();

        // controle s'il est valide et soumis
        if($form->isSubmitted() && $form->isValid()){
            // dans ce cas on récupère les données
            $categorie->setName($form->get('name')->getData());
            
            // insertion en BDD
            $cr->add($categorie, 1);

            $articles = $ar->findAll();

            $succes = "L'enregistrement de la catégorie a été effectuée avec succès";
            return $this->renderForm('article/index.html.twig', [
                'succes' => $succes,
                'articles' => $articles,
    
            ]);
        }

        return $this->renderForm('categorie/index.html.twig', [
            'controller_name' => 'CategorieController',
            'formulaire' => $form
        ]);
    }
}
