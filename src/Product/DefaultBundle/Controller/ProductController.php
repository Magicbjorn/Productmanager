<?php

namespace Product\DefaultBundle\Controller;

use Product\DefaultBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends Controller
{
    private $showClearFilter = false;

    public function CreateLoginForm()
    {
        $formFactory = $this->container->get('fos_user.registration.form.factory');
        $form        = $formFactory->createForm();
        return $form->createView();
    }

    public function indexAction(Request $request)
    {
        $filter     = null;
        $noproducts = false;

        $em           = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('ProductDefaultBundle:Product')->createQueryBuilder('prods');

        if ($request->query->getAlnum('filter')) {
            $filter                = $request->query->getAlnum('filter');
            $this->showClearFilter = true;

            $queryBuilder->where('prods.name LIKE :name')
                ->setParameter('name', '%' . $filter . '%');
        } else {
            $this->showClearFilter = false;
        }

        $query = $queryBuilder->getQuery();

        $paginator = $this->get('knp_paginator');
        $result    = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 5)
        );

        if (count($result) <= 0) {
            $noproducts = true;
        }

        return $this->render('ProductDefaultBundle:Product:index.html.twig', array(
            'products'                => $result,
            'showClearFilter'         => $this->showClearFilter,
            'filter'                  => $filter,
            'noproducts'              => $noproducts,
            'login_registration_form' => $this->CreateLoginForm(),
        ));
    }

    public function newAction(Request $request)
    {
        $product = new Product();
        $form    = $this->createForm('Product\DefaultBundle\Form\ProductType', $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('product_show', array('id' => $product->getId()));
        }

        return $this->render('ProductDefaultBundle:Product:new.html.twig', array(
            'product'                 => $product,
            'form'                    => $form->createView(),
            'login_registration_form' => $this->CreateLoginForm(),
        ));
    }

    public function showAction(Product $product)
    {
        $deleteForm = $this->createDeleteForm($product);

        return $this->render('ProductDefaultBundle:Product:show.html.twig', array(
            'product'                 => $product,
            'delete_form'             => $deleteForm->createView(),
            'login_registration_form' => $this->CreateLoginForm(),
        ));
    }

    public function editAction(Request $request, Product $product)
    {
        $deleteForm = $this->createDeleteForm($product);
        $editForm   = $this->createForm('Product\DefaultBundle\Form\ProductType', $product);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('product_show', array('id' => $product->getId()));
        }

        return $this->render('ProductDefaultBundle:Product:edit.html.twig', array(
            'product'                 => $product,
            'edit_form'               => $editForm->createView(),
            'delete_form'             => $deleteForm->createView(),
            'login_registration_form' => $this->CreateLoginForm(),
        ));
    }

    public function deleteAction(Request $request, Product $product)
    {
        $form = $this->createDeleteForm($product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($product);
            $em->flush();
        }

        return $this->redirectToRoute('product_index');
    }

    /**
     * Creates a form to delete a product entity.
     *
     * @param Product $product The product entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Product $product)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('product_delete', array('id' => $product->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
