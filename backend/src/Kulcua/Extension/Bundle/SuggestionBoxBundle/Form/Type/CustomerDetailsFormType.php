<?php

namespace Kulcua\Extension\Bundle\SuggestionBoxBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type as Numeric;

/**
 * Class CustomerDetailsFormType.
 */
class CustomerDetailsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, [
            'required' => true,
            'constraints' => [new NotBlank()],
        ]);
        $builder->add('email', EmailType::class, [
            'required' => true,
            'constraints' => [new NotBlank()],
        ]);
        $builder->add('phone', TextType::class, [
            'required' => false,
            'constraints' => [
                new Numeric(['type' => 'numeric', 'message' => 'Incorrect phone number format, use +00000000000']),
            ],
        ]);
        $builder->add('loyaltyCardNumber', TextType::class, [
            'required' => false,
        ]);
    }
}
