<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 15/04/2019
 * Time: 14:46
 */

namespace App\Controller;


use App\Entity\Article;
use App\Entity\Categorie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /*
     * Page d'accueil
     */
    public function index(){

        $repository = $this->getDoctrine()
            ->getRepository(Article::class);

        # je recupere touts les articles de ma base
        $articles = $repository->findAll();
        $spotlight = $repository->findBySpotlight();

        #affichage
        return $this->render("default/index.html.twig.", [
            'articles' => $articles,
            'spotlight' => $spotlight
        ]);
        #return new Response("<html><body><h1>PAGE D'ACCUEIL</h1></body></html>");
    }
    public function contact(){
        //return new Response("<html><body><h1>PAGE CONTACT</h1></body></html>");
        return $this->render("default/contact.html.twig");
    }
//

    /**
     * Page permettant d'afficher les articles d'une categorie
     * @Route("/categorie/{slug<[a-zA-Z0-9\-_\/]+>}" ,
     *     defaults={"slug"="default"},
     *     methods={"GET"},
     *     name="default_categorie")
     */
    public function categorieslug($slug){
        /*
         * Récupérer la catégorie correspondant au "slug"
         * passer en paramétre de la route .
         * ------------------------------------------
         * on récupère le paramétre "slug" de la route
         * (url) dans notre variable $slug
         */
        $categorie = $this->getDoctrine()
            ->getRepository(Categorie::class)
            ->findOneBy(['slug' => $slug]);

        /*
         * Grâce a la relation entre Article et Catégorie (OneToMany),
         * je suis en mesure de récupérer les articles d'une catégorie.
         */
        $articles = $categorie->getArticles();

        /*
         * j'envoi a ma vue les données a afficher.
         */
        return $this->render('default/categorie.html.twig', [
            'articles' => $articles,
            'categorie'=>$categorie
        ]);
        #return new Response("<html><body><h1>Page categorie: $slug</h1></body></html>");
    }

    /**
     * Page permettant d'afficher un article.
     * @Route("/{categorie}/{slug}_{id<\d+>}.html",
     *     name="default_article")
     */
    public function article($categorie, $slug, $id){
        #Examle d'URL:
        # /politique/macron_498498.html

        /*
         * Récupération de l'article correspondant a
         * l'ID en paramétre de notre route.
         */
        $article = $this->getDoctrine()
            ->getRepository(Article::class)
            ->find($id);

        return $this->render('default/article.html.twig', [
            'article' => $article
        ]);
    }
}