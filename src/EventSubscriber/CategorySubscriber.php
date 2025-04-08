<?php
namespace App\EventSubscriber;

use App\Repository\CategorieRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;

class CategorySubscriber implements EventSubscriberInterface
{
    private $twig;
    private $categorieRepository;

    public function __construct(Environment $twig, CategorieRepository $categorieRepository)
    {
        $this->twig = $twig;
        $this->categorieRepository = $categorieRepository;
    }

    public function onKernelController(ControllerEvent $event)
    {
        $categories = $this->categorieRepository->findAll();
        $this->twig->addGlobal('all_categories', $categories);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}