<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;
use App\Form\UserType;

class UserSecurityController extends AbstractController
{
    /**
     * @Route("/user/security", name="user_security")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        
        return $this->render('User/login.html.twig', [
            'last_username' => $lastUsername, 
            'error' => $error
        ]);
    }
    
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
    
    public function registration(Request $request, UserPasswordEncoderInterface $passwordEncoder) : ?Response 
    {
        $entity = new User();
        
        $form = $this->createForm(UserType::class, $entity);
        $form->add('submit', SubmitType::class, ['label' => 'Create user']);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($entity, $entity->getPlainPassword());
            $entity->setPassword($password);
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($entity);
            $entityManager->flush();
            
            $this->addFlash('success', 'Success!');
            
            if ($form->get('submit')->isClicked()) {
                return $this->redirectToRoute('user_login');
            }
        }
        
        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Error.');
        }
        
        return $this->render('User/registration.html.twig', [
            'entity' => $entity,
            'form' => $form->createView()
        ]);
    }
}
