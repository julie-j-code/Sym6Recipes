<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(
        Request $request,
        EntityManagerInterface $manager,
        MailerInterface $mailer

    ): Response {
        $contact = new Contact();

        if ($this->getUser()) {
            $contact->setFullName($this->getUser()->getFullName())
                ->setEmail($this->getUser()->getEmail());
        }


        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();

            $manager->persist($contact);
            $manager->flush();

            //Email
            $email = (new TemplatedEmail())
                ->from($contact->getEmail())
                ->to('jeannet.julie@gmail.com')
                ->subject($contact->getSubject())
                ->htmlTemplate('emails/contact.html.twig')
                ->context([
                    'contact' => $contact
                ]);

            $mailer->send($email);

        //     $email = (new Email())
        //     ->from('hello@example.com')
        //     ->to('jeannet.julie@gmail.com')
        //     ->subject('Time for Symfony Mailer!')
        //     ->text('Sending emails is fun again!')
        //     ->html('<p>See Twig integration for better HTML integration!</p>');

        // $mailer->send($email);
            

            $this->addFlash(
                'success',
                'Votre demande a été envoyé avec succès !'
            );

            return $this->redirectToRoute('app_contact');
        } 

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
