<?php

namespace PCBuild\MainBundle\Controller;

use PCBuild\MainBundle\Entity\Build;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class BuildController extends Controller
{
    private $showClearFilter = false;

    private $username = null;

    public function CreateLoginForm()
    {
        $formFactory = $this->container->get('fos_user.registration.form.factory');
        $form        = $formFactory->createForm();
        return $form->createView();
    }

    public function indexAction(Request $request)
    {
        $username = $this->get('security.token_storage')->getToken()->getUser();
        
        if ($username != "anon.") {
            $username = $username->getUsername(0);
        }

        $filter   = null;
        $nobuilds = false;

        $em           = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('PCBuildMainBundle:Build')->createQueryBuilder('builds');

        $queryBuilder->where('builds.created_by = :created_by')
            ->setParameter('created_by', $username);

        if ($request->query->getAlnum('filter')) {
            $filter                = $request->query->getAlnum('filter');
            $this->showClearFilter = true;

            $queryBuilder->where('builds.name LIKE :name')
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
            $nobuilds = true;
        }

        return $this->render('PCBuildMainBundle:Build:index.html.twig', array(
            'builds'                  => $result,
            'showClearFilter'         => $this->showClearFilter,
            'filter'                  => $filter,
            'nobuilds'                => $nobuilds,
            'login_registration_form' => $this->CreateLoginForm(),
        ));
    }

    public function newAction(Request $request)
    {
        $build = new Build();
        $form  = $this->createForm('PCBuild\MainBundle\Form\BuildType', $build);
        $form->handleRequest($request);

        $build->setCreated_at(time());

        $usr = $this->getUser();
        $build->setCreated_by($usr->getUsername());

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($build);

            $build->setCreated_at = explode(" ", microtime())[1];
            $build->setCreated_by = $this->username;

            $em->flush();

            return $this->redirectToRoute('build_show', array('id' => $build->getId()));
        }

        return $this->render('PCBuildMainBundle:Build:new.html.twig', array(
            'build'                   => $build,
            'form'                    => $form->createView(),
            'login_registration_form' => $this->CreateLoginForm(),
        ));
    }

    public function showAction(Build $build)
    {
        $deleteForm = $this->createDeleteForm($build);

        return $this->render('PCBuildMainBundle:Build:show.html.twig', array(
            'build'                   => $build,
            'delete_form'             => $deleteForm->createView(),
            'login_registration_form' => $this->CreateLoginForm(),
        ));
    }

    public function editAction(Request $request, Build $build)
    {
        $deleteForm = $this->createDeleteForm($build);
        $editForm   = $this->createForm('PCBuild\MainBundle\Form\BuildType', $build);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('build_show', array('id' => $build->getId()));
        }

        return $this->render('PCBuildMainBundle:Build:edit.html.twig', array(
            'build'                   => $build,
            'edit_form'               => $editForm->createView(),
            'delete_form'             => $deleteForm->createView(),
            'login_registration_form' => $this->CreateLoginForm(),
        ));
    }

    public function deleteAction(Request $request, Build $build)
    {
        $form = $this->createDeleteForm($build);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($build);
            $em->flush();
        }

        return $this->redirectToRoute('build_index');
    }

    /**
     * Creates a form to delete a product entity.
     *
     * @param Product $product The product entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Build $build)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('build_delete', array('id' => $build->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
