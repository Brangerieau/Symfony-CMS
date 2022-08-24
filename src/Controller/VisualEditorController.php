<?php

namespace Brangerieau\SymfonyCmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/visual-editor', name: 'symfony_cms_visual_editor')]
class VisualEditorController extends AbstractController
{
    #[Route('/preview', name: '_preview')]
    public function index(Request $request)
    {
        $content = json_decode($request->getContent(), true);

        if (array_is_list($content)) {
            return $this->render('@SymfonyCms/visualeditor/preview.html.twig', [
                'blocks' => $content,
            ]);
        }

        return $this->render('@SymfonyCms/visualeditor/blocks/'.$content['_name'].'.html.twig', $content);
    }
}
