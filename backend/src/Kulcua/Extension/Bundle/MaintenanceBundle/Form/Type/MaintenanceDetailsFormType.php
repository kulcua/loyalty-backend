<?php

namespace Kulcua\Extension\Bundle\MaintenanceBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type as Numeric;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

/**
 * Class MaintenanceDetailsFormType.
 */
class MaintenanceDetailsFormType extends AbstractType
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
    }
}
