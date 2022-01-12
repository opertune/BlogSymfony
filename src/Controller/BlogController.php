<?php
namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     */
    public function index(ArticleRepository $repo): Response {
        $articles = $repo->findAll();
        
        return $this->render('blog/index.html.twig',[
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/", name="home")
     */
    public function home(): Response {
        return $this->render('blog/home.html.twig');
    }

    /**
     * @Route("/blog/new", name="blog_create")
     */
    public function create(Article $article = null, Request $request, EntityManagerInterface $manager): Response{
        //dump($request);
        // METHOD HTML
        // if($request->request->count() > 0){
        //     $article = new Article();
        //     $article->setTitle($request->request->get('title'))
        //             ->setContent($request->request->get('content'))
        //             ->setImage($request->request->get('image'))
        //             ->setCreateAt(new DateTime());

        //     $manager->persist($article);
        //     $manager->flush();
        //     return $this->redirectToRoute('blog_show',[
        //         'id'=>$article->getID()
        //     ]);
        // }
        if(!isset($article)){
            $article = new Article();
        }
        
        // Form avec un objet 
        $form = $this->createFormBuilder($article)
        ->add('title')
        ->add('content')
        ->add('image')
        ->getForm();

        $form->handleRequest($request);
        // On vérifie que le form est valide
        if($form->isSubmitted() && $form->isValid()){
            $article->setCreateAt(new DateTime());
            $manager->persist($article);
            $manager->flush();
            return $this->redirectToRoute('blog_show', [
                'id' => $article->getId()
            ]);
        }



        return $this->render('blog/create.html.twig',[
            'formArticle' => $form->createView()
        ]);
    }

    /**
     * @Route("/blog/{id}", name="blog_show")
     */
    public function show(Article $article): Response {
        return $this->render("blog/show.html.twig",[
            'article' => $article
        ]);
    }
}
