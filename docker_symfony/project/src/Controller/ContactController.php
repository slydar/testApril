<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contacts', name: 'app_contacts')]
    public function index(): Response
    {
        $contacts = $this->getDoctrine()->getRepository(Contact::class)->findAll();
        return $this->render('contact/index.html.twig', ['contacts' => $contacts]);
    }

    #[Route('/contacts/add', name: 'app_contact_add')]
    public function add(Request $request): Response
    {
        $contact = new Contact();

        // Manage Form
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // save contact in DB
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($contact);
            $entityManager->flush();

            return $this->redirectToRoute('app_contacts');
        }

        return $this->render('contact/new.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/contacts/{id}/edit', name: 'app_contact_edit')]
    public function edit(Request $request, $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $contact = $entityManager->getRepository(Contact::class)->find($id);

        if (!$contact) {
            throw $this->createNotFoundException('Contact not found');
        }

        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Mettez à jour le contact en base de données
            $entityManager->flush();

            return $this->redirectToRoute('app_contacts');
        }

        return $this->render('contact/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/contacts/{id}/remove', name: 'app_contact_remove')]
    public function remove(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        // Get the contact ID from the request
        $contactId = $request->get('id');

        // Get the instance of the contact to edit from the database
        $contact = $entityManager->getRepository(Contact::class)->find($contactId);

        // Check if the contact exists
        if (!$contact) {
            throw $this->createNotFoundException('Contact not found');
        }

        if ($this->isCsrfTokenValid('delete'.$contact->getId(), $request->request->get('_token'))) {
            // remove contact in DB
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($contact);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_contacts');
    }

    #[Route('/contacts/{id}/export-pdf', name: 'app_contact_export_pdf')]
    public function exportPdf(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        // Get the contact ID from the request
        $contactId = $request->get('id');

        // Get the instance of the contact to edit from the database
        $contact = $entityManager->getRepository(Contact::class)->find($contactId);

        // Check if the contact exists
        if (!$contact) {
            throw $this->createNotFoundException('Contact not found');
        }

        // Créez une instance de Dompdf avec les options par défaut
        $dompdf = new Dompdf(new Options());

        // Récupérez le contenu HTML de la fiche contact au format PDF
        $htmlContent = $this->renderView('contact/pdf_template.html.twig', [
            'contact' => $contact,
        ]);

        // Chargez le contenu HTML dans Dompdf
        $dompdf->loadHtml($htmlContent);

        // Rendez le PDF (format A4 par défaut)
        $dompdf->render();

        // Générez le nom du fichier PDF
        $filename = 'contact_' . $contact->getId() . '.pdf';

        // Envoyez le PDF au navigateur
        return new Response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    }
}
