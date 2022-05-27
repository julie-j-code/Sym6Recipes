<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Service\MailService;
use Doctrine\ORM\EntityManagerInterface;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3Validator;
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
        MailService $mailer,
        Recaptcha3Validator $recaptcha3Validator

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
            // $score = $recaptcha3Validator->getLastResponse()->getScore();

            $manager->persist($contact);
            $manager->flush();

            //Email
            // $email = (new TemplatedEmail())
            //     ->from($contact->getEmail())
            //     ->to('jeannet.julie@gmail.com')
            //     ->subject($contact->getSubject())
            //     ->htmlTemplate('emails/contact.html.twig')
            //     ->context([
            //         'contact' => $contact
            //     ]);

            $mailer->sendEmail(
                $contact->getEmail(),
                $contact->getSubject(),
                'emails/contact.html.twig',
                ['contact'=>$contact],
                'jeannet.julie@gmail.com'
            );       

            $this->addFlash(
                'success',
                'Votre demande a été envoyée avec succès !'
            );

            return $this->redirectToRoute('app_contact');
        } 

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
            'controller_name' => 'Nous contacter'
        ]);
    }
}
