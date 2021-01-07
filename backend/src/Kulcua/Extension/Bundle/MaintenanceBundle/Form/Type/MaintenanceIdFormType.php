<?php

namespace Kulcua\Extension\Bundle\MaintenanceBundle\Form\Type;

use Kulcua\Extension\Component\Maintenance\Domain\ReadModel\MaintenanceDetails;
use Kulcua\Extension\Component\Maintenance\Domain\ReadModel\MaintenanceDetailsRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class MaintenanceIdFormType.
 */
class MaintenanceIdFormType extends AbstractType
{
    /**
     * @var MaintenanceDetailsRepository
     */
    protected $maintenanceDetailsRepository;

    /**
     * MaintenanceChoiceFormType constructor.
     *
     * @param MaintenanceDetailsRepository $maintenanceDetailsRepository
     */
    public function __construct(MaintenanceDetailsRepository $maintenanceDetailsRepository)
    {
        $this->maintenanceDetailsRepository = $maintenanceDetailsRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $validateCustomerIsNull = $options['validate_customer_is_null'];
        $repo = $this->maintenanceDetailsRepository;
        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) use ($repo, $validateCustomerIsNull) {
            $data = $event->getData();
            $maintenance = $repo->find($data);
            if (!$maintenance instanceof MaintenanceDetails) {
                $event->getForm()->addError(new FormError('Maintenance does not exist'));
            }

            // if ($validateCustomerIsNull && $maintenance->getCustomerId()) {
            //     $event->getForm()->addError(new FormError('Customer is already assign to this maintenance'));
            // }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'validate_customer_is_null' => null,
        ]);
    }

    public function getParent()
    {
        return TextType::class;
    }
}
