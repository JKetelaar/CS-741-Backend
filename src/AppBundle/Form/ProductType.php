<?php

namespace AppBundle\Form;

use AppBundle\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ProductType
 * @package AppBundle\Form
 */
class ProductType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('price')
            ->add('quantity')
            /* Promotion */
            ->add('promoPrice', TextType::class, ['required' => false])
            ->add(
                'promoFrom',
                DateType::class,
                [
                    'required' => false,
                    'format' => 'yyyy-MM-dd  HH:mm:ss',
                    'widget' => 'single_text',
                ]
            )
            ->add(
                'promoTo',
                DateType::class,
                [
                    'required' => false,
                    'format' => 'yyyy-MM-dd  HH:mm:ss',
                    'widget' => 'single_text',
                ]
            );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Product::class,
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_product';
    }
}
