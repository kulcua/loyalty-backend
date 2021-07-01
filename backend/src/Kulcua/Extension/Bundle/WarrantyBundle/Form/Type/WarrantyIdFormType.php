<?php

namespace Kulcua\Extension\Bundle\WarrantyBundle\Form\Type;

use Kulcua\Extension\Component\Warranty\Domain\ReadModel\WarrantyDetails;
use Kulcua\Extension\Component\Warranty\Domain\ReadModel\WarrantyDetailsRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class WarrantyIdFormType.
 */
class WarrantyIdFormType extends AbstractType
{
    /**
     * @var WarrantyDetailsRepository
     */
    protected $warrantyDetailsRepository;

    /**
     * WarrantyChoiceFormType constructor.
     *
     * @param WarrantyDetailsRepository $warrantyDetailsRepository
     */
    public function __construct(WarrantyDetailsRepository $warrantyDetailsRepository)
    {
        $this->warrantyDetailsRepository = $warrantyDetailsRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $validateCustomerIsNull = $options['validate_customer_is_null'];
        $repo = $this->warrantyDetailsRepository;
        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) use ($repo, $validateCustomerIsNull) {
            $data = $event->getData();
            $warranty = $repo->find($data);
            if (!$warranty instanceof WarrantyDetails) {
                $event->getForm()->addError(new FormError('Warranty does not exist'));
            }

            // if ($validateCustomerIsNull && $warranty->getCustomerId()) {
            //     $event->getForm()->addError(new FormError('Customer is already assign to this warranty'));
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
