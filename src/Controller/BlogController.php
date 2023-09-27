<?php

namespace App\Controller;

use DateTime;
use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitTypeType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    #[Route('/blog', name: 'blog')]
    public function index(ArticleRepository $repo): Response
    {
        // $repo = $this->getDoctrine()->getRepository(Article::class);
        $articles = $repo->findAll();
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'articles' => $articles
        ]);
    }


    #[Route('/', name: 'home')]
    public function home()
    {
        return $this->render('blog/home.html.twig', [
            'title' => "bienvenue",
            'age' => 20
        ]);
    }

    #[Route('/blog/new', name: 'blog_create')]
    #[Route('/blog/{id}/edit}', name: 'blog_edit')]
    public function form(Article $article = null, Request $request, ObjectManager $manager)
    {
        //créer et MAJ
        if ($article == null) {
            $article = new Article();
        }
        // $form = $this->createFormBuilder($article)
        //     ->add('title')
        //     ->add('content')
        //     ->add('image')
        //     ->getForm();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request); //analyser la requete http pour analyser si l'ont soumis ou envoie la requete
        dump($article);
        if ($form->isSubmitted() && $form->isValid()) {
            if (!$article->getID()) { //si l'article n'a pas d'identifiant donc il s'agit de création
                $article->setCreatedAt(new \DateTimeImmutable());
            }
            $manager->persist($article);
            $manager->flush();

            //afficher l article crée
            return $this->redirectToRoute('blog_show', ['id' => $article->getId()]);
        }
        return $this->render('blog/create.html.twig', [
            'formArticle' => $form->createView(),
            'editMode' => $article->getId() !== null
        ]);
    }
    #[Route('/blog/{id}', name: 'blog_show')]
    public function show(Article $article)
    {
        //$repo = $this->getDoctrine()->getRepository(Article::class);
        //$article = $repo->find($id);
        return $this->render('blog/show.html.twig', ['article' => $article]);
    }
}
