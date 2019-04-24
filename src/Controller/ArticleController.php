<?php


namespace App\Controller;


use App\Entity\Article;
use App\Entity\Categorie;
use App\Entity\Membre;
use App\Form\ArticleFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    use HelperTrait;
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
    /**
     *  Formulaire pour créer un article
     * @Route("/creer-un-article", name="article_add")
     */
    public function addArticle(Request $request)
    {
        # Création d'un nouvel article
        $article = new Article();

        #Récupération d'un Auteur (Membre)
        $membre = $this->getDoctrine()
            ->getRepository(Membre::class)
            ->find(1);

        #Affecter un Auteur a l'Article
        $article->setMembre($membre);

        #Création d'un formulaire permettant l'ajout d'un article
        $form = $this->createForm(ArticleFormType::class, $article);

        # Traitement des donées POST
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
        #dump($article);
            #1. Génération du slug
            $article->setSlug($this->slugify($article->getTitre()));

            #2. Traitement de l'upload de l'image

            /** @var UploadedFile $file */
            $file = $article->getFeaturedImage();

            $fileName = $article->getSlug().'.'.$file->guessExtension();

            // Move the file to the directory where brochures are stored
            try {
                $file->move(
                    $this->getParameter('images_directory'),
                    $fileName
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }

            // updates the 'brochure' property to store the PDF file name
            // instead of its contents
            $article->setFeaturedImage($fileName);

            // ... persist the $product variable or any other work


            #3. Sauvegarde en BDD
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();
            #4. Notification
            $this->addFlash('notice',
                'Félicitations, votre article est en ligne !');
            #5. Redirection
            return $this->redirectToRoute('default_article',[
                'categorie' => $article->getCategorie()->getSlug(),
                'slug' => $article->getSlug(),
                'id' => $article->getId()
            ]);
        }

        # Affichage du formulaire dans la vue
        return $this->render("article/addform.html.twig", [
           'form' => $form->createView()
        ]);
    }
}