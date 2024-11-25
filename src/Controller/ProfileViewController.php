<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProfileViewController extends AbstractController
{
    #[Route('/profileview/{id}', name: 'app_profile_view')]
    public function show(User $user): Response
    {
        return $this->render('profile_view/show.html.twig', [
            'user' => $user
        ]);
    }
}
