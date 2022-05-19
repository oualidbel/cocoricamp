<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/security', name: 'app_security')]
    public function index(): Response
    {
        return $this->render('security/index.html.twig', [
            'controller_name' => 'SecurityController',
        ]);
    }

    #[Route("/inscription", name: 'app_register', methods: ['GET', 'POST'])]
    public function register(Request $request, UserPasswordHasherInterface $hasher, EntityManagerInterface $manager): Response
    {   
        //On crée un nouvel exemplaire de l'entité 'User', afin de pouvoir remplir l'objet via le formulaire, puis insertions en BDD.

        $user = new User();

        //On exécute la method createFom (de la classe AbstractController), afin de créer un formulaire en rapport avec la classe 'RegistrationType' pour utiliser les getters et setters afin de remplir l'objet $user.

        $form = $this->createForm(RegistrationType::class, $user, [
            'validation_groups' => ['registration', 'default']
        ]);

        // 

        //Nous définissons un groupe de validation de contraintes afin qu'elle ne soient prise en compte uniquement lors de l'inscription et non lors de la modification dans le backOffice.

        $form->handleRequest($request);

        dump($request);

        if($form->isSubmitted() && $form->isValid()){

            //si le formulaire a bien été validé (isSubmitted) et chaque champs a bien été rempli et qu'ils corespondent aux bon setters de l'objet '$user', alors on entre ici dans le if.

            // En cas d'éffraction de la base de données le hacker aurais accès aux mots de passes des utilisateurs, donc on préferrera hacher les mdp, pour ce la symfony dispose de plusieurs composants et interfaces dont "UserPasswordEncoderInterface".

            $hash = $hasher->hashPassword($user, $user->getPassword());

            $user->setPassword($hash);
            $user->setRoles(["ROLE_USER"]);

            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', 'Félicitations, votre compte a bien été créé. Vous pouvez dès à présent vous connecter');

            return $this->redirectToRoute('app_login');

            /* dump($user); */
        }

        return $this->render('security/register.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route(path: '/s\'identifier', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
