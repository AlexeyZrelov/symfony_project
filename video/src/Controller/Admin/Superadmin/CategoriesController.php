<?php

namespace App\Controller\Admin\Superadmin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Utils\CategoryTreeAdminList;
use App\Utils\CategoryTreeAdminOptionList;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoriesController extends AbstractController
{
    private ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    #[Route('/su/categories', name: 'categories', methods: ['get', 'post'])]
    public function categories(CategoryTreeAdminList $categories, Request $request): Response
    {
        $categories->getCategoryList($categories->buildTree());

        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $is_invalid = null;

        if ($this->saveCategory($category, $form, $request)) {

            return $this->redirectToRoute('categories');

        } elseif ($request->isMethod('post')) {

            $is_invalid = ' is-invalid';

        }

        return $this->render('admin/categories.html.twig', [
            'categories' => $categories->categorylist,
            'form'       => $form->createView(),
            'is_invalid' => $is_invalid
        ]);
    }

    #[Route('/su/edit-category/{id}', name: 'edit_category', methods: ['get', 'post'])]
    public function editCategory(Category $category, Request $request): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $is_invalid = null;

        if ($this->saveCategory($category, $form, $request)) {

            return $this->redirectToRoute('categories');

        } elseif ($request->isMethod('post')) {

            $is_invalid = ' is-invalid';

        }

        return $this->render('admin/edit_category.html.twig', [
            'category' => $category,
            'form'       => $form->createView(),
            'is_invalid' => $is_invalid
        ]);
    }

    #[Route('/su/delete-category/{id}', name: 'delete_category')]
    public function deleteCategory(Category $category): RedirectResponse
    {
        $entityManager = $this->doctrine->getManager();
        $entityManager->remove($category);
        $entityManager->flush();

        return $this->redirectToRoute('categories');
    }

    private function saveCategory($category, $form, $request): bool
    {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $category->setName($request->request->all('category')['name']);
            $repository = $this->doctrine->getRepository(Category::class);
            $parent = $repository->find($request->request->all('category')['parent']);
            $category->setParent($parent);

            $entityManager = $this->doctrine->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            return true;

        }

        return false;

    }


    public function getAllCategories(CategoryTreeAdminOptionList $categories, $editedCategory = null): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $categories->getCategoryList($categories->buildTree());
        return $this->render('admin/_all_categories.html.twig', [
            'categories' => $categories,
            'editedCategory' => $editedCategory
        ]);

    }

}