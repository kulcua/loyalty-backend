<?php

namespace Kulcua\Extension\Bundle\ChatBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Kulcua\Extension\Bundle\ChatBundle\Validator\Constraints;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

/**
 * Class MessagePhotoFormType.
 */
class MessagePhotoFormType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
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
        $builder->add(
            'photoMessage',
            FileType::class,
            [
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Constraints\Image(
                        [
                            'mimeTypes' => ['image/png', 'image/gif', 'image/jpeg', 'image/jpg', 'video/mp4'],
                            'maxSize' => '20M',
                        ]
                    ),
                ],
            ]
        );
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
