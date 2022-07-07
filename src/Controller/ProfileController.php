<?php

namespace Brangerieau\SymfonyCmsBundle\Controller;

use Brangerieau\SymfonyCmsBundle\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin', name: 'symfony_cms')]
class ProfileController extends AbstractController
{
    #[Route('/profile/{id}', name: '_profile', requirements: ['id' => '\d+'])]
    public function index(Request $request, int $id, UserRepository $userRepository): Response
    {
        return $this->render('@SymfonyCms/profile/index.html.twig', [
            'profile' => $userRepository->find($id),
        ]);
    }
}
