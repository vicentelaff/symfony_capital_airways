<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Flight;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FlightType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('flightSchedule', TimeType::class, [
                "label" => "Departure time:",
                "hours" => range(6, 23)
            ])
            ->add('flightPrice', NumberType::class, [
                "label" => "Flight's price:"
            ])
            ->add('discount', CheckboxType::class, [
                "label" => "10% Discount ?"
            ])
            ->add('places', IntegerType::class, [
                "label" => "Number of places:"
            ])
            ->add('departure', EntityType::class, [
                "class" => City::class,
                "choice_label" => "cityName",
                "label" => "Departure city:"
            ])
            ->add('arrival', EntityType::class, [
                "class" => City::class,
                "choice_label" => "cityName",
                "label" => "Arrival city:"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Flight::class,
            "required" => False
        ]);
    }
}
