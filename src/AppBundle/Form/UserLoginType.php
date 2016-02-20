<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class UserLoginType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('current_password', 'password', array(
            'label' => 'form.current_password',
            'translation_domain' => 'FOSUserBundle',
            'mapped' => false,
            'constraints' => new UserPassword(),
        ))
            ->add('plainPassword', 'repeated', array(
        'type' => 'password',
        'first_options' => array('label' => 'form.new_password'),
        'second_options' => array('label' => 'form.new_password_confirmation'),
        'invalid_message' => 'fos_user.password.mismatch',
    ));
        ;
    }
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User'
        ));
    }

//    public function getParent()
//    {
//         return 'fos_user_profile';
//    }

    public function getBlockPrefix()
    {
        return 'app_user_login';
    }
    // For Symfony 2.x
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
