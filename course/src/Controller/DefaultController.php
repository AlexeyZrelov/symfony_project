<?php

namespace App\Controller;

use App\Entity\Pdf;
use App\Entity\File;
use App\Entity\User;
use App\Entity\Video;
use App\Entity\Author;
use App\Entity\Address;
use App\Form\VideoFormType;
use App\Services\MyService;
use App\Entity\SecurityUser;
use App\Form\RegisterUserType;
use App\Services\GiftsService;
use App\Services\MySecondService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Cache\Adapter\TagAwareAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class DefaultController extends AbstractController
{

    // public function __construct(GiftsService $gifts)
    public function __construct(EventDispatcherInterface $dispather)
    {
        $this->dispatcher = $dispather;
    }

    #[Route('/home', name: 'home')]
    public function index(ManagerRegistry $doctrine, Request $request, UserPasswordHasherInterface $passwordEncoder): Response
    {

        $entityManager = $doctrine->getManager();
        $users = $entityManager->getRepository(SecurityUser::class)->findAll();
        dump($users);

    

        //=========
        // $user = new SecurityUser();

        // $plaintextPassword = 'pwd123';
        // $hashedPassword = $passwordEncoder->hashPassword($user, $plaintextPassword);

        // $form = $this->createForm(RegisterUserType::class, $user);
        // $form->handleRequest($request);

        // if ($form->isSubmitted() && $form->isValid()) {
        //     $user->setPassword($hashedPassword);
        //     $form->get('password')->getData();

        //     $user->setEmail($form->get('email')->getData());

        //     $entityManager = $doctrine->getManager();
        //     $entityManager->persist($user);
        //     $entityManager->flush();

        //     return $this->redirect('home');

        // }
        //==========
        

        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
            // 'form' => $form->createView(),
        ]);

    }

    #[Route('/login', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login/index.html.twig', [
            'controller_name' => 'LoginController',
            'last_username' => $lastUsername,
            'error'         => $error, 
        ]);

    }

//    #[Route('/default2', name: 'app_default2')]
//    public function index2(): Response
//    {
//        return new Response('I am from default2 route!');
//    }

    #[Route('/generate-url/{param?}', name: 'generate_url')]
    public function generate_url()
    {
        exit($this->generateUrl(
            'generate_url',
            ['param' => 10],
            UrlGeneratorInterface::ABSOLUTE_URL
        ));
    }

    #[Route('/download')]
    public function download()
    {
        $path = $this->getParameter('download_directory');
        return $this->file($path.'file.pdf');
    }

    #[Route('/redirect-test')]
    public function redirectTest()
    {
        return $this->redirectToRoute('route_to_redirect', ['param' => 10]);
    }

    #[Route('/url-to-redirect/{param?}', name: 'route_to_redirect')]
    public function methodToRedirect()
    {
        exit('Test redirection');
    }

    // #[Route('/forwarding-to-controller')]
    // public function forwardingToController()
    // {
    //     $response = $this->forward(
    //         'App\Controller\DefaultController::methodToForwardTo',
    //         ['param' => '1']
    //     );
    //     return $response;
    // }

    public function methodToForwardTo($param)
    {
        exit('Test controller forwarding - '.$param);
    }

    #[Route('blog/{page}', name: 'blog_list', requirements: ['page' => '\d+'])]
    public function index2(): Response
    {
        return new Response('Optional parameters in url and requirements for parameters');
    }

    public function mostPopularPosts($number=3): Response
    {
        // database call
        $posts = ['post 1', 'post 2', 'post 3', 'post 4'];
        return $this->render('default/most_popular_posts.html.twig', [
            'posts' => $posts,
        ]);
    }

}
