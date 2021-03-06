<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Notification\ContactNotification;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(Request $request, ContactNotification $contactNotification)
    {
        $contact = new Contact();

        $form = $this ->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $contactNotification->notify($contact); // j'aimerai bien que tu notifie ce contact là et tu t'occupes de la
            // partie traitemenr
            $this->addFlash('success', 'Votre email a bien été envoyé');
            return $this->redirectToRoute('home');

        }

        return $this->render('contact/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
