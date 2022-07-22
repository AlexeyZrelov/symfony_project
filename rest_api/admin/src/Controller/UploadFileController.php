<?php

namespace App\Controller;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Constraints as Assert;

class UploadFileController extends AbstractController
{
    private ManagerRegistry $doctrine;
    private ValidatorInterface $validator;
    private string $fileSaveDb;

    public function __construct(ManagerRegistry $doctrine, ValidatorInterface $validator)
    {
        $this->doctrine = $doctrine;
        $this->validator = $validator;
    }

    public function __invoke(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $fileName = '';

        $productId = $request->request->get('product_id');
        $em = $this->doctrine->getManager();
        $product = $em->getRepository(Product::class)->find($productId);

        if ($product && $product->getImageFile() == null) {

            $fileName = $this->_upload($request);
            $product->setImageFile($this->fileSaveDb);
            $em->persist($product);
            $em->flush();

        }

        return new Response($fileName);

    }

    private function _upload($request) {

        $emailConstraint = new Assert\File(['maxSize'=>'1000k','mimeTypes'=>["image/jpeg", "image/png"]]);

        $errors = $this->validator->validate(
            $request->files->get('imageFile'),
            (array)$emailConstraint
        );

        if (0 === count($errors)) {
            // ... this IS a valid email address, do something
        } else {
            // this is *not* a valid email address
            $errorMessage = $errors[0]->getMessage();

            return new Response($errorMessage);
        }

        // dd($request->files);
        $uploadedFile = $request->files->get('imageFile');


        if (!$uploadedFile)
        {
            throw new BadRequestHttpException('"file" is required');
        }

        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
        $fileName = $safeFilename . '-' . uniqid() . '.' . $uploadedFile->guessExtension();

        try {
            $uploadedFile->move('uploads/images/products', $this->fileSaveDb = $originalFilename.'.'.$uploadedFile->guessExtension());
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
            return;
        }

        return $fileName;
    }


}
