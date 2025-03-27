<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Commande;
use App\Entity\CommandeProduit;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'cart')]
    public function cart(SessionInterface $session, ProduitRepository $produitRepository): Response
    {
        $cart = $session->get('cart', []);
        $cartData = [];
    $total = 0;

    foreach ($cart as $id => $quantity) {
        $produit = $produitRepository->find($id);
        if (!$produit) continue;

        $cartData[] = [
            'produit' => $produit,
            'quantite' => $quantity
        ];

        $total += $produit->getPrix() * $quantity;
    }

    return $this->render('pages/cart.html.twig', [
        'title' => 'Votre Panier - TECH NOIR',
        'cart' => $cartData,
        'total' => $total,
    ]);
    }

    #[Route('/cart/add/{id}', name: 'cart_add')]
    public function addToCart(int $id, SessionInterface $session): Response
    {
        $cart = $session->get('cart', []);

        if (!isset($cart[$id])) {
            $cart[$id] = 1;
        } else {
            $cart[$id]++;
        }

        $session->set('cart', $cart);

        return $this->redirectToRoute('cart');
    }

    #[Route('/cart/remove/{id}', name: 'cart_remove')]
    public function removeFromCart(int $id, SessionInterface $session): Response
    {
        $cart = $session->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
        }

        $session->set('cart', $cart);

        return $this->redirectToRoute('cart');
    }

    #[Route('/checkout', name: 'checkout')]
public function checkout(SessionInterface $session, EntityManagerInterface $em, ProduitRepository $produitRepository): Response
{
    $cart = $session->get('cart', []);

    if (empty($cart)) {
        $this->addFlash('warning', 'Votre panier est vide.');
        return $this->redirectToRoute('catalog');
    }

    $commande = new Commande();
    $commande->setCreatedAt(new \DateTimeImmutable());

    $total = 0;

    foreach ($cart as $id => $quantite) {
        $produit = $produitRepository->find($id);
        if (!$produit) continue;

        $commandeProduit = new CommandeProduit();
        $commandeProduit->setProduit($produit);
        $commandeProduit->setQuantite($quantite);
        $commandeProduit->setCommande($commande);

        $total += $produit->getPrix() * $quantite;

        $em->persist($commandeProduit);
    }

    $commande->setTotal($total);
    $em->persist($commande);
    $em->flush();

    $session->remove('cart');

    return $this->render('pages/confirmation.html.twig', [
        'commande' => $commande,
        'total' => $total
    ]);
}
    
}