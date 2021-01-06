<?php

namespace Kulcua\Extension\Bundle\MaintenanceBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class LabelFormType.
 */
class LabelFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('key', TextType::class, [
            'required' => true,
            'constraints' => $options['allow_empty'] ? [] : [new NotBlank()],
        ]);
        $builder->add('value', TextType::class, [
            'required' => true,
            'constraints' => $options['allow_empty'] ? [] : [new NotBlank()],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['allow_empty' => false]);
    }
}
