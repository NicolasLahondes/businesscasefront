<?php

namespace App\Controller;

use Symfony\Component\Validator\Validator\ValidatorInterface;

use App\Entity\Products;
use App\Form\ProductsType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ProductMagDisplayController extends AbstractController


{
    /**
     * @Route("/product/mag/display", name="product_mag_display")
     */
    public function index(): Response
    {
        return $this->render('product_mag_display/index.html.twig', [
            'controller_name' => 'ProductMagDisplayController',
        ]);
    }

    /////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////
    // DISPLAY ALL __PRODUCTS
    ////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////


    /**
     * @Route("/product/mag/displayAll", name="product_mag_display")
     */
    public function displayAll(): Response
    {
        $products = $this->getDoctrine()
            ->getRepository(Products::class)->findAll();


        return $this->render(
            'product_mag_display/index.html.twig',
            [
                'products' => $products,
            ]
        );
    }

    /////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////
    // ADD NEW __PRODUCTS
    ////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////

    /**
     * @Route("/product/new", name="product_new", methods={"GET","POST"})
     */
    public function new(Request $request, SluggerInterface $slugger, ValidatorInterface $validator): Response
    {
        $product = new Products();
        $form = $this->createForm(ProductsType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $errors = $validator->validate($product);
            if (count($errors) > 0) {
                return $this->render('product_mag_display/new.html.twig', [
                    'errors' => $errors,
                ]);
            }

            // UPLOAD IMAGE

            /** @var UploadedFile $imagesFile */
            $imagesFile = $form->get('images')->getData();
            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($imagesFile) {
                $originalFilename = pathinfo($imagesFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imagesFile->guessExtension();
                // Move the file to the directory where brochures are stored
                try {
                    $imagesFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $product->setImages($newFilename);
            }

            // UPLOAD IMAGE FIN

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();
            return $this->redirectToRoute('product_mag_display', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product_mag_display/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    /////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////
    // DISPLAY ONE __PRODUCTS
    ////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////

    /**
     * @Route("/product/{id}/mag", name="product_mag_display_id")
     */
    public function display(int $id): Response
    {
        $products = $this->getDoctrine()
            ->getRepository(Products::class)
            ->find($id);
        // if (!$product) {
        //     return $this->render('author/validation.html.twig', [
        //         'errors' => 'Aucun produit ne possède cet identifiant'
        //     ]);
        // }

        return $this->render(
            'product_mag_display/indexId.html.twig',
            [
                'products' => $products,
            ]
        );
    }

    /////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////
    // EDIT ONE __PRODUCTS
    ////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////

    /**
     * @Route("product/{id}/edit", name="products_edit", methods={"GET","POST"})
     */
    public function edit(int $id, Request $request, Products $products, SluggerInterface $slugger, ValidatorInterface $validator): Response
    {
        $form = $this->createForm(ProductsType::class, $products);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $errors = $validator->validate($products);
            if (count($errors) > 0) {
                return $this->render('product_mag_display/new.html.twig', [
                    'errors' => $errors,
                ]);
            }

            // UPLOAD IMAGE

            /** @var UploadedFile $imagesFile */
            $imagesFile = $form->get('images')->getData();
            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($imagesFile) {
                $originalFilename = pathinfo($imagesFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imagesFile->guessExtension();
                // Move the file to the directory where brochures are stored
                try {
                    $imagesFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $products->setImages($newFilename);
            }

            // UPLOAD IMAGE FIN

            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('product_mag_display_id', ['id' => $id], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('product_mag_display/productEdit.html.twig', [
            'products' => $products,
            'form' => $form,
            'id' => $id
        ]);
    }

    /////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////
    // DELETE ONE __PRODUCTS
    ////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////

    /**
     * @Route("/product/{id}/delete", name="product_delete")
     */
    public function delete(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $product = $entityManager->getRepository(Products::class)->find($id);

        if (!$product) {
            return $this->render('author/errorDisplay.html.twig', ['error' => 'Le produit n\'existe pas']);
        }

        $entityManager->remove($product);
        $entityManager->flush();

        return $this->redirectToRoute('product_mag_display');
    }
}
