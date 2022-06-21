<?php
namespace App\Form\Type;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class changesPasswordFormType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data-class'=>User::class
        ]);
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('password',PasswordType::class,
        ['attr'=>['class'=>'put your class name here','placeholder'=>'enter your crrent password']
        ])
        ->add('new_password',RepeatedType::class, [
            'type' => PasswordType::class,
            'invalid_message' => 'The password fields must match.',
            'options' => ['attr' => ['class' => 'put your class name here']],
            'required' => true,
            'first_options' =>['label' => 'New Password','attr'=>['placeholder'=>'enter your new password']],
            'second_options' =>['label' => 'Repeat Password','attr'=>['placeholder'=>'please confirm your new password']],
            'attr' => ['autocomplete' => 'off']])
        ->add('Save',SubmitType::class,['attr'=>['class'=>'put your class name here']]);
    }
}

?>