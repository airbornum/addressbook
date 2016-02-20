<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class ContactType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $options['user'];

        $builder
            ->add('contact', 'entity', [
                'class' => 'AppBundle:User',
                'query_builder' => function(EntityRepository $er) use ($user) {
                    $qb = $er->createQueryBuilder('u');
                    $qb
                        ->where('u.id <> ?1');
                    foreach ($user->getContacts() as $contact) {
                        $qb->andWhere('u.id <> '.$contact->getId());
                    }
                    $qb->setParameter('1', $user->getId());

                    return $qb;
                },
                'mapped' => false
            ])
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
        $resolver->setRequired(array('user'));
    }

//    public function getParent()
//    {
//         return 'fos_user_profile';
//    }

    public function getBlockPrefix()
    {
        return 'app_user_contact';
    }
    // For Symfony 2.x
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
