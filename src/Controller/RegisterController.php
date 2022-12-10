<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterController extends AbstractController
{
    private $entityManager;
    
    public function __construct(EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator){
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @Route("/register", name="register")
     */
    public function index(Request $request, UserPasswordHasherInterface $passwordHasher)
    {
        $user = $this->getUser();
        
        if($user){$emailUser = $user->getEmail();}else{$emailUser = "";}

        $notification = null;

        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $user = $form->getData();
            
            $search_email = $this->entityManager->getRepository(User::class)->findOneByEmail($user->getEmail());
            
            if(!$search_email){
                $plaintextPassword = $form->getData()->getPassword();
                $hashedPassword = $passwordHasher->hashPassword(
                    $user,
                    $plaintextPassword
                );
                $user->setPassword($hashedPassword);
                
                $this->entityManager->persist($user);
                $this->entityManager->flush();

                return new RedirectResponse($this->urlGenerator->generate('app_login'));
                    $notification = "Votre inscription a bien été enregistrée. Merci de saisir vos identifiant afin d'accéder à votre espace client";
            }else{
                $notification = "Vous avez déjà un compte enregistré avec l'adresse email ".$user->getEmail();
            }
        }
        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification,
            'emailUser' => $emailUser,
        ]);
    }
}
