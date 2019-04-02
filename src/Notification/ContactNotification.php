<?php

namespace App\Notification;

use App\Entity\Contact;
use Twig\Environment;

class ContactNotification {

    private $mailer;
    private $twig;

    public function __construct(\Swift_Mailer $mailer, Environment $twig)
    {
        $this->mailer = $mailer;  //permet d'envoyer un email
        $this->twig = $twig; //génére une vue HTML
    }

    public function notify(Contact $contact) { //methode qui permet d'envoyer l'email

        // génère un message -> on crée une nouvelle instance de swift message
        $message = (new \Swift_Message('Envoyez un message'))//<--sujet de l'email
            ->setFrom('noreply@agence.fr') // adress qui sert à l'envoi
            ->setTo('contact@agence.fr') // a qui va être envoyé l'email
            ->setReplyTo($contact->getEmail()) //à qui on va repondre - on injecte l'email de l'utilisateur
            ->setBody($this->twig->render('emails/contact.html.twig', [ // rend le contenu de notre email dans cette page
                'contact' => $contact // passe en parametre les infos sur l'utilisateur -> le contact
        ]), 'text/html'); // ce body est au format text/html

        $this->mailer->send($message); //envoi avec en parametre le message
    }
}