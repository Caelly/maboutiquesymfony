<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager=$entityManager;
    }

    /**
     * @Route("/mon-panier", name="cart")
     */
    public function index(Cart $cart): Response
    {
        // De base on a une session de panier qui est de la forme id => quantity (ex : 3 => 2 pour dire qu'on a deux exemplaires du produit 3)
        $cartComplete = [];
        
        if($cart->get())
        {
            foreach($cart->get() as $id => $quantity) // Pour chaque enregistrement dans la session de panier je veux que tu comprennes que ce qu'il y a à gauche est un id et ce qu'il y a à droite une quantité
            {
                $cartComplete[] = [
                    'product' => $this->entityManager->getRepository(Product::class)->findOneById($id), // Je vais chercher l'objet Produit en fonction de l'id trouvé à l'éxécution de ma boucle
                    'quantity' => $quantity // J'affecte ma quantité à cet objet
                ];
            }
        }

        return $this->render('cart/index.html.twig', [
            'cart' => $cartComplete
        ]);
    }

    /**
     * @Route("/cart/add/{id}", name="add_to_cart")
    */
    public function add(Cart $cart, $id)
    {
        $cart->add($id);
        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("/cart/remove", name="remove_my_cart")
    */
    public function remove(Cart $cart)
    {
        $cart->remove();

        return $this->redirectToRoute('products');
    }

    /**
     * @Route("/cart/delete/{id}", name="delete_to_cart")
    */
    public function delete(Cart $cart, $id)
    {
        $cart->delete($id);
        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("/cart/decrease/{id}", name="decrease_to_cart")
    */
    public function decrease(Cart $cart, $id)
    {
        $cart->decrease($id);

        return $this->redirectToRoute('cart');
    }

    
}
