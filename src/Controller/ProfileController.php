<?php

namespace Brangerieau\SymfonyCmsBundle\Controller;

use Brangerieau\SymfonyCmsBundle\Form\EditUserType;
use Brangerieau\SymfonyCmsBundle\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin', name: 'symfony_cms')]
class ProfileController extends AbstractController
{
    #[Route('/profile/{id}', name: '_profile', requirements: ['id' => '\d+'])]
    public function index(Request $request, int $id, UserRepository $userRepository, EntityManagerInterface $em): Response
    {
        $user = $userRepository->find($id);

        $form = $this->createForm(EditUserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->flush();
        }

        return $this->render('@SymfonyCms/profile/index.html.twig', [
            'profile' => $user,
            'formUser' => $form->createView()
        ]);
    }
}
