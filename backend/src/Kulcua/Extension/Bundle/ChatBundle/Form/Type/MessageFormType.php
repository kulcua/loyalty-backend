<?php

namespace Kulcua\Extension\Bundle\ChatBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type as Numeric;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

/**
 * Class MessageDetailsFormType.
 */
class MessageFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('conversationId', TextType::class, [
            'required' => true
        ]);
        $builder->add('conversationParticipantIds', CollectionType::class, [
            'entry_type' => TextType::class,
            'required' => true,
            'allow_add' => true,
            'error_bubbling' => false
        ]);
        $builder->add('senderId', TextType::class, [
            'required' => true
        ]);
        $builder->add('senderName', TextType::class, [
            'required' => true
        ]);
        $builder->add('message', TextType::class, [
            'required' => true
        ]);
        $builder->add('messageTimestamp', DateTimeType::class, [
            'required' => true,
            'widget' => 'single_text',
            'format' => DateTimeType::HTML5_FORMAT,
            'constraints' => [
                new NotBlank(),
            ],
        ]);
    }
}
