<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountController extends AbstractController
{
    #[Route('/account', name: 'app_account')]
    public function index(UserRepository $users): Response
    {
        $user = $this->getUser();

        $id = $user->getId();

        return $this->render('account/index.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/account/edit/{id}', name: 'app_account_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function editUser(Request $request, User $user, EntityManagerInterface $manager, int $id)
    {
        $id = $user->getId();

        $editAccount = [];

        $form = $this->createFormBuilder($editAccount)
                    ->add('lastname', TextType::class, [        'data' => $user->getLastname()
                    ])
                    ->add('firstname', TextType::class, [
                        'data' => $user->getFirstname()
                    ])
                    ->add('email', EmailType::class, [
                        'data' => $user->getEmail()
                    ])
                    ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $userData = $form->getData();
            $user->setLastname($userData['lastname']);
            $user->setFirstname($userData['firstname']);
            $user->setEmail($userData['email']);
            
            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute('app_account');
        }

        return $this->render('account/edit.html.twig', [
            'formEditAccount' => $form->createView(),
            'id' => $id,
        ]);
    }
}
