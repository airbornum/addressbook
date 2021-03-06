<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName')
            ->add('lastName')
            ->add('email')
            ->add('phoneNumber')
            ->add('address')
            ->add('website')
        ;
//            ->add('current_password', 'password', array(
//            'label' => 'form.current_password',
//            'translation_domain' => 'FOSUserBundle',
//            'mapped' => false,
//            'constraints' => new UserPassword(),
//        ));
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
        return 'app_user_profile';
    }
    // For Symfony 2.x
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
