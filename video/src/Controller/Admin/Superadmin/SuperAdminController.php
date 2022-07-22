<?php

namespace App\Controller\Admin\Superadmin;

use App\Entity\Category;
use App\Entity\User;
use App\Entity\Video;
use App\Form\VideoType;
use App\Utils\Interfaces\UploaderInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SuperAdminController extends AbstractController
{
    private ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

//    #[Route('/su/upload-video', name: 'upload_video')]
//    public function uploadVideo(): Response
//    {
//        return $this->render('admin/upload_video.html.twig');
//    }

    #[Route('/su/upload-video-locally', name: 'upload_video_locally')]
    public function uploadVideoLocally(Request $request, UploaderInterface $fileUploader): Response
    {
        $video = new Video();
        $form = $this->createForm(VideoType::class, $video);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->doctrine->getManager();

            $file = $video->getUploadedVideo();

//            $fileName = 'to do';
            $fileName = $fileUploader->upload($file);

            $base_path = Video::uploadFolder;
            $video->setPath($base_path.$fileName[0]);
            $video->setTitle($fileName[1]);

            $em->persist($video);
            $em->flush();

            return $this->redirectToRoute('videos');

        }

        return $this->render('admin/upload_video_locally.html.twig', [
            'form' => $form->createView()
        ]);
    }


    #[Route('/su/delete-video/{video}', name: 'delete_video', requirements: ['path'=>'.+'])]
    public function deleteVideo(Video $video, UploaderInterface $fileUploader): RedirectResponse
    {
        $path = $video->getPath();
        $em = $this->doctrine->getManager();
        $em->remove($video);
        $em->flush();

        if ($fileUploader->delete($path)) {

            $this->addFlash(
                'success',
                'The video was successfully deleted.'
            );

        } else {

            $this->addFlash(
                'danger',
                'We were not able to delete. Check the video.'
            );

        }

        return $this->redirectToRoute('videos');

    }

    #[Route('/su/users', name: 'users')]
    public function users(): Response
    {
//        return $this->render('admin/users.html.twig');

        $repository = $this->doctrine->getRepository(User::class);
        $users = $repository->findBy([], ['name' => 'ASC']);

        return $this->render('admin/users.html.twig', [
            'users' => $users
        ]);

    }

    #[Route('/su/delete-user/{user}', name: 'delete_user')]
    public function deleteUser(User $user): RedirectResponse
    {

        $manager = $this->doctrine->getManager();
        $manager->remove($user);
        $manager->flush();

        return $this->redirectToRoute('users');

    }

    #[Route('/su/update-video-category/{video}', name: 'update_video_category')]
    public function updateVideoCategory(Request $request, Video $video): RedirectResponse
    {

        $em = $this->doctrine->getManager();

        $category = $this->doctrine->getRepository(Category::class)->find($request->request->get('video_category'));

        $video->setCategory($category);

        $em->persist($video);
        $em->flush();

        return $this->redirectToRoute('videos');

    }

    // c_113
//    #[Route('/su/set-video-duration/{video}/{vimeo_id}', name: 'set-video-duration', requirements: ['vimeo_id'=>'.+'])]
//    public function setVideoDuration(Video $video, $vimeo_id)
//    {
//        if (!is_numeric($vimeo_id)) {
//
//            // you can handle here setting duration for locally stored files
//            // ...
//
//            return $this->redirectToRoute('videos');
//
//        }
//
//        $user_vimeo_token = $this->getUser()->getVimeoApiKey();
//        $curl = curl_init();
//
//        curl_setopt_array($curl, [
//            CURLOPT_URL => 'https://api.vimeo.com/videos/{$vimeo_id}',
//            CURLOPT_RETURNTRANSFER => true,
//            CURLOPT_ENCODING => '',
//            CURLOPT_MAXREDIRS => 10,
//            CURLOPT_TIMEOUT => 30,
//            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//            CURLOPT_CUSTOMREQUEST => 'GET',
//            CURLOPT_HTTPHEADER => [
//                "Accept: application/vnd.vimeo.*+json;version=3.4",
//                "Authorization: Bearer $user_vimeo_token",
//                "Cache-Control: no-cache",
//                "Content-Type: application/x-www-form-urlencoded"
//            ]
//        ]);
//
//    }

}