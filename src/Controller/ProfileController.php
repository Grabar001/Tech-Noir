<?php

namespace App\Controller;

use index;
use App\Entity\Commande;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        return $this->render('profil/index.html.twig', [
            'user' => $this->getUser(),
        ]);
    }

    #[Route('/profil/commandes', name: 'profil_commandes')]
    public function commandes(EntityManagerInterface $em): Response
    {
        $commandes = $em->getRepository(Commande::class)->findBy([], ['createdAt' => 'DESC']);

        return $this->render('profil/commandes.html.twig', [
            'commandes' => $commandes
        ]);
    }

}
