<?php
namespace App\Form\Type;

use App\Entity\User;
use DateTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class updateProfileFormType extends AbstractType
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
        ->add('fullname',TextType::class,['attr'=>['class'=>'infor-item']])
        ->add('gender', ChoiceType::class, [ 'attr' => ['id' => 'radio_button'],
                'choices' =>
                    [
                        'Male' => 'male',
                        'Female' => 'female'
                    ],
                'expanded' => true
            ])
        ->add('email',EmailType::class,['attr'=>['class'=>'infor-item'],'required'=>false])
        ->add('phone',TextType::class,['attr'=>['class'=>'infor-item'],'required'=>false])
        ->add('address',TextType::class,['attr'=>['class'=>'infor-item'],'required'=>false])
        ->add('password',PasswordType::class,
        ['attr'=>['class'=>'infor-item','placeholder'=>'Enter your current password'],'required'=>false
        ])
        ->add('new_password',RepeatedType::class, [
            'type' => PasswordType::class,
            'invalid_message' => 'The password fields must match.',
            'options' => ['attr' => ['class' => 'infor-item']],
            'required' => false,
            'first_options' =>['label' => 'New Password','attr'=>['placeholder'=>'Enter your new password', 'class' => 'infor-item']],
            'second_options' =>['label' => 'Confirm New Password','attr'=>['placeholder'=>'Please confirm your new password', 'class' => 'infor-item']],
            'attr' => ['autocomplete' => 'off']])
        ->add('save',SubmitType::class,['attr'=>['class'=>'infor-item']]);
    }
}
?>