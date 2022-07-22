<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Video;
use App\Form\UserType;
use App\Utils\CategoryTreeAdminOptionList;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class MainController extends AbstractController
{
    private ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    #[Route('/', name: 'admin_main_page')]
    public function index(Request $request, UserPasswordHasherInterface $password_encoder, UserInterface $user): Response
    {

        $user = $this->getUser();

        $form = $this->createForm(UserType::class, $user, ['user'=>$user]);
        $form->handleRequest($request);
        $is_invalid = null;

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->doctrine->getManager();
            $user->setName($request->request->all('user')['name']);
            $user->setLastName($request->request->all('user')['last_name']);
            $user->setEmail($request->request->all('user')['email']);
            $password = $password_encoder->hashPassword($user,
            $request->request->all('user')['password']['first']);
            $user->setPassword($password);
            $entityManager->persist($user);
            $entityManager->flush();


            $this->addFlash(
                'success',
                'Your changes were saved!'
            );

            return $this->redirectToRoute('admin_main_page');

        } elseif ($request->isMethod('post')) {

            $is_invalid = 'is-invalid';

        }

        return $this->render('admin/my_profile.html.twig', [
            'subscription' => $this->getUser()->getSubscription(),
            'form' => $form->createView(),
            'is_invalid' => $is_invalid
        ]);
    }

    #[Route('/delete-account', name: 'delete_account')]
    public function deleteAccount()
    {
        $em = $this->doctrine->getManager();
        $user = $em->getRepository(User::class)->find($this->getUser());

        $em->remove($user);

        $em->flush();

        session_destroy();

        return $this->redirectToRoute('app_front');

    }

    #[Route('/videos', name: 'videos')]
    public function videos(CategoryTreeAdminOptionList $categories): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {

//            $videos = $this->doctrine->getRepository(Video::class)->findAll();

            $categories->getCategoryList($categories->buildTree());
            $videos =$this->doctrine->getRepository(Video::class)->findBy([], ['title'=>'ASC']);

        } else {

            $categories = null;
            $videos = $this->getUser()->getLikedVideos();

        }

        return $this->render('admin/videos.html.twig', [
            'videos' => $videos,
            'categories' => $categories
        ]);
    }

//    public function getAllCategories(CategoryTreeAdminOptionList $categories, $editedCategory = null): Response
//    {
//        $this->denyAccessUnlessGranted('ROLE_ADMIN');
//
//        $categories->getCategoryList($categories->buildTree());
//        return $this->render('admin/_all_categories.html.twig', [
//            'categories' => $categories,
//            'editedCategory' => $editedCategory
//        ]);
//
//    }

    #[Route('/cancel-plan', name: 'cancel_plan')]
    public function cancelPlan(): RedirectResponse
    {
        $user = $this->doctrine->getRepository(User::class)->find($this->getUser());

        $subscription = $user->getSubscription();
        $subscription->setValidTo(new \DateTime());
        $subscription->setPaymentStatus(null);
        $subscription->setPlan('canceled');

        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($subscription);
        $entityManager->persist($user);

        $entityManager->flush();

        return $this->redirectToRoute('admin_main_page');

    }
}
