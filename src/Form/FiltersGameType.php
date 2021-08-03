<?php

namespace App\Form;

use App\Entity\Device;
use App\Entity\Game;
use App\Entity\GameCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FiltersGameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'required'=>false
            ])
            ->add('pricemin', NumberType::class, [
                'required' => false
            ])
            ->add('pricemax', NumberType::class, [
                'required' => false
            ])
            ->add('noteGlobalMin',NumberType::class, [
                'required' => false,
            ])
            ->add('noteGlobalMax',NumberType::class, [
                'required' => false,
            ])
            ->add('gameCategory', EntityType::class, [
                'class' => GameCategory::class,
                'choice_label' => 'name',
                'label' => 'Catégorie',
                'placeholder' => 'Sélectionner une catégorie',
                'required' => false
            ])
            ->add('devices', EntityType::class, [
                'class' => Device::class,
                'choice_label' => 'name',
                'label' => 'Console',
                'placeholder' => 'Sélectionner une console',
                'required' => false
            ])
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'btn']
            ])
        ;
    }

//    public function configureOptions(OptionsResolver $resolver)
//    {
//        $resolver->setDefaults([
//            'data_class' => Game::class,
//        ]);
//    }
}
