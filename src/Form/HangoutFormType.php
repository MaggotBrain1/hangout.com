<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\City;
use App\Entity\Hangout;
use App\Entity\Place;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\RangeValidator;

class HangoutFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,[
                'label' => 'Nom de la sortie',
                'required' => true,
                'row_attr' => [
                    'class' => 'form-floating mb-1',
                ],
            ])
            ->add('startTime',DateTimeType::class,[
                'html5'=>true,
                'widget' => 'single_text',
                'label' => 'Date et heure de la sortie',
                'required' => true,
                'constraints' => [
                    new GreaterThanOrEqual([
                        'value' => 'today',
                        'message' => 'La date doit être supérieure ou égale à la date du jour !'
                    ]),
                    new GreaterThan([
                        'propertyPath' => 'parent.all[registerDateLimit].data',
                        'message' => 'La date doit être supérieure à la date limite d\'inscription !'
                    ])
                ],
                'placeholder' => [
                    'day' => 'Jour', 'month' => 'Mois','year' => 'Année',
                    'hour' => 'Heure', 'minute' => 'Minute', 'second' => 'Seconde',
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-1',
                ],
            ])
            ->add('registerDateLimit',DateTimeType::class,[
                'html5'=>true,
                'widget' => 'single_text',
                'label' => 'Date et heure limite d\'inscription',
                'constraints' => [
                    new GreaterThanOrEqual([
                        'value' => 'today',
                        'message' => 'La date doit être supérieure ou égale à la date du jour !'
                    ])
                ],
                'placeholder' => [
                    'day' => 'Jour', 'month' => 'Mois','year' => 'Année'
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-1',
                ],

            ])

            ->add('MaxOfRegistration', IntegerType::class,[
                'label'=>' Nombre limite de places',
                'row_attr' => [
                    'class' => 'form-floating mb-1',
                ],
                'constraints'=>[new Positive(['message'=>'le nombre de participants doit être positif'])]
            ])


            ->add('duration',TimeType::class, [
                'html5'=>true,
                'widget' => 'single_text',
                'label' => 'Durée',
                'row_attr' => [
                    'class' => 'form-floating mb-1',
                ],
                'input'  => 'datetime',


            ])


            ->add('hangoutInformations', TextType::class,[
                'label'=>'Descripton et infos',
                'row_attr' => [
                    'class' => 'form-floating mb-1',
                ],

            ])
            ->add('campusOrganizerSite', EntityType::class,[
                'label' => 'Lieu',
                'class' => Campus::class,
                'choice_label' => 'name',
                'data'=>$options['defaultCampus'],
                'row_attr' => [
                    'class' => 'form-floating mb-1',
                ],
            ])
           ->add('city', EntityType::class,[
                'label' => 'Ville',
                'class' => City::class,
                'choice_label' => 'name',
                'query_builder' => function (EntityRepository $er) {
                   return $er->createQueryBuilder('c')
                       ->orderBy('c.name', 'ASC');
               },
               'row_attr' => [
                   'class' => 'form-floating mb-1',
               ],
                'mapped'=>false
            ])
            ->add('place', EntityType::class,[
                'label' => 'Lieu',
                'class' => Place::class,
                'choice_label' => 'name',
                'row_attr' => [
                    'class' => 'form-floating mb-1',
                ],
            ])
            ->add('street', TextType::class,[
                'label' => 'Ville',
                'row_attr' => [
                    'class' => 'form-floating mb-1',
                ],
                'mapped'=>false
            ])


            ->add('zip', IntegerType::class,[
                'label' => 'Code postal',
                'row_attr' => [
                    'class' => 'form-floating mb-1',
                ],
                'mapped'=>false
            ])
            ->add('latitude', TextType::class,[
                'label' => 'Latitude',
                'row_attr' => [
                    'class' => 'form-floating mb-1',
                ],
                'mapped'=>false
            ])
            ->add('longitude', TextType::class,[
                'label' => 'Longitude',
                'row_attr' => [
                    'class' => 'form-floating mb-1',
                ],
                'mapped'=>false
            ]);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Hangout::class,
        ]);
        $resolver->setRequired(['defaultCampus']);
    }
}
