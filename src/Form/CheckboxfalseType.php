<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints\File;


class CheckboxfalseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder 
                ->add('elegido', CheckboxType::class,[
                    'required' => false,
                    'row_attr' => ['class'=> 'checkbox'],
                    'attr'=>['value'=> '0'],
                    'label' => false,
                ])
                ->add('save', SubmitType::class, array('label' => 'Enviar'));
                
            
            
    }
}