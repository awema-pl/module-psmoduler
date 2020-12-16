<?php
declare(strict_types=1);

namespace Psmoduler\Admin\Sections\Representatives\Forms;

use PrestaShopBundle\Form\Admin\Type\CommonAbstractType;
use PrestaShopBundle\Service\Routing\Router;
use PrestaShopBundle\Translation\TranslatorAwareTrait;
use Psmoduler\Admin\Sections\Representatives\Repositories\RepresentativeRepository;
use Psmoduler\Entity\Admin\Sections\Representatives\PsmodulerRepresentative;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Constraints\Blank;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\CallbackValidator;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use PrestaShopBundle\Form\Admin\Type\TranslatorAwareType;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class AssignRepresentativeType extends AbstractType
{
    use TranslatorAwareTrait;
    
    /** @var RepresentativeRepository $representativeRepository */
    private $representativeRepository;
    
    /** @var array $employeeChoices */
    private $employeeChoices;
    
    public function __construct(RepresentativeRepository $representativeRepository, $employeeChoices )
    {
        $this->representativeRepository = $representativeRepository;
        $this->employeeChoices = $employeeChoices;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('id_employee', ChoiceType::class, [
                'label' => 'Employee',
                'help' => 'Create a new eployee: Advanced Parameters > Team',
                'translation_domain' => 'Admin.Global',
                'choices' => $this->employeeChoices,
                'constraints' => [
                    new NotBlank(),
                    new Callback(function ($fieldValue, ExecutionContextInterface $context, $payload) use($options) {
                        /** @var PsmodulerRepresentative $representative */
                        $representative = $this->representativeRepository->findOneByIdEmployee($fieldValue);
                        if ($representative){
                            $context->buildViolation($this->trans('This employee is already a representative.', [], 'Modules.Psmoduler.Admin'))->atPath('code')->addViolation();
                        }
                    }),
                ]
            ])
            ->add('code', TextType::class, [
                'label' => 'Representative code',
                'help' => 'Representative code (e.g. KR0001).',
                'translation_domain' => 'Modules.Psmoduler.Admin',
                'constraints' => [
                    $this->getLengthConstraint(255),
                    new NotBlank(),
                    new Callback(function ($fieldValue, ExecutionContextInterface $context, $payload) use($options) {
                        $representative = $this->representativeRepository->findOneByCode($fieldValue);
                        if ($representative){
                            $context->buildViolation($this->trans('This representative code already exists.', [],'Modules.Psmoduler.Admin'))->atPath('code')->addViolation();
                        }
                    }),
                ]
            ])
            ->add('phone', TextType::class, [
                'label' => 'Phone',
                'translation_domain' => 'Modules.Psmoduler.Admin',
                'required' =>false,
                'constraints' => [
                    $this->getLengthConstraint(255)
                ]
            ]);
    }

    /**
     * Get length constraint
     *
     * @param int $maxLength
     * @param int|null $minLength
     *
     * @return Length
     */
    private function getLengthConstraint($maxLength, $minLength = null)
    {
        $options = [
            'max' => $maxLength,
            'maxMessage' => $this->trans(
                'This field cannot be longer than %limit% characters',
                ['%limit%' => $maxLength],
                'Admin.Notifications.Error'
            )
        ];

        if (null !== $minLength) {
            $options['min'] = $minLength;
            $options['minMessage'] = $this->trans(
                'This field cannot be shorter than %limit% characters',
                ['%limit%' => $minLength],
                'Admin.Notifications.Error'
            );
        }

        return new Length($options);
    }

}
