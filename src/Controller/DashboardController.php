<?php

namespace Brangerieau\SymfonyCmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin', name: 'symfony_cms')]
class DashboardController extends AbstractController
{
    #[Route('/', name: '_dashboard')]
    public function index(Request $request): Response
    {
        return $this->render('@SymfonyCms/dashboard/index.html.twig');
    }
}
