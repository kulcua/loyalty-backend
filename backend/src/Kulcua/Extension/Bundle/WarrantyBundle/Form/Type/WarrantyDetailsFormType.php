<?php

namespace Kulcua\Extension\Bundle\WarrantyBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

/**
 * Class WarrantyDetailsFormType.
 */
class WarrantyDetailsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('productSku', TextType::class, [
            'required' => true
        ]);
        $builder->add('warrantyCenter', TextType::class);
        $builder->add('bookingDate', DateTimeType::class, [
            'required' => true,
            'widget' => 'single_text',
            'format' => DateTimeType::HTML5_FORMAT,
            'constraints' => [
                new NotBlank(),
            ],
        ]);
        $builder->add('bookingTime', TextType::class);
        $builder->add('createdAt', DateTimeType::class, [
            'required' => true,
            'widget' => 'single_text',
            'format' => DateTimeType::HTML5_FORMAT,
            'constraints' => [
                new NotBlank(),
            ],
        ]);
        $builder->add(
            'active',
            CheckboxType::class,
            [
                'required' => false,
            ]
        );
        $builder->add('description', TextType::class, [
            'required' => true
        ]);
        $builder->add('cost', TextType::class, [
            'required' => true
        ]);
        $builder->add('paymentStatus', TextType::class, [
            'required' => true
        ]);
    }
}
