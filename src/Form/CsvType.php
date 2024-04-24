<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;


class CsvType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder 
                ->add('file', FileType::class,[
                    'mapped' => false,
                    'constraints' => [
                        new File([
                            'mimeTypes' => [
                                'text/csv',
                                'text/plain',
                            ],
                            'mimeTypesMessage' => 'Sube un archivo csv Valido',
                        ])
                    ],
                ])
            ->add('save', SubmitType::class, array('label' => 'Enviar'));
            
    }
}