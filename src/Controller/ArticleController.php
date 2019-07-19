<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Common\Persistence\ObjectManager;

class ArticleController extends AbstractController
{
    /**
     * @Route("/articles", name="articles_index")
     */
    public function index( ArticleRepository $repo)
    {
        $articles = $repo->findAll();

        dump($articles);

        return $this->render('article/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * Permet de créer une article
     * @Route("/articles/new", name="articles_create")
     *
     * @return void
     */
    public function create(Request $request, ObjectManager $manager){

        $article = new Article();

        $form = $this->createForm(ArticleType::class, $article);

        dump($article);

        $form->handleRequest($request);

        dump($article);

        if( $form->isSubmitted() && $form->isValid() ){
            /**
             * Pour chaque image ajoutée à l'entité $article,
             * définir la propriété Article dans l'entité Image et persister l'entité Image
             */
            foreach($article->getImages() as $image){
                $image->setArticle($article);
                $manager->persist($image);
            }

            
            $manager->persist($article);
            $manager->flush();

            $this->addFlash('success', "L'article <strong>{$article->getTitle()}</strong> a bien été enregistré !");

            return $this->redirectToRoute('articles_show', [
                'slug' => $article->getSlug()
            ]);
        }

        return $this->render('article/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/articles/{slug}", name="articles_show")
     *
     */
    public function show( Article $article ){

        // $article = $repo->findOneBySlug($slug);
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * Permet d'éditer un article
     * 
     * @Route("/articles/{slug}/edit", name="articles_edit")
     *
     * @param Article $article
     * @return Response
     */
    public function edit(Article $article, Request $request, ObjectManager $manager){

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid() ){
            /**
             * Pour chaque image ajoutée à l'entité $article,
             * définir la propriété Article dans l'entité Image et persister l'entité Image
             */
            foreach($article->getImages() as $image){
                $image->setArticle($article);
                $manager->persist($image);
            }

            
            $manager->persist($article);
            $manager->flush();

            $this->addFlash('success', "L'article <strong>{$article->getTitle()}</strong> a bien été modifié !");

            return $this->redirectToRoute('articles_show', [
                'slug' => $article->getSlug()
            ]);
        }

        return $this->render("article/edit.html.twig", [
            'form' => $form->createView(),
            'article' => $article
        ]);
    }
}