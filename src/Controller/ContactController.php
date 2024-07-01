<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
// use App\Service\MailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


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
            // dd($contact);

            $manager->flush();

            // test service 
            //email envoie
            // $mailService->sendEmail(
            //     $contact->getEmail(),
            //     $contact->getSubject(),
            //     'emails/contact.html.twig',
            //     ['contact' => $contact]
            // );

            // envoie email
            $email = (new TemplatedEmail())
                ->from($contact->getEmail())
                ->to('admin@hellorecettes.com')
                ->subject($contact->getSubject())
                ->htmlTemplate('emails/contact.html.twig')


                ->context([
                    'contact' => $contact
                ]);

            $mailer->send($email);

            $this->addFlash(
                'success',
                'Votre message a été envoyé avec succés !'
            );
            return $this->redirectToRoute('app_contact');

        }

        return $this->render('pages/contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
