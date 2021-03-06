<?php

namespace App\Form;

use CardMaker\Entity\Dto\GenerateCard;
use CardMaker\Entity\Layer;
use CardMaker\Handler\CardGenerate;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CardGenerateType
 *
 * @package App\Form
 */
class CardGenerateType extends AbstractType
{
    const TEXT_SIZES = [
        'cardmaker.textsize.auto' => 0,
        'cardmaker.textsize.love-me' => 17,
        'cardmaker.textsize.i-know-many-words' => 18,
        'cardmaker.textsize.tiny' => 19,
        'cardmaker.textsize.small' => 20,
        'cardmaker.textsize.normal' => 21,
        'cardmaker.textsize.medium' => 22,
        'cardmaker.textsize.big' => 23,
        'cardmaker.textsize.huge' => 24,
        'cardmaker.textsize.extra-huge' => 25,
        'cardmaker.textsize.democracy-big' => 26,
        'cardmaker.textsize.premium-democracy' => 27
    ];

    const FORM_MODE = [
        'cardmaker.form-mode.basic' => 0,
        'cardmaker.form-mode.advanced' => 1,
    ];

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('layer', ChoiceType::class, [
                'choices' => Layer::CARD_LAYERS,
                'label' => 'cardmaker.form.layer',
                'required' => true
            ])
            //            ->add('mode', ChoiceType::class, [
            //                'choices' => self::FORM_MODE,
            //                'label' => 'cardmaker.form.mode',
            //                'required' => false,
            //                'placeholder' => false
            //            ])
            ->add('mode', ChoiceType::class, [
                'choices' => self::FORM_MODE,
                'label' => 'cardmaker.form.mode',
                'required' => false,
                'placeholder' => false
            ])
            ->add('title', TextType::class, [
                'label' => 'cardmaker.form.title',
                'required' => true
            ])
            ->add('tag', TextType::class, [
                'label' => 'cardmaker.form.tag',
                'required' => false
            ])
            ->add('captionType', ChoiceType::class, [
                'choices' => CardGenerate::CAPTION_TYPES,
                'label' => 'cardmaker.form.caption-type',
                'required' => false,
                'placeholder' => false
            ])
            ->add('caption', TextType::class, [
                'label' => 'cardmaker.form.caption',
                'required' => false
            ])
            ->add('level', TextType::class, [
                'label' => 'cardmaker.form.level',
                'required' => false
            ])
            ->add('text', TextareaType::class, [
                'label' => 'cardmaker.form.text',
                'required' => true
            ])
            ->add('layoutSize', ChoiceType::class, [
                'choices' => CardGenerate::CARD_LAYOUT_SIZE,
                'label' => 'cardmaker.form.layout_size',
                'required' => false,
                'placeholder' => false
            ])
            ->add('textSize', ChoiceType::class, [
                'choices' => self::TEXT_SIZES,
                'label' => 'cardmaker.form.text_size',
                'required' => false,
                'placeholder' => false
            ])
            ->add('image', FileType::class, [
                'label' => 'cardmaker.form.image',
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => GenerateCard::class]);
    }
}
