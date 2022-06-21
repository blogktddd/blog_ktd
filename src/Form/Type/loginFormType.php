<?php
    namespace App\Form\Type;

    use App\Entity\User;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\PasswordType;
    use Symfony\Component\Form\Extension\Core\Type\SubmitType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class loginFormType extends AbstractType
    {
        public function configureOptions(OptionsResolver $resolver)
        {
           $resolver->setDefaults([
               'data_class' => User::class
           ]);
        }

        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $builder
                ->add('username', TextType::class, [ 'attr' => [ 'class' => 'text' ]])
                ->add('password', PasswordType::class, [ 'attr' => [ 'class' => 'text' ]])
                ->add('login', SubmitType::class, [ 'attr' => [ 'class' => 'signin' ]]);
        }
    }
