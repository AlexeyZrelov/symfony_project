<?php

namespace App\Controller;

use App\Controller\Traits\Likes;
use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\User;
use App\Entity\Video;
use App\Form\UserType;
use App\Repository\VideoRepository;
use App\Utils\CategoryTreeFrontPage;
use App\Utils\VideoForNoValidSubscription;
use Doctrine\Persistence\ManagerRegistry;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security; // c_114

class FrontController extends AbstractController
{
    use Likes;

    private ManagerRegistry $doctrine;
    private $requestStack;

    public function __construct(ManagerRegistry $doctrine, RequestStack $requestStack)
    {
        $this->doctrine = $doctrine;
        $this->requestStack = $requestStack;
    }

    #[Route('/front', name: 'app_front')]
    public function index(): Response
    {
        return $this->render('front/index.html.twig');
    }

    #[Route('/video-list/category/{categoryname},{id}/{page}', name: 'video_list', defaults: ["page"=>1])]
    public function videoList($id, $page, CategoryTreeFrontPage $categories, Request $request, VideoForNoValidSubscription $video_no_members): Response
    {
        $categories->getCategoryListAndParent($id);

        $ids = $categories->getChildIds($id);
        $ids[] = $id;

        $videos = $this->doctrine
            ->getRepository(Video::class)
            ->findByChildIds($ids, $page, $request->get('sortby'));

        return $this->render('front/video_list.html.twig', [
            'subcategories' => $categories,
            'videos' => $videos,
            'video_no_members' => $video_no_members->check()
        ]);
    }

    #[Route('/video-details/{video}', name: 'video_details')]
    public function videoDetails(VideoRepository $repo, $video, VideoForNoValidSubscription $video_no_members): Response
    {
//        dump($repo->videoDetails($video));

        return $this->render('front/video_details.html.twig', [
            'video' => $repo->videoDetails($video),
            'video_no_members' => $video_no_members->check()
        ]);
    }

    #[Route('/search-results/{page}', name: 'search_results', defaults: ["page"=>"1"], methods: ['get'])]
    public function searchResults($page, Request $request, VideoForNoValidSubscription $video_no_members): Response
    {
        $videos = null;

        if ($query = $request->get('query')) {

            $videos = $this->doctrine
                ->getRepository(Video::class)
                ->findByTitle($query, $page, $request->get('sortby'));

            if (!$videos->getItems()) {

                $videos = null;

            }

        }

        return $this->render('front/search_results.html.twig', [
            'videos' => $videos,
            'query' => $query,
            'video_no_members' => $video_no_members->check()
        ]);
    }

//    #[Route('/pricing', name: 'pricing')]
//    public function pricing(): Response
//    {
//        return $this->render('front/pricing.html.twig');
//    }

//    #[Route('/register', name: 'register')]
//    public function register(Request $request, UserPasswordHasherInterface $password_encoder): Response
//    {
//        $user = new User();
//        $form = $this->createForm(UserType::class, $user);
//        $form->handleRequest($request);
//        if ($form->isSubmitted() && $form->isValid()) {
//
//            $entityManager = $this->doctrine->getManager();
//
//            $user->setName($request->request->all('user')['name']);
//            $user->setLastName($request->request->all('user')['last_name']);
//            $user->setEmail($request->request->all('user')['email']);
//            $password = $password_encoder->hashPassword($user, $request->request->all('user')['password']['first']);
//            $user->setPassword($password);
//            $user->setRoles(['ROLE_USER']);
//
//            $entityManager->persist($user);
//            $entityManager->flush();
//
//            $this->loginUserAutomatically($user, $password);
//
//            return $this->redirectToRoute('admin_main_page');
//
//        }
//        return $this->render('front/register.html.twig', [
//            'form' => $form->createView()
//        ]);
//    }
//
//    #[Route('/login', name: 'login')]
//    public function login(AuthenticationUtils $helper): Response
//    {
//        return $this->render('front/login.html.twig', [
//            'error' => $helper->getLastAuthenticationError()
//        ]);
//    }
//
//    private function loginUserAutomatically($user, $password)
//    {
//        $token = new UsernamePasswordToken($user, $password, $user->getRoles());
//
//        $this->container->get('security.token_storage')->setToken($token);
////        $this->container->get('session')->set('_security_main', serialize($token));
//        $this->requestStack->getSession()->set('_security_main', serialize($token));
//
//    }
//
//    #[Route('/logout', name: 'logout')]
//    public function logout(): void
//    {
//        throw new \Exception('This should never be reached!');
//    }
//
//    #[Route('/payment', name: 'payment')]
//    public function payment(): Response
//    {
//        return $this->render('front/payment.html.twig');
//    }

    #[Route('/new-comment/{video}', name: 'new_comment', methods: ['post'])]
    public function newComment(Video $video, Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');

        if (!empty(trim($request->request->get('comment')))) {

            $comment = new Comment();
            $comment->setContent($request->request->get('comment'));
            $comment->setUser($this->getUser());
            $comment->setVideo($video);

            $em = $this->doctrine->getManager();
            $em->persist($comment);
            $em->flush();

        }

        return $this->redirectToRoute('video_details', ['video'=>$video->getId()]);
    }

    #[Route('/video-list/{video}/like', name: 'like_video', methods: ['POST'])]
    #[Route('/video-list/{video}/dislike', name: 'dislike_video', methods: ['POST'])]
    #[Route('/video-list/{video}/unlike', name: 'undo_like_video', methods: ['POST'])]
    #[Route('/video-list/{video}/undodislike', name: 'undo_dislike_video', methods: ['POST'])]
    public function toggleLikesAjax(Video $video, Request $request): JsonResponse
    {
        $result = '';

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');

        switch ($request->get('_route')) {

            case 'like_video':
                $result = $this->likeVideo($video);
                break;

            case 'dislike_video':
                $result = $this->dislikeVideo($video);
                break;

            case 'undo_like_video':
                $result = $this->undoLikeVideo($video);
                break;

            case 'undo_dislike_video':
                $result = $this->undoDislikeVideo($video);
                break;
        }
        return $this->json(['action' => $result, 'id' => $video->getId()]);
    }

    public function mainCategories(): Response
    {
        $categories = $this->doctrine
            ->getRepository(Category::class)
            ->findBy(['parent'=>null], ['name'=>'ASC']);

        return $this->render('front/_main_categories.html.twig', [
            'categories' => $categories
        ]);
    }

//    private function likeVideo(Video $video): string
//    {
//        $user = $this->doctrine->getRepository(User::class)->find($this->getUser());
//        $user->addLikedVideo($video);
//
//        $em = $this->doctrine->getManager();
//        $em->persist($user);
//        $em->flush();
//
//        return 'liked';
//    }

//    private function dislikeVideo(Video $video): string
//    {
//        $user = $this->doctrine->getRepository(User::class)->find($this->getUser());
//        $user->addDislikedVideo($video);
//
//        $em = $this->doctrine->getManager();
//        $em->persist($user);
//        $em->flush();
//
//        return 'disliked';
//    }

//    private function undoLikeVideo(Video $video): string
//    {
//        $user = $this->doctrine->getRepository(User::class)->find($this->getUser());
//        $user->removeLikedVideo($video);
//
//        $em = $this->doctrine->getManager();
//        $em->persist($user);
//        $em->flush();
//
//        return 'undo liked';
//    }

//    private function undoDislikeVideo(Video $video): string
//    {
//        $user = $this->doctrine->getRepository(User::class)->find($this->getUser());
//        $user->removeDislikedVideo($video);
//
//        $em = $this->doctrine->getManager();
//        $em->persist($user);
//        $em->flush();
//
//        return 'undo disliked';
//    }

    #[Route('su/delete-comment/{comment}', name: 'delete_comment')]
    #[Security("user.getId() == comment.getUser().getId()")]
    public function deleteComment(Comment $comment, Request $request): RedirectResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');

        $em = $this->doctrine->getManager();
        $em->remove($comment);
        $em->flush();

        return $this->redirect($request->headers->get('referer'));

    }

}
