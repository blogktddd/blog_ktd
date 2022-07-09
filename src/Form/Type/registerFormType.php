<?php
    namespace App\Form\Type;

    use App\Entity\User;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
    use Symfony\Component\Form\Extension\Core\Type\PasswordType;
    use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
    use Symfony\Component\Form\Extension\Core\Type\SubmitType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class registerFormType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $builder
                ->add('username', TextType::class, ['attr' => ['class' => 'text']])
                ->add('password', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'invalid_message' => 'The password not match',
                    'options' => ['attr' => ['class' => 'text']],
                    'required' => true,
                    'first_options' => ['label' => 'Password'],
                    'second_options' => ['label'=>'Confirm Password']
                ])
                ->add('fullname', TextType::class,['attr' => ['class' => 'text']])
                ->add('gender', ChoiceType::class, [
                    'choices' =>
                    [
                        'Male' => 'Male',
                        'Female' => 'Female'
                    ],
                    'expanded' => true
                ])
                ->add('Register', SubmitType::class, ['attr' => ['class' => 'signup']]);
        }

        public function configureOptions(OptionsResolver $resolver)
        {
            $resolver->setDefaults([
                'data_class' => User::class
            ]);
        }
    }