<?php

namespace App\Controller;

use App\Controller\Traits\SaveSubscription;
use App\Entity\Subscription;
use App\Entity\User;
use App\Form\UserType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class SecurityController extends AbstractController
{
    use SaveSubscription;

    private ManagerRegistry $doctrine;
    private RequestStack $requestStack;

    public function __construct(ManagerRegistry $doctrine, RequestStack $requestStack)
    {
        $this->doctrine = $doctrine;
        $this->requestStack = $requestStack;
    }

//    #[Route('/register', name: 'register')]
    #[Route('/register/{plan}', name: 'register', defaults: ["plan"=>null])]
//    public function register(Request $request, UserPasswordHasherInterface $password_encoder): Response
    public function register(Request $request, UserPasswordHasherInterface $password_encoder, SessionInterface $session, $plan): Response
    {
        if ($request->isMethod('GET')) {

            $session->set('planName', $plan);
            $session->set('planPrice', Subscription::getPlanDataPriceByName($plan));

        }

        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->doctrine->getManager();

            $user->setName($request->request->all('user')['name']);
            $user->setLastName($request->request->all('user')['last_name']);
            $user->setEmail($request->request->all('user')['email']);
            $password = $password_encoder->hashPassword($user, $request->request->all('user')['password']['first']);
            $user->setPassword($password);
            $user->setRoles(['ROLE_USER']);


            // c 93
            $date = new \DateTime();
            $date->modify('+1 month');
            $subscription = new Subscription();
            $subscription->setValidTo($date);
            $subscription->setPlan($session->get('planName'));

            if ($plan == Subscription::getPlanDataNameByIndex(0)) {

                $subscription->setFreePlanUsed(true);
                $subscription->setPaymentStatus('paid');

            }

            $user->setSubscription($subscription);
            // c_93

            $entityManager->persist($user);
            $entityManager->flush();

            $this->loginUserAutomatically($user, $password);

            return $this->redirectToRoute('admin_main_page');

        }


        if ($this->isGranted('IS_AUTHENTICATED_REMEMBERED') && $plan == Subscription::getPlanDataNameByIndex(0)) {

            $this->saveSubscription($plan, $this->getUser());

            return $this->redirectToRoute('admin_main_page');

        } elseif ($this->isGranted('IS_AUTHENTICATED_REMEMBERED')) {

            return $this->redirectToRoute('payment');

        }



        return $this->render('front/register.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/login', name: 'login')]
    public function login(AuthenticationUtils $helper): Response
    {
        return $this->render('front/login.html.twig', [
            'error' => $helper->getLastAuthenticationError()
        ]);
    }

    private function loginUserAutomatically($user, $password)
    {
        $token = new UsernamePasswordToken($user, $password, $user->getRoles());

        $this->container->get('security.token_storage')->setToken($token);
//        $this->container->get('session')->set('_security_main', serialize($token));
        $this->requestStack->getSession()->set('_security_main', serialize($token));

    }

    #[Route('/logout', name: 'logout')]
    public function logout(): void
    {
        throw new \Exception('This should never be reached!');
    }

    #[Route('/payment', name: 'payment')]
    public function payment(): Response
    {
        return $this->render('front/payment.html.twig');
    }

}