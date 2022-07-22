<?php

namespace App\Controller;

use App\Entity\Task;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ToDoListController extends AbstractController
{

    private ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    #[Route('/', name: 'app_to_do_list')]
    public function index(): Response
    {
        $entityManager = $this->doctrine->getManager();
//        $tasks = $entityManager->getRepository(Task::class)->findAll();
        $tasks = $entityManager->getRepository(Task::class)->findBy([], ['id'=>'DESC']);

        return $this->render('to_do_list/index.html.twig', [
            'tasks' => $tasks,
        ]);
    }

    #[Route('/create', name: 'create_task', methods: ['post'])]
    public function create(Request $request): RedirectResponse
    {
        $title = trim($request->request->get('title'));
        if (empty($title)) {
            return $this->redirectToRoute('app_to_do_list');
        }

        $entityManager = $this->doctrine->getManager();

        $task = new Task();
        $task->setTitle($title);
        $entityManager->persist($task);
        $entityManager->flush();

        return $this->redirectToRoute('app_to_do_list');

    }

    #[Route('/switch-status/{id}', name: 'switch_status')]
    public function switchStatus($id): RedirectResponse
    {
        $entityManager = $this->doctrine->getManager();
        $task = $entityManager->getRepository(Task::class)->find($id);

        $task->setStatus( ! $task->getStatus() );
        $entityManager->flush();

        return $this->redirectToRoute('app_to_do_list');

    }

    #[Route('/delete/{id}', name: 'task_delete')]
    public function delete(Task $id): RedirectResponse
    {
        $entityManager = $this->doctrine->getManager();
        $entityManager->remove($id);
        $entityManager->flush();

        return $this->redirectToRoute('app_to_do_list');
    }
}
