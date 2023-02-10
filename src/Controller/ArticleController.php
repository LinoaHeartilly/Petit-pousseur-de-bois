<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Categorie;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/article", name="article")
     */
    public function index(ArticleRepository $ar): Response
    {
        $articles = $ar->findAll();
        dd($articles);
        
        return $this->render('article/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/ajoutArticle", name="ajoutArticle")
     */
    public function ajout(Request $request, ArticleRepository $ar, CategorieRepository $cr): Response
    {
        $form = $this->createForm(ArticleType::class);
        $form->handleRequest($request);
        $article = new Article();
        $cat = new Categorie();

        if($form->isSubmitted() && $form->isValid()){

            $article->setNom($form->get('nom')->getData());
            $article->setDescription($form->get('description')->getData());
            $article->setPrix($form->get('prix')->getData());
            $article->setTempsRealisation($form->get('temps_realisation')->getData());
            $cat = $form->get('art_cat')->getData();
            $id = $cat->getId();
            $article->setArtCat($cat);
            $ar->add($article, 1);
            dd($article);
        }

        return $this->renderForm('article/ajoutArticle.html.twig', [
            'formulaire' => $form,

        ]);
    }
}
