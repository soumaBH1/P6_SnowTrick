<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\Persistence\ObjectManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityController extends AbstractController
{
    #[Route('/inscription', name: 'security_registration')]
    public function registration(LoggerInterface $logger, Request $request, ObjectManager $manager, UserPasswordHasherInterface $passwordHasher)
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $logger->debug("test registration");
        $form->handleRequest($request); //analiser la request
        //$logger->debug("form is submitted " . $form->isSubmitted());
       // $logger->debug("form is valid " . $form->isValid());
        if($form->isSubmitted() && $form->isValid()){
            $logger->debug("test registration2");
            $hash = $passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hash);
            $manager->persist($user);
            $manager->flush();
            
        }
        return $this->render('security/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
