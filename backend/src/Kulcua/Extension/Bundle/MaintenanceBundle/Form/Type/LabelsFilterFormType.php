<?php

namespace Kulcua\Extension\Bundle\MaintenanceBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class LabelsFilterFormType.
 */
class LabelsFilterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('labels', CollectionType::class, [
            'allow_add' => true,
            'allow_delete' => true,
            'entry_type' => LabelFormType::class,
            'entry_options' => [
                'allow_empty' => true,
            ],
        ]);
    }
}
