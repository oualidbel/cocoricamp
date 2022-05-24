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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountController extends AbstractController
{
    #[Route('/mon-compte', name: 'app_account')]
    public function index(UserRepository $users): Response
    {
        $user = $this->getUser();

        $id = $user->getId();

        return $this->render('account/index.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/account/edit', name: 'app_account_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    /* public function update(ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $user = $entityManager->getRepository(User::class)->find($id);

        $user->setLastname();
        $entityManager->flush();

        return $this->redirectToRoute('product_show', [
            'id' => $product->getId()
        ]);
    } */
    public function editUser(Request $request, User $user, EntityManagerInterface $manager, int $id)
    {
        $id = $user->getId();

        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setLastname();
            $user->setFirstname();
            $user->setEmail();
            
            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute('app_account');
        }

        return $this->render('account/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
