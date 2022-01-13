<?php
namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\ArticleType;
use App\Form\CommentType;
use App\Repository\ArticleRepository;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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
     * @Route("/blog/{id}/edit", name="blog_edit")
     */
    public function create(Article $article = null, Request $request, EntityManagerInterface $manager): Response{
        // dump($request);
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
        // $form = $this->createFormBuilder($article)
        // ->add('title')
        // ->add('content', TextareaType::class ,[
        //     'attr' => [
        //         'placeholder' => 'Contenu',
        //         'class' => 'form-control'
        //     ]
        // ])
        // ->add('image')
        // ->getForm();

        // $form->handleRequest($request);

        $form = $this->createForm(ArticleType::class, $article);
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
            'formArticle' => $form->createView(),
            'editMode' => $article->getId() !== null
        ]);
    }

    /**
     * @Route("/blog/{id}", name="blog_show")
     */
    public function show(Article $article, Request $request, EntityManagerInterface $manager): Response {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        // On vérifie que le form est valide
        if($form->isSubmitted() && $form->isValid()){
            $comment->setCreatedAt(new DateTime())
                    ->setArticle($article);

            $manager->persist($comment);
            $manager->flush();
            return $this->redirectToRoute('blog_show', [
                'id' => $article->getId()
            ]);
        }

        return $this->render("blog/show.html.twig",[
            'article' => $article,
            'commentForm' => $form->createView()
        ]);
    }
}
