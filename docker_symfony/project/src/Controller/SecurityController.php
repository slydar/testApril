<?php

namespace App\Controller;

use App\Form\LoginFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils)
    {
        $form = $this->createForm(LoginFormType::class);
        $error = $authenticationUtils->getLastAuthenticationError();

        // Si l'utilisateur est déjà connecté, redirige-le vers la page des contacts
        if ($this->getUser()) {
            return $this->redirectToRoute('app_contacts');
        }

        return $this->render('security/login.html.twig', [
            'form' => $form->createView(),
            'error' => $error,
        ]);
    }
}
