<?php

namespace AppBundle\Controller;

use AppBundle\Form\ContactType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use AppBundle\Form\UserLoginType;

/**
 * User controller.
 *
 * @Route("/user")
 */
class UserController extends Controller
{

    /**
     * Lists all contacts for current user.
     *
     * @Route("/", name="contacts")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $client = $this->getUser();
        $contacts = $client->getContacts();

        return array(
            'contacts' => $contacts,
            'user'        => $this->getUser()
        );
    }
    /**
     * Creates a new User entity.
     *
     * @Route("/", name="user_create")
     * @Method("POST")
     * @Template("AppBundle:User:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new User();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('user_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'user'        => $this->getUser()
        );
    }

    /**
     * Creates a form to create a User entity.
     *
     * @param User $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(User $entity)
    {
        $form = $this->createForm(new UserType(), $entity, array(
            'action' => $this->generateUrl('user_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new User entity.
     *
     * @Route("/new", name="user_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new User();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'user'        => $this->getUser()
        );
    }


    /**
     * Creates a new User entity.
     *
     * @Route("/contact", name="contact_create")
     * @Method("POST")
     * @Template("AppBundle:User:newContact.html.twig")
     */
    public function createContactAction(Request $request)
    {
        $form = $this->createContactCreateForm();
        $form->handleRequest($request);

        if ($form->isValid()) {
            $contact = $form->get('contact')->getData();
            $this->getUser()->addContact($contact);

            $em = $this->getDoctrine()->getManager();
            $em->persist($this->getUser());
            $em->flush();

            return $this->redirect($this->generateUrl('contacts'));
        }

        return array(
            'form'   => $form->createView(),
            'user'        => $this->getUser()
        );
    }

    /**
     * Creates a form to create a User entity.
     *
     * @param User $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createContactCreateForm()
    {
        $form = $this->createForm(new ContactType(), null, array(
            'action' => $this->generateUrl('contact_create'),
            'method' => 'POST',
            'user' => $this->getUser()
        ));

        $form->add('submit', 'submit', array('label' => 'Add contact'));

        return $form;
    }

    /**
     * Displays a form to associate a new contact.
     *
     * @Route("/contact/new", name="contact_new")
     * @Method("GET")
     * @Template()
     */
    public function newContactAction()
    {
        $form   = $this->createContactCreateForm();

        return array(
            'form'   => $form->createView(),
            'user'   => $this->getUser()
        );
    }

    /**
     * Finds and displays a User entity.
     *
     * @Route("/{id}", name="user_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $contact = $em->getRepository('AppBundle:User')->find($id);

        if (!$contact) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $deleteForm = $this->createDeleteContactForm($id);

        return array(
            'contact'      => $contact,
            'delete_form' => $deleteForm->createView(),
            'user'        => $this->getUser()
        );
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     * @Route("/{id}/edit", name="user_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $editForm = $this->createEditForm($entity);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'user'        => $this->getUser()
        );
    }

    /**
    * Creates a form to edit a User entity.
    *
    * @param User $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(User $entity)
    {
        $form = $this->createForm(new UserType(), $entity, array(
            'action' => $this->generateUrl('user_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing User entity.
     *
     * @Route("/{id}", name="user_update")
     * @Method("PUT")
     * @Template("AppBundle:User:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('contacts', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'user'        => $this->getUser()
        );
    }

    /**
     * Displays a form to edit credentials of an existing User entity.
     *
     * @Route("/{id}/login/edit", name="user_edit_login")
     * @Method("GET")
     * @Template()
     */
    public function editLoginAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $editForm = $this->createEditLoginForm($entity);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'user'        => $this->getUser()
        );
    }

    /**
    * Creates a form to edit credentials of a User entity.
    *
    * @param User $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditLoginForm(User $entity)
    {
        $form = $this->createForm(new UserLoginType(), $entity, array(
            'action' => $this->generateUrl('user_update_login', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits credentials of an existing User entity.
     *
     * @Route("/{id}/login", name="user_update_login")
     * @Method("PUT")
     * @Template("AppBundle:User:editLogin.html.twig")
     */
    public function updateLoginAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $editForm = $this->createEditLoginForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('contacts', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'user'        => $this->getUser()
        );
    }

    /**
     * Deletes a User entity.
     *
     * @Route("/{id}", name="user_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:User')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find User entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('login'));
    }

    /**
     * Creates a form to delete a User entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('user_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
            ;
    }

    /**
     * Deletes a contact.
     *
     * @Route("/{id}/contact", name="contact_delete")
     * @Method("GET")
     */
    public function deleteContactAction(Request $request, $id)
    {
        $form = $this->createDeleteContactForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $contact = $em->getRepository('AppBundle:User')->find($id);

            $logger = $this->get('my.logger');
            $logger->info("Deleting $id...");
            if (!$contact) {
                throw $this->createNotFoundException('Unable to find User entity.');
            }

            $logger->info("found");
            $this->getUser()->removeContact($contact);
            $logger->info("removed");

            $em->persist($this->getUser());
            $em->flush();
            $logger->info("persisted");
        }

        return $this->redirect($this->generateUrl('contacts'));
    }

    /**
     * Creates a form to delete a User entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteContactForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('contact_delete', array('id' => $id)))
            ->setMethod('GET')
            ->add('submit', 'submit', array('label' => 'Delete contact'))
            ->getForm()
            ;
    }
}
