<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 17/04/2019
 * Time: 14:51
 */

namespace App\Controller;


use App\Entity\Article;
use App\Entity\Categorie;
use App\Entity\Membre;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * Demonstration de l'ajout d'un article avec Doctrine
     * @Route("/demo/article", name="article_demo")
     */

    public function demo(){
        #création d'une catégorie
        $categorie = new Categorie();
        $categorie->setNom("Politique");
        $categorie->setSlug("politique");
        # création d'un auteur (Membre)
        $membre = new Membre();
        $membre->setPrenom("Hugo");
        $membre->setNom("Michel");
        $membre->setEmail("michel@www.fr");
        $membre->setPassword("testastos");
        $membre->setRoles(['ROLE_AUTEUR']);
        $membre->setDateInscription(new \DateTime());
        #creation d'un article
        $article = new Article();
        $article->setTitre('CGygvgudshffbf bfezgnb fugfez  cyghfez f fhfhfbhdezhufbèfughz')
                ->setSlug("notre-fezfezfezf-zfzefezf-zfzefzef-zfzefz")
                ->setContenu('<p> hnrfbhefbnreu  frhv vguyghvf huvdfn vhbv ,njvddv</p>')
                ->setFeaturedImage("12.jpg")
                ->setSpotlight(1)
                ->setSpecial(0)
                ->setMembre($membre)
                ->setCategorie($categorie)
                ->setDateCreation(new \DateTime());

        /*
         * Récupération du Manager de Doctrine
         * ----------------------------------
         * Le EntityManager est une classe qui
         * sais comment persister d'autres classes.
         * (effectuer des operations CRUD sur nos entites.)
         *
         */

        $em = $this->getDoctrine()->getManager();// Permet récuperer le EntityManager de Doctrine

        $em->persist($categorie);// J'enregistre dans ma base categorie
        $em->persist($membre);
        $em->persist($article);

        $em->flush();// J'execute le tout
        #retourner une reponse a la vue
        return new Response('Nouvel Article ajouté avec ID : '
        . $article->getNom()
        . 'et la nouvelle categorie '
        . $categorie->getNom()
        . 'de Auteur : '
        . $membre->getPrenom()
        );
    }
}