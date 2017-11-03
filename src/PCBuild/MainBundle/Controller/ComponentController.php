<?php

namespace PCBuild\MainBundle\Controller;

use PCBuild\MainBundle\Entity\Component;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Component controller.
 *
 * @Route("component")
 */
class ComponentController extends Controller
{
    /**
     * Lists all component entities.
     *
     * @Route("/", name="component_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $components = $em->getRepository('PCBuildMainBundle:Component')->findAll();

        return $this->render('PCBuildMainBundle:Component:index.html.twig', array(
            'components' => $components,
        ));
    }

    /**
     * Creates a new component entity.
     *
     * @Route("/new", name="component_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $component = new Component();
        $form = $this->createForm('PCBuild\MainBundle\Form\ComponentType', $component);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($component);
            $em->flush();

            return $this->redirectToRoute('component_show', array('id' => $component->getId()));
        }

        return $this->render('PCBuildMainBundle:Component:new.html.twig', array(
            'component' => $component,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a component entity.
     *
     * @Route("/{id}", name="component_show")
     * @Method("GET")
     */
    public function showAction(Component $component)
    {
        $deleteForm = $this->createDeleteForm($component);

        return $this->render('PCBuildMainBundle:Component:show.html.twig', array(
            'component' => $component,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing component entity.
     *
     * @Route("/{id}/edit", name="component_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Component $component)
    {
        $deleteForm = $this->createDeleteForm($component);
        $editForm = $this->createForm('PCBuild\MainBundle\Form\ComponentType', $component);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('component_edit', array('id' => $component->getId()));
        }

        return $this->render('PCBuildMainBundle:Component:edit.html.twig', array(
            'component' => $component,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a component entity.
     *
     * @Route("/{id}", name="component_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Component $component)
    {
        $form = $this->createDeleteForm($component);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($component);
            $em->flush();
        }

        return $this->redirectToRoute('component_index');
    }

    /**
     * Creates a form to delete a component entity.
     *
     * @param Component $component The component entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Component $component)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('component_delete', array('id' => $component->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
