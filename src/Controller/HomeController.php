<?php

namespace App\Controller;

use App\Form\ContactType;
use App\Services\MailerService;
use App\Services\MessageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
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
     * @param MailerService $mailerService
     */
    public function contact(Request $request, MailerInterface $mailer, MailerService $mailerService): Response
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

            // création de l'email
            $email = (new Email())
                ->from($adresse)
                ->to('alexandre.caniac93@gmail.com')
                ->subject('Site : nouveau contact')
                ->text($contenu);

            // envoie de l'email
            $mailer->send($email);
            /* $mailerService->send(
                "New message", 
                $data['email'], 
                "alexandre.caniac93@gmail.com", 
                "home/index.html.twig",
                [
                    "name" => "toto", 
                    "message" => $data['contenu']
                ]
            );*/
            dd($data);
        }       

        return $this->renderForm('home/contact.html.twig', [
            'controller_name' => 'HomeController',
            'formulaire' => $form
        ]);
    }
}
