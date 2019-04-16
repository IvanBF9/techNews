<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 15/04/2019
 * Time: 14:46
 */

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /*
     * Page d'accueil
     */
    public function index(){
        return $this->render("default/index.html.twig");
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
        return $this->render("default/categorie.html.twig");
        return new Response("<html><body><h1>Page categorie: $slug</h1></body></html>");
    }

    /**
     * Page permettant d'afficher un article.
     * @Route("/{categorie}/{slug}_{id<\d+>}.html",
     *     name="default_article")
     */
    public function article($categorie, $slug, $id){
        #Examle d'URL:
        # /politique/macron_498498.html
        return $this->render('default/article.html.twig');
    }
}