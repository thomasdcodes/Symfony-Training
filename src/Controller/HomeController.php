<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\ServerInstanceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route(path: '/', name: 'app_home_dashboard')]
    public function dashboard(ServerInstanceRepository $serverInstanceRepository): Response
    {
        return $this->render('home/dashboard.html.twig', [
            'instances' => $serverInstanceRepository->findAll(),
        ]);
    }

    #[Route(path: '/impressum', name: 'app_home_impressum')]
    public function impressum(): Response
    {
        $model = 'Hello Impressum!';

        dump($model);

        return $this->render('home/impressum.html.twig', [
            'model' => $model,
        ]);
    }
}