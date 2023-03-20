<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ContactType;
use App\Repository\ArticleRepository;
use App\Services\MessageService;
use phpDocumentor\Reflection\Types\Nullable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Notifier\Event\FailedMessageEvent;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * @Route("/contact", name="contact")
     * @param Request $request
     * @param MessageService $messageService
     */
    public function contact(Request $request, MailerInterface $mailer): Response
    {
        // instanciation form
        $form = $this->createForm(ContactType::class);
        // récupération des données
        $form->handleRequest($request);        


        // controle form
        if($form->isSubmitted() && $form->isValid()){

            $data = $form->getData();
            // récupération champ email
            $adresse = $data['email'];
            // récupération champ content
            $contenu = $data['contenu'];

            $email = (new Email())
            ->from($adresse)
            ->to('alexandre.caniac93@gmail.com')
            ->subject('Petits pousseur')
            ->text($contenu)
            ->html('<p></p>
                <p>mail : '.$adresse.'</p>
                <p>contenu : '.$contenu.'</p>');
            
            try{
        $mailer->send($email);

            }catch(FailedMessageEvent $event){
                $event->getError();
            }
            
            $succes = 'Mail envoyé avec succès.';

            return $this->renderForm('home/contact.html.twig', [
                'succes' => $succes,
                'formulaire' => $form, 
            ]);
        }       

        return $this->renderForm('home/contact.html.twig', [
            'formulaire' => $form, 
        ]);
    }

    /**
     * 
     * @Route("/mention_legale", name="cgv")
     * @return Response
     */
    public function cgv(): Response
    {

        return $this->render('home/cgv.html.twig');
    }
}
