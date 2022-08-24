<?php

namespace Brangerieau\SymfonyCmsBundle\Controller;

use Brangerieau\SymfonyCmsBundle\Entity\User;
use Brangerieau\SymfonyCmsBundle\Form\EditUserType;
use Brangerieau\SymfonyCmsBundle\Form\ResetPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin', name: 'symfony_cms')]
class ProfileController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    #[Route('/profile/{user}', name: '_profile', requirements: ['user' => '\d+'])]
    #[IsGranted('PROFILE_VIEW', 'user')]
    public function index(Request $request, User $user): Response
    {
        $form = $this->createForm(EditUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $avatar = $form['avatar']->getData();
            if ($avatar) {
                $filename = $user->getId().'.'.$avatar->guessExtension();

                try {
                    $avatar->move(
                        $this->getParameter('kernel.project_dir').'/public/'.$this->getParameter('avatar_directory'),
                        $filename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                $user->setAvatar($this->getParameter('avatar_directory').'/'.$filename);
            }

            $this->em->flush();
        }

        return $this->render('@SymfonyCms/profile/index.html.twig', [
            'profile' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/profile/{user}/reset-password', name: '_profile_reset_password', requirements: ['user' => '\d+'])]
    #[IsGranted('PROFILE_EDIT', 'user')]
    public function reset_password(Request $request, User $user, UserPasswordHasherInterface $hasher): Response
    {
        $form = $this->createForm(ResetPasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($hasher->hashPassword($user, $user->getPassword()));
            $this->em->flush();
        }

        return $this->render('@SymfonyCms/profile/resetPassword.html.twig', [
            'profile' => $user,
            'form' => $form->createView(),
        ]);
    }
}
