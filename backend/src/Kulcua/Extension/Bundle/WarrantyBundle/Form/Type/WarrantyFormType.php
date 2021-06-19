<?php

namespace Kulcua\Extension\Bundle\WarrantyBundle\Form\Type;

use Kulcua\Extension\Component\Warranty\Domain\Warranty;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Valid;
/**
 * Class WarrantyFormType.
 */
class WarrantyFormType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {     
        $builder->add('warrantyData', WarrantyDetailsFormType::class, [
            'required' => true,
            'constraints' => [new NotBlank(), new Valid()],
        ]);

        $builder->add('customerData', CustomerDetailsFormType::class, [
            'required' => true,
            'constraints' => [new NotBlank(), new Valid()],
        ]);
    }
}
