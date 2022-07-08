<?php

namespace Brangerieau\SymfonyCmsBundle\Controller;

use Brangerieau\SymfonyCmsBundle\Form\EditUserType;
use Brangerieau\SymfonyCmsBundle\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin', name: 'symfony_cms')]
class ProfileController extends AbstractController
{

    public function __construct(
        private UserRepository $userRepository,
        private EntityManagerInterface $em
    )
    {
    }

    #[Route('/profile/{id}', name: '_profile', requirements: ['id' => '\d+'])]
    public function index(Request $request, int $id, SluggerInterface $slugger): Response
    {
        $user = $this->userRepository->find($id);

        $form = $this->createForm(EditUserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $avatar = $form['avatar']->getData();
            if ($avatar) {
                $filename = $user->getId().'.'.$avatar->guessExtension();

                try {
                    $avatar->move(
                        $this->getParameter('kernel.project_dir') . '/public/' . $this->getParameter('avatar_directory'),
                        $filename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                $user->setAvatar($this->getParameter('avatar_directory') . '/' . $filename);
            }

            $this->em->flush();
        }

        return $this->render('@SymfonyCms/profile/index.html.twig', [
            'profile' => $user,
            'formUser' => $form->createView()
        ]);
    }
}
