<?php

namespace PCBuild\MainBundle\Controller;

use PCBuild\MainBundle\Entity\Build;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class BuildController extends Controller
{
    private $showClearFilter = false;

    private $usr      = null;
    private $username = null;

    public function CreateLoginForm()
    {
        $formFactory = $this->container->get('fos_user.registration.form.factory');
        $form        = $formFactory->createForm();
        return $form->createView();
    }

    public function indexAction(Request $request)
    {
        $usr      = $this->get('security.token_storage')->getToken()->getUser();
        $username = "";

        if ($usr != "anon.") {
            $username = $usr->getUsername(0);
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

            $queryBuilder->where('builds.title LIKE :title')
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
            $nobuilds = true;
        }

        return $this->render('PCBuildMainBundle:Build:index.html.twig', array(
            'builds'                  => $result,
            'showClearFilter'         => $this->showClearFilter,
            'filter'                  => $filter,
            'nobuilds'                => $nobuilds,
            'login_registration_form' => $this->CreateLoginForm(),
            'totalprice'              => 29.99,
        ));
    }

    public function newAction(Request $request)
    {
        $nocomponents = false;
        $em           = $this->getDoctrine()->getManager();
        $components   = $em->getRepository('PCBuildMainBundle:Component')->findAll();

        if ($components == []) {
            $nocomponents = true;
        } else {
            $build = new Build();
            $form  = $this->createForm('PCBuild\MainBundle\Form\BuildType', $build);
            $form->handleRequest($request);

            $usr = $this->getUser();

            if ($usr == null) {
                return $this->redirectToRoute('build_index');
            } else {
                if ($form->isSubmitted() && $form->isValid()) {
                    $em->persist($build);

                    $totalprice = 0.00;

                    foreach ($_POST['componentsselected'] as $componentid) {
                        $component = $em->find('PCBuildMainBundle:Component', $componentid);

                        $totalprice += $component->getPrice();

                        $build->addComponent($component);
                    }

                    $build->setTotalprice($totalprice);
                    $build->setCreated_at(new \DateTime('now'));
                    $build->setUpdated_at(new \DateTime('now'));
                    $build->setCreated_by($usr->getUsername());
                    $build->setUpdated_by($usr->getUsername());

                    $em->flush();

                    return $this->redirectToRoute('build_show', array('id' => $build->getId()));
                }
            }

            return $this->render('PCBuildMainBundle:Build:new.html.twig', array(
                'build'                   => $build,
                'form'                    => $form->createView(),
                'login_registration_form' => $this->CreateLoginForm(),
                'components'              => $components,
                'nocomponents'            => $nocomponents,
            ));
        }

        return $this->render('PCBuildMainBundle:Build:new.html.twig', array(
            'nocomponents' => $nocomponents,
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
        $em = $this->getDoctrine()->getManager();

        $deleteForm = $this->createDeleteForm($build);
        $editForm   = $this->createForm('PCBuild\MainBundle\Form\BuildType', $build);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $totalprice = $build->getTotalprice();

            if (isset($_POST['deleteselected'])) {
                foreach ($_POST['deleteselected'] as $componentid) {
                    $component = $em->find('PCBuildMainBundle:Component', $componentid);

                    $totalprice -= $component->getPrice();

                    $build->removeComponent($component);
                }
            }

            if (isset($_POST['addselected'])) {
                foreach ($_POST['addselected'] as $componentid) {
                    $component = $em->find('PCBuildMainBundle:Component', $componentid);

                    $totalprice += $component->getPrice();

                    $build->addComponent($component);
                }
            }

            $build->setTotalprice($totalprice);

            $build->setUpdated_at(new \DateTime('now'));
            $build->setUpdated_by($this->GetUserName());

            $em->flush();

            return $this->redirectToRoute('build_show', array('id' => $build->getId()));
        }

        $componentsstore = $em->getRepository('PCBuildMainBundle:Component')->findAll();
        $components      = array();

        dump($build);
        foreach ($componentsstore as $component) {
            dump(array($build->getComponents()));
            if (!$build->getComponents()->contains($component)) {
                array_push($components, $component);
            }
        }

        return $this->render('PCBuildMainBundle:Build:edit.html.twig', array(
            'build'                   => $build,
            'edit_form'               => $editForm->createView(),
            'delete_form'             => $deleteForm->createView(),
            'login_registration_form' => $this->CreateLoginForm(),
            'components'              => $components,
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
