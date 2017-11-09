<?php

namespace PCBuild\MainBundle\Controller;

use PCBuild\MainBundle\Entity\Component;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;use Symfony\Component\HttpFoundation\Request;

/**
 * Component controller.
 *
 * @Route("component")
 */
class ComponentController extends Controller
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
        $filter       = null;
        $nocomponents = false;

        $em           = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('PCBuildMainBundle:Component')->createQueryBuilder('components');

        if ($request->query->getAlnum('filter')) {
            $filter                = $request->query->getAlnum('filter');
            $this->showClearFilter = true;

            $queryBuilder->where('components.title LIKE :title')
                ->setParameter('title', '%' . $filter . '%');
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
            $nocomponents = true;
        }

        return $this->render('PCBuildMainBundle:Component:index.html.twig', array(
            'components'              => $result,
            'showClearFilter'         => $this->showClearFilter,
            'filter'                  => $filter,
            'nocomponents'            => $nocomponents,
            'login_registration_form' => $this->CreateLoginForm(),
        ));
    }

    public function newAction(Request $request)
    {
        $component = new Component();
        $form      = $this->createForm('PCBuild\MainBundle\Form\ComponentType', $component);
        $form->handleRequest($request);

        $username = $this->GetUserName();

        if ($username == "anon.") {
            return $this->redirectToRoute('component_index');
        } else {
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($component);

                $component->setCreated_at(new \DateTime('now'));
                $component->setUpdated_at(new \DateTime('now'));
                $component->setCreated_by($username);
                $component->setUpdated_by($username);
                $component->setBuilds(array());

                dump($component);

                $em->flush();

                return $this->redirectToRoute('component_show', array('id' => $component->getId()));
            }
        }

        return $this->render('PCBuildMainBundle:Component:new.html.twig', array(
            'component'               => $component,
            'form'                    => $form->createView(),
            'login_registration_form' => $this->CreateLoginForm(),
        ));
    }

    public function showAction(Component $component)
    {
        $deleteForm = $this->createDeleteForm($component);

        return $this->render('PCBuildMainBundle:Component:show.html.twig', array(
            'component'               => $component,
            'delete_form'             => $deleteForm->createView(),
            'login_registration_form' => $this->CreateLoginForm(),
        ));
    }

    public function editAction(Request $request, Component $component)
    {
        $deleteForm = $this->createDeleteForm($component);
        $editForm   = $this->createForm('PCBuild\MainBundle\Form\ComponentType', $component);
        $editForm->handleRequest($request);

        $username = $this->GetUserName();

        if ($username == "anon.") {
            return $this->redirectToRoute('component_index');
        } else {
            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $em = $this->getDoctrine()->getManager();

                foreach ($component->getBuilds()->getIterator() as $i => $build) {
                    $em->persist($build);

                    $totalprice = 0.00;

                    foreach ($build->getComponents()->getIterator() as $i => $component) {
                        $totalprice += $component->getPrice();
                    }

                    $build->setTotalprice($totalprice);
                    $em->flush();
                }

                $component->setUpdated_at(new \DateTime('now'));
                $component->setUpdated_by($username);

                $em->flush();

                return $this->redirectToRoute('component_show', array('id' => $component->getId()));
            }

            return $this->render('PCBuildMainBundle:Component:edit.html.twig', array(
                'component'               => $component,
                'edit_form'               => $editForm->createView(),
                'delete_form'             => $deleteForm->createView(),
                'login_registration_form' => $this->CreateLoginForm(),
            ));
        }
    }

    public function deleteAction(Request $request, Component $component)
    {
        $form = $this->createDeleteForm($component);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            foreach ($component->getBuilds()->getIterator() as $i => $build) {
                $em->persist($build);

                $totalprice = $build->getTotalprice();
                $totalprice -= $component->getPrice();
                $build->setTotalprice($totalprice);

                $em->flush();
            }

            $em->remove($component);
            $em->flush();
        }

        return $this->redirectToRoute('component_index');
    }

    public function GetUserName()
    {
        $user     = $this->get('security.token_storage')->getToken()->getUser();
        $username = $user;

        if ($username != "anon.") {
            $username = $user->getUsername(0);
        }

        return $username;
    }

    /**
     * Creates a form to delete a component entity.
     *
     * @param Component $component The component entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    public function createDeleteForm(Component $component)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('component_delete', array('id' => $component->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
