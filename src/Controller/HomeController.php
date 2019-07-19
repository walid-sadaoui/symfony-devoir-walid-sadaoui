<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     *
     */
    public function home(ArticleRepository $repo)
    {
        $articles = $repo->findAll();
        $total = count($repo->findAll());
        
        return $this->render('home.html.twig', [
            'total' => $total,
            'articles' => $articles
        ]);
    }
}
?>