<?php

namespace App\Controller;

use App\Classe\Search;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use SearchType;
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
    public function index(): Response
    {
        $search = new Search();
        $products = $this->entityManager->getRepository(Product::class)->findAll();
        $form = $this->createForm(SearchType::class, $search);

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
