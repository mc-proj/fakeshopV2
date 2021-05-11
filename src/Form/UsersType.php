<?php

namespace App\Form;

use App\Entity\UsersSP;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
//use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
//use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class UsersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("pseudo", TextType::class, [
                "label" => "Nom d'utilisateur (champ obligatoire)",
                "attr" => [
                    "class" => "champ"
                ],
                "constraints" => [

                    new NotBlank([
                        "message" => "Veuillez renseigner ce champ"
                    ]),
                    new Length([
                        "min" => 5,
                        "max" => 255,
                    ])
                ]
            ])
            ->add("nom", TextType::class, [
                "label" => "Nom (champ obligatoire)",
                "attr" => [
                    "class" => "champ"
                ],
                "constraints" => [

                    new NotBlank([
                        "message" => "Veuillez renseigner ce champ"
                    ]),
                    new Length([
                        "min" => 5,
                        "max" => 255,
                    ])
                ]
            ])
            ->add("prenom", TextType::class, [
                "label" => "Prénom (champ obligatoire)",
                "attr" => [
                    "class" => "champ"
                ],
                "constraints" => [

                    new NotBlank([
                        "message" => "Veuillez renseigner ce champ"
                    ]),
                    new Length([
                        "min" => 3,
                        "max" => 255,
                    ])
                ]
            ])
            ->add("adresse", TextType::class, [
                "label" => "Adresse (champ obligatoire)",
                "attr" => [
                    "class" => "champ"
                ],
                "constraints" => [

                    new NotBlank([
                        "message" => "Veuillez renseigner ce champ"
                    ]),
                    new Length([
                        "min" => 5,
                        "max" => 255,
                    ])
                ]
            ])
            ->add("ville", TextType::class, [
                "label" => "Ville (champ obligatoire)",
                "attr" => [
                    "class" => "champ"
                ],
                "constraints" => [

                    new NotBlank([
                        "message" => "Veuillez renseigner ce champ"
                    ]),
                    new Length([
                        "min" => 5,
                        "max" => 45,
                    ])
                ]
            ])
            ->add("code_postal", TextType::class, [
                "label" => "Code postal (champ obligatoire)",
                "attr" => [
                    "class" => "champ"
                ],
                "constraints" => [

                    new NotBlank([
                        "message" => "Veuillez renseigner ce champ"
                    ]),
                    new Length([
                        "min" => 4,
                        "max" => 255,
                    ])
                ]
            ])
            ->add("Pays",  CountryType::class, [
                "label" => "Pays (champ obligatoire)",
                "attr" => [
                    "class" => "champ"
                ],
                "preferred_choices" => array('FR')
            ])
            ->add("telephone", TextType::class, [
                "label" => "Téléphone (champ obligatoire)",
                "attr" => [
                    "class" => "champ"
                ],
                "constraints" => [
                    new NotBlank([
                        "message" => "Veuillez renseigner un numéro de téléphone"
                    ]),
                    new Length([
                        "min" => 5,
                        "max" => 45,
                    ])
                ],
            ])
            ->add("email", RepeatedType::class, [
                "type" => EmailType::class,
                "mapped" => true,
                "first_options"  => ["label" => "Adresse email (champ obligatoire)", "attr" => ["class" => "champ"]],
                "second_options" => ["label" => "Confirmer adresse email (champ obligatoire)", "attr" => ["class" => "champ"]],
                "invalid_message" => "Les adresses email doivent concorder",
                "constraints" => [
                    new NotBlank([
                        "message" => "Veuillez renseigner une adresse mail",
                    ]),
                    new Length([
                        "min" => 6,
                        "max" => 45,
                    ]),
                    new Regex([
                        "pattern" => "/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/",
                        "message" => "Adresse email invalide"
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UsersSP::class,
        ]);
    }
}
