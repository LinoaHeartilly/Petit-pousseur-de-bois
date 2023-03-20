<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Categorie;
use App\Form\ArticleType;
use App\Form\FiltreArticleType;
use App\Repository\ArticleRepository;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ArticleController extends AbstractController
{
    /**
     * @Route("/article", name="article")
     */
    public function index(Request $request, ArticleRepository $ar): Response
    {
        $articles = $ar->findAll();

        $form = $this->createForm(FiltreArticleType::class);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            if(empty($form->get('art_cat')->getData())){
                $nom = $form->get('nom')->getData();
                $nom= '%'.$nom.'%';
                $articles = $ar->findByName($nom);
            }elseif(empty($form->get('nom')->getData())){
                $cat = $form->get('art_cat')->getData();
                $articles = $ar->findByCat($cat);
            }else{

                $nom = $form->get('nom')->getData();
                $cat = $form->get('art_cat')->getData();
                $nom= '%'.$nom.'%';
                $articles = $ar->findByCatAndName($nom, $cat);
            }

            return $this->renderForm('article/index.html.twig', [
                'articles' => $articles,
                'formulaire' => $form
            ]);
        }
        
        return $this->renderForm('article/index.html.twig', [
            'articles' => $articles,
            'formulaire' => $form
        ]);
    }

    /**
     * @Route("/ajoutArticle", name="ajoutArticle")
     */
    public function ajout(Request $request, ArticleRepository $ar, CategorieRepository $cr, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(ArticleType::class);
        $form->handleRequest($request);
        $article = new Article();
        $cat = new Categorie();

        if($form->isSubmitted() && $form->isValid()){

            $brochureFile = $form->get('chemin_image')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('brochures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $article->setCheminImage($newFilename);
            }
            $article->setNom($form->get('nom')->getData());
            $article->setDescription($form->get('description')->getData());
            $article->setPrix($form->get('prix')->getData());
            $article->setTempsRealisation($form->get('temps_realisation')->getData());
            $cat = $form->get('art_cat')->getData();
            $article->setArtCat($cat);
            $ar->add($article, 1);

            // redirection vers la méthode principale au lieu de duppliquer le code
            return $this->forward('App\Controller\ArticleController::index');
        }

        return $this->renderForm('article/ajoutArticle.html.twig', [
            'formulaire' => $form,

        ]);
    }

    /**
     * @Route("/creation/{id}", name="viewArticle")
     */
    public function view(ArticleRepository $ar, $id) :Response
    {
        $article = $ar->find($id);
        return $this->render('article/viewArticle.html.twig', [
            'article' => $article,
        ]);
    }
}
