<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SpaController extends AbstractController
{
    #[Route(
        '/{vue_routing}',
        name: 'app_main',
        requirements: ['vue_routing' => '^(?!api|_(profiler|wdt)).*'],
        defaults: ['vue_routing' => null],
        priority: -1,
    )]
    public function index(): Response
    {
        return $this->render('base.html.twig');
    }
}
