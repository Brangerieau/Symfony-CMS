<?php

namespace Brangerieau\SymfonyCmsBundle\Controller;

use Brangerieau\SymfonyCmsBundle\Entity\Pages;
use Brangerieau\SymfonyCmsBundle\Form\PagesType;
use Brangerieau\SymfonyCmsBundle\Repository\PagesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/admin/pages', name: 'symfony_cms_pages')]
class PagesController extends AbstractController
{
    public function __construct(
        private PagesRepository $pagesRepository,
        private TranslatorInterface $translator,
        private EntityManagerInterface $manager,
        private SluggerInterface $slugger
    ) {
    }

    #[Route('/', name: '')]
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $pages = $paginator->paginate(
            $this->pagesRepository->findAll(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('@SymfonyCms/pages/index.html.twig', [
            'pages' => $pages,
        ]);
    }

    #[Route('/add', name: '_add')]
    #[Route('/{page}/update', name: '_update')]
    public function update(Request $request, Pages $page = null): Response
    {
        $page = ($page) ? $page : new Pages();

        $form = $this->createForm(PagesType::class, $page);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ('symfony_cms_pages_add' === $request->attributes->get('_route')) {
                $page->setAuthor($this->getUser());
            }
            $page->setSlug(strtolower($this->slugger->slug($page->getName())));
            $page->setModifiedAt(new \DateTimeImmutable());

            $this->manager->persist($page);
            $this->manager->flush();

            $this->addFlash('success',
                $this->translator->trans('Page {name} has been modified', ['{name}' => $page->getName()], 'symfonycms')
            );

            if ('symfony_cms_pages_add' === $request->attributes->get('_route')) {
                return $this->redirectToRoute('symfony_cms_pages_update', ['page' => $page->getId()]);
            }
        }

        return $this->render('@SymfonyCms/pages/update.html.twig', [
            'page' => $page,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{page}/delete/', name: '_delete', requirements: ['user' => '\d+'])]
    public function delete(Request $request, Pages $page): RedirectResponse
    {

        if(!$page->isPossibleToDelete()){
            throw new BadRequestException('Impossible to delete this page', 400);
        }

        $this->manager->remove($page);
        $this->manager->flush();

        $this->addFlash('success',
            $this->translator->trans('Page {name} has been deleted!', ['{name}' => $page->getName()], 'symfonycms')
        );

        return $this->redirectToRoute('symfony_cms_pages');
    }
}
