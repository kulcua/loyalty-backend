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
 * Class ConversationFormType.
 */
class ConversationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('participantIds', CollectionType::class, [
            'entry_type' => TextType::class,
            'required' => true,
            'allow_add' => true,
            'error_bubbling' => false
        ]);
        $builder->add('participantNames', CollectionType::class, [
            'entry_type' => TextType::class,
            'required' => true,
            'allow_add' => true,
            'error_bubbling' => false
        ]);
        $builder->add('lastMessageSnippet', TextType::class, [
            'required' => true
        ]);
        $builder->add('lastMessageTimestamp', DateTimeType::class, [
            'required' => true,
            'widget' => 'single_text',
            'format' => DateTimeType::HTML5_FORMAT,
            'constraints' => [
                new NotBlank(),
            ],
        ]);
    }
}
