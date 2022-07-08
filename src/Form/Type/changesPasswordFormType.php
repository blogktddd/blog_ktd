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
        ['attr'=>['class'=>'infor-item'],'required'=>false
        ])
        ->add('new_password',RepeatedType::class, [
            'type' => PasswordType::class,
            'invalid_message' => 'The password fields must be match.',
            'options' => ['attr' => ['class' => 'infor-item']],
            'required' => false,
            'first_options' =>['label' => 'New Password','attr'=>[ 'class' => 'infor-item']],
            'second_options' =>['label' => 'Confirm Password','attr'=>[ 'class' => 'infor-item']],
            'attr' => ['autocomplete' => 'off']])
        ->add('Save',SubmitType::class,['attr'=>['class'=>'infor-item btn-save-change-pass']]);
    }
}

?>