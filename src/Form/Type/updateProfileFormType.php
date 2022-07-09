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
                        'Male' => 'Male',
                        'Female' => 'Female'
                    ],
                'expanded' => true
            ])
        ->add('email',EmailType::class,['attr'=>['class'=>'infor-item'],'required'=>false])
        ->add('phone',TextType::class,['attr'=>['class'=>'infor-item'],'required'=>false])
        ->add('address',TextType::class,['attr'=>['class'=>'infor-item'],'required'=>false])
        ->add('save',SubmitType::class,['attr'=>['class'=>'infor-item']]);
    }
}
?>