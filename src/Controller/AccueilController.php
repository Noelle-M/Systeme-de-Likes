<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccueilController extends AbstractController
{
    private $entityManager;
    
    public function __construct(EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator){
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
    }


    /**
     * @Route("/", name="app_accueil")
     */
    public function index(): Response
    {
        $ListUser = $this->entityManager->getRepository(User::class)->findAll();

        return $this->render('accueil/index.html.twig', [
            'ListUser' => $ListUser,
        ]);
    }
}
