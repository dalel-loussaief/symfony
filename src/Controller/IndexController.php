<?php
namespace App\Controller;
use App\Entity\Article; 
use App\Entity\Category;
use App\Form\ArticleType;
use App\Form\CategoryType;
use App\Entity\CategorySearch;
use App\Entity\PropertySearch;
use App\Form\PropertySearchType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\CategorySearchType;
use App\Entity\PriceSearch;
use App\Form\PriceSearchType;


class IndexController extends AbstractController
{
   
 public function home(Request $request,ManagerRegistry $entityManager ):Response { 
   //$articles= ['article1','article2','article3'];
    #return $this->render('index.html.twig',['articles'=>$articles]);
    //$articles = $entityManager->getRepository(Article::class)->findAll();
    //return $this->render('index.html.twig', ['articles' => $articles]);

 
    $propertySearch = new PropertySearch();
    $form = $this->createForm(PropertySearchType::class,$propertySearch);
    $form->handleRequest($request);
    $articles= [];
    
    if($form->isSubmitted() && $form->isValid()) {
    $name = $propertySearch->getname(); 
    if ($name!="")
    $article = $entityManager->getRepository(Article::class)->findBy(['name'=> $name]);
  else 
  
    $articles= $entityManager->getRepository(Article::class)->findAll();
  }
    return $this->render('index.html.twig',[ 'form' =>$form->createView(), 'articles' => $articles]); 


 }


 
 public function save(EntityManagerInterface $entityManager):Response
  {
 $article = new Article();
 $article->setname('Article 4');
 $article->setPrix(5000);
 $entityManager->persist($article);
 $entityManager->flush();
 return new Response('Article enregisté avec id '.$article->getId());
 }

 /**
  * Method({"GET", "POST"}) */ 

 public function new(Request $request,EntityManagerInterface $entityManager): Response
{
  $article = new Article();
  $form = $this->createForm(ArticleType::class,$article);
  $form->handleRequest($request);
  if($form->isSubmitted() && $form->isValid()) {
  $article = $form->getData();
  //$entityManager = $this-> $entityManager->getManager();
  $entityManager->persist($article);
  $entityManager->flush();
  return $this->redirectToRoute('article_list');
  }
  return $this->render('articles/new.html.twig',['form' => $form->createView()]);
  }
 
  


  public function show($id,ManagerRegistry $entityManager): Response {
    $article = $entityManager->getRepository(Article::class)->find($id);
    return $this->render('articles/show.html.twig', array('article' => $article));
    }
/**
    *Method({"GET", "POST"})
*/
public function edit(Request $request, $id,EntityManagerInterface $entityManager) {
  $article = new Article();
  $article = $entityManager->getRepository(Article::class)->find($id);
  
  $form = $this->createForm(ArticleType::class,$article);
  $form->handleRequest($request);
  if($form->isSubmitted() && $form->isValid()) {
 
    //$entityManager = $this->$entityManager->getManager();
    $entityManager->flush();
    
    return $this->redirectToRoute('article_list');
    }
    
    return $this->render('articles/edit.html.twig', ['form' =>$form->createView()]);
    }
    /**
     * *Method({"GET", "DELETE"})
     *  @Method({"DELETE"})
  
     */
  public function delete(EntityManagerInterface $entityManager,Request $request, $id) {
    $article =$entityManager->getRepository(Article::class)->find($id);
    
    //$entityManager = $this->$entityManager->getManager();
    $entityManager->remove($article);
    $entityManager->flush();
    
    $response = new Response();
    $response->send();
    return $this->redirectToRoute('article_list');
    }


    /**
     *  Method({"GET", "POST"})
     */
    public function newCategory(Request $request,EntityManagerInterface $entityManager): Response {
      $category = new Category();
      $form = $this->createForm(CategoryType::class,$category);
      $form->handleRequest($request);
      if($form->isSubmitted() && $form->isValid()) {
      $article = $form->getData();
     // $entityManager = $this->$entityManager->getManager();
      $entityManager->persist($category);
      $entityManager->flush();
      }
     return $this->render('Articles/newCategory.html.twig',['form'=>$form->createView()]);
      }

/**
 * * Method({"GET", "POST"})
 */
      public function articlesParCategorie(Request $request,EntityManagerInterface $entityManager) {
        $categorySearch = new CategorySearch();
        $form = $this->createForm(CategorySearchType::class,$categorySearch);
        $form->handleRequest($request);
        $articles= [];
        if($form->isSubmitted() && $form->isValid()) {
          $category = $categorySearch->getCategory();
          
          if ($category!="")
         $articles= $category->getArticles();
          else 
          $articles= $entityManager->getRepository(Article::class)->findAll();
          }
          
          return $this->render('Articles/articlesParCategorie.html.twig',['form' => $form->createView(),'articles' => $articles]);
          }



/**
 * * Method({"GET", "POST"})
 */
          public function articlesParPrix(Request $request,EntityManagerInterface $entityManager)
{
          $PriceSearch = new PriceSearch();
          $form = $this->createForm(PriceSearchType::class,$PriceSearch);
          $form->handleRequest($request);
          $articles= [];
          if($form->isSubmitted() && $form->isValid()) {
          $minPrice = $PriceSearch->getMinPrice();
          $maxPrice = $PriceSearch->getMaxPrice();
          $articles = $entityManager->getRepository(Article::class)->findByPriceRange($minPrice, $maxPrice);
}
          return $this->render('articles/articleParPrix.html.twig',[ 'form' =>$form->createView(), 'articles' => $articles]);
}


}
?>
