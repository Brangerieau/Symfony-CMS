<?php

namespace Brangerieau\SymfonyCmsBundle\Controller;

use Brangerieau\SymfonyCmsBundle\Entity\Pages;
use Brangerieau\SymfonyCmsBundle\Form\PagesType;
use Brangerieau\SymfonyCmsBundle\Repository\PagesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/admin/visual-editor', name: 'symfony_cms_visual_editor')]
class VisualEditorController extends AbstractController
{
    #[Route('/preview', name: '_preview')]
    public function index(Request $request)
    {
        $content = json_decode($request->getContent(), true);

        if (array_is_list($content)){
            return $this->render('@SymfonyCms/visualeditor/preview.html.twig', [
                'blocks' => $content
            ]);
        }

        return $this->render('@SymfonyCms/visualeditor/blocks/' . $content['_name'] . '.html.twig', $content);
    }
}
