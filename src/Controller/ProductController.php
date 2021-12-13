<?php

namespace App\Controller;

use SearchType;
use App\Classe\Search;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager=$entityManager;
    }

    /**
     * @Route("/nos-produits", name="products")
     */
    public function index(Request $request): Response
    {
        $search = new Search();
        $form = $this->createForm(SearchType::class, $search);

        $form->handleRequest($request); // écoute si le formulaire est envoyé

        if($form->isSubmitted() && $form->isValid())
        {
            $products = $this->entityManager->getRepository(Product::class)->findWithSearch($search);
        }
        else 
        {
            $products = $this->entityManager->getRepository(Product::class)->findAll();
        }

        return $this->render('product/index.html.twig', [
            'products' => $products, 
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/produit/{slug}", name="product")
     */
    public function show($slug): Response
    {
        $product = $this->entityManager->getRepository(Product::class)->findOneBySlug($slug);
        if(!$product)
        {
            return $this->redirectToRoute('products');
        }
        else
        {
            return $this->render('product/show.html.twig', [
                'product' => $product
            ]);
        }
    }

}
