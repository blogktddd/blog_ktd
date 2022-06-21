<?php
    namespace App\Form\Type;

    use App\Entity\Post;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\FileType;
    use Symfony\Component\Form\Extension\Core\Type\SubmitType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class postFormType extends AbstractType
    {
        public function configureOptions(OptionsResolver $resolver)
        {
            $resolver->setDefaults([
                    'data_class' => Post::class
                ]);
        }

        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $builder
                ->add('image', FileType::class, ['attr'])
                ->add('caption', TextType::class)
                ->add('Post', SubmitType::class);
        }
    }
