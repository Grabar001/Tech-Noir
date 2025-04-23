<?php

namespace App\Service;

use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class CartService
{
    private $requestStack;
    private $produitRepository;

    public function __construct(RequestStack $requestStack, ProduitRepository $produitRepository)
    {
        $this->requestStack = $requestStack;
        $this->produitRepository = $produitRepository;
    }

    /**
     * Получаем текущую корзину из сессии
     */
    private function getSessionCart(): array
    {
        return $this->requestStack->getSession()->get('cart', []);
    }

    /**
     * Сохраняем корзину в сессию
     */
    private function saveSessionCart(array $cart): void
    {
        $this->requestStack->getSession()->set('cart', $cart);
    }

    /**
     * Добавить товар по ID
     */
    public function add(int $id): void
    {
        $cart = $this->getSessionCart();

        if (!isset($cart[$id])) {
            $cart[$id] = 1;
        } else {
            $cart[$id]++;
        }

        $this->saveSessionCart($cart);
    }

    /**
     * Удалить товар по ID
     */
    public function remove(int $id): void
    {
        $cart = $this->getSessionCart();

        if (isset($cart[$id])) {
            unset($cart[$id]);
        }

        $this->saveSessionCart($cart);
    }

    /**
     * Очистить корзину
     */
    public function clear(): void
    {
        $this->saveSessionCart([]);
    }

    /**
     * Получить полную корзину (товары + количество)
     */
    public function getFullCart(): array
    {
        $cart = $this->getSessionCart();
        $cartWithData = [];

        foreach ($cart as $id => $quantity) {
            $produit = $this->produitRepository->find($id);

            if ($produit) {
                $cartWithData[] = [
                    'produit' => $produit,
                    'quantity' => $quantity,
                    'total' => $produit->getPrixAvecReduction() * $quantity,
                ];
            }
        }

        return $cartWithData;
    }

    /**
     * Получить общую сумму корзины
     */
    public function getTotal(): float
    {
        $total = 0;

        foreach ($this->getFullCart() as $item) {
            $total += $item['total'];
        }

        return $total;
    }
}