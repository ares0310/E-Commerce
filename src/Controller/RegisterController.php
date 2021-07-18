<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{
    private $entityManager; // pour pouvoir faire plus simple, il faut instancier entityManager - 
    public function __construct(EntityManagerInterface $entityManager){         //Injection de dépendance (Classe $variable de cette classe en paramètre)

        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/inscription", name="register")
     */
    public function index(Request $request, UserPasswordEncoderInterface $encoder)
    {   

        $user = new User();
        $form = $this -> createForm(RegisterType::class, $user);        // création du formulaire

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $user = $form->getData();

            $password = $encoder->encodePassword($user, $user->getPassword());

            $user->setPassword($password);

            $this->entityManager->persist($user);           // ->persist() - enregistre cette donnée
            $this->entityManager->flush();                  // exécute cette donnée
        }

        return $this->render('register/index.html.twig', [              // affichage du formulaire
            "form" => $form->createView()
        ]);
    }
}
