<?php

namespace Brangerieau\SymfonyCmsBundle\Controller;

use Brangerieau\SymfonyCmsBundle\Entity\User;
use Brangerieau\SymfonyCmsBundle\Form\EditUserType;
use Brangerieau\SymfonyCmsBundle\Form\ResetPasswordType;
use Brangerieau\SymfonyCmsBundle\Repository\UserRepository;
use Brangerieau\SymfonyCmsBundle\Services\Mailing;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\ResetPassword\DependencyInjection\SymfonyCastsResetPasswordExtension;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

#[Route('/admin/users', name: 'symfony_cms_users')]
#[IsGranted('ROLE_SUPER_ADMIN')]
#[IsGranted('ROLE_ADMIN')]
class UsersController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository,
        private EntityManagerInterface $manager,
        private TranslatorInterface $translator,
        private ResetPasswordHelperInterface $resetPasswordHelper,
        private Mailing $mailing
    ){
    }

    #[Route('/', name: '')]
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $users = $paginator->paginate(
            $this->userRepository->findAll(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('@SymfonyCms/users/index.html.twig', [
            'reset_password' => class_exists('SymfonyCasts\Bundle\ResetPassword\DependencyInjection\SymfonyCastsResetPasswordExtension'),
            'users' => $users,
        ]);
    }

    #[Route('/{user}/update', name: '_update')]
    public function update(Request $request, User $user): Response
    {

        $form = $this->createForm(EditUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $this->addFlash('success',
                $this->translator->trans('{name} has been modified', ['{name}' => $user->getLastname() . ' ' . $user->getFirstname()], 'symfonycms')
            );
        }

        return $this->render('@SymfonyCms/users/update.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{user}/reset-password/', name: '_reset_password', requirements: ['user' => '\d+'])]
    public function reset_password(Request $request, User $user, MailerInterface $mailer): RedirectResponse
    {
        try {
            $resetToken = $this->resetPasswordHelper->generateResetToken($user);
        } catch (ResetPasswordExceptionInterface $e) {

            $this->addFlash('danger',
                $this->translator->trans('An error occurred while generating the token', [], 'symfonycms')
            );

            return $this->redirectToRoute('symfony_cms_users');
        }

        $email = $this->mailing->create($user->getEmail(), '@SymfonyCms/mails/reset-password/email.html.twig', [
            'resetToken' => $resetToken
        ], new Address('resetpassword@symfonnycms.fr', $this->translator->trans('Reset password', [], 'symfonycms')));

        $this->mailing->send($email);

        $this->addFlash('success',
            $this->translator->trans('An email has been sent to {name} to change his password', ['{name}' => $user->getLastname() . ' ' . $user->getFirstname()], 'symfonycms')
        );

        return $this->redirectToRoute('symfony_cms_users');
    }

    #[Route('/{user}/delete/', name: '_delete', requirements: ['user' => '\d+'])]
    public function delete(Request $request, User $user): RedirectResponse
    {
        $this->manager->remove($user);
        $this->manager->flush();

        $this->addFlash('success',
            $this->translator->trans('User {name} has been deleted!', ['{name}' => $user->getLastname() . ' ' . $user->getFirstname()], 'symfonycms')
        );

        return $this->redirectToRoute('symfony_cms_users');
    }
}
