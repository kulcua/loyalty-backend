<?php

namespace Kulcua\Extension\Bundle\MaintenanceBundle\Form\Type;

use Kulcua\Extension\Component\Maintenance\Domain\Maintenance;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Valid;
/**
 * Class MaintenanceFormType.
 */
class MaintenanceFormType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // $builder->add($this->buildMaintenanceDataForm($builder));
        
        $builder->add('maintenanceData', MaintenanceDetailsFormType::class, [
            'required' => true,
            'constraints' => [new NotBlank(), new Valid()],
        ]);

        $builder->add('customerData', CustomerDetailsFormType::class, [
            'required' => true,
            'constraints' => [new NotBlank(), new Valid()],
        ]);
    }
}
