<?php
/**
 * @author JKetelaar
 */

namespace AppBundle\Form;

use AppBundle\Entity\OrderAddress;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class OrderAddressType
 * @package AppBundle\Form
 */
class OrderAddressType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type')
            ->add('fullname')
            ->add('address')
            ->add('secondaryAddress')
            ->add('city')
            ->add('state')
            ->add('zipCode')
            ->add('phoneNumber')
            ->add('instructions');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => OrderAddress::class,
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_orderaddress';
    }

}
