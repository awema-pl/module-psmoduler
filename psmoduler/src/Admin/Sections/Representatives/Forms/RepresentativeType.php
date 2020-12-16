<?php
declare(strict_types=1);

namespace Psmoduler\Admin\Sections\Representatives\Forms;

use PrestaShop\PrestaShop\Core\Domain\Employee\ValueObject\FirstName;
use PrestaShop\PrestaShop\Core\Domain\Employee\ValueObject\LastName;
use PrestaShop\PrestaShop\Core\Domain\Employee\ValueObject\Password;
use PrestaShop\PrestaShop\Core\Domain\ValueObject\Email as EmployeeEmail;
use PrestaShopBundle\Form\Admin\Type\ChangePasswordType;
use PrestaShopBundle\Form\Admin\Type\CommonAbstractType;
use PrestaShopBundle\Service\Routing\Router;
use PrestaShopBundle\Translation\TranslatorAwareTrait;
use Psmoduler\Admin\Sections\Employees\Repositories\EmployeeRepository;
use Psmoduler\Admin\Sections\Representatives\Repositories\RepresentativeRepository;
use Psmoduler\Admin\Sections\Commons\Exceptions\PsmodulerException;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Constraints\Blank;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\CallbackValidator;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use PrestaShopBundle\Form\Admin\Type\TranslatorAwareType;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Context;

class RepresentativeType extends AbstractType
{
    use TranslatorAwareTrait;

    /** @var RepresentativeRepository $representativeRepository */
    private $representativeRepository;

    /** @var EmployeeRepository $employeeRepository */
    private $employeeRepository;

    /** @var array $languagesChoices */
    private $languagesChoices;

    public function __construct(RepresentativeRepository $representativeRepository, EmployeeRepository $employeeRepository, $languagesChoices)
    {
        $this->representativeRepository = $representativeRepository;
        $this->employeeRepository = $employeeRepository;
        $this->languagesChoices = $languagesChoices;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('code', TextType::class, [
                'label' => 'Representative code',
                'help' => 'Representative code (e.g. KR0001).',
                'translation_domain' => 'Modules.Psmoduler.Admin',
                'constraints' => [
                    $this->getLengthConstraint(255),
                    new NotBlank(),
                    new Callback(function ($fieldValue, ExecutionContextInterface $context, $payload) use (&$options) {
                        $idRepresentative = $options['id_representative'];
                        $isForEditing = $options['is_for_editing'];
                        $representative = $this->representativeRepository->findOneByCode($fieldValue);
                        if ((!$isForEditing && $representative) || ($isForEditing && $representative && $idRepresentative !== $representative->getId())) {
                            $context->buildViolation($this->trans('This representative code already exists.', [], 'Modules.Psmoduler.Admin'))->atPath('code')->addViolation();
                        }
                    }),
                ]
            ])
            ->add('firstname', TextType::class, [
                'label' => 'First name',
                'translation_domain' => 'Modules.Psmoduler.Admin',
                'constraints' => [
                    new NotBlank(),
                    $this->getLengthConstraint(FirstName::MAX_LENGTH),
                ],
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Last name',
                'translation_domain' => 'Modules.Psmoduler.Admin',
                'constraints' => [
                    new NotBlank(),
                    $this->getLengthConstraint(LastName::MAX_LENGTH),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'E-mail',
                'translation_domain' => 'Modules.Psmoduler.Admin',
                'constraints' => [
                    new NotBlank(),
                    $this->getLengthConstraint(EmployeeEmail::MAX_LENGTH),
                    new Email([
                        'message' => $this->trans('This field is invalid', [], 'Admin.Notifications.Error'),
                    ]),
                    new Callback(function ($fieldValue, ExecutionContextInterface $context, $payload) use (&$options) {
                        $employee = $this->employeeRepository->findOneByEmail($fieldValue);
                        if ($employee){
                            $representative = $this->representativeRepository->findOneByIdEmployee($employee['id_employee']);
                            if (!$representative){
                                $context->buildViolation($this->trans('The employee already exists. Proceed to assigning a representative.', [], 'Modules.Psmoduler.Admin'))->atPath('email')->addViolation();
                            } else if ((!$options['is_for_editing'] && $representative) || ($options['is_for_editing'] && $representative && $representative->getId() !== $options['id_representative'])){
                                $context->buildViolation($this->trans('This representative email already exists.', [], 'Modules.Psmoduler.Admin'))->atPath('email')->addViolation();
                            }
                        }
                    }),
                ],
            ]);
        $passwordHelp = $this->trans('Password should be at least %num% characters long.', ['%num%' => 8], 'Admin.Advparameters.Help');
        if ($options['is_for_editing']){
            $passwordHelp = $this->trans('Leave blank if you don\'t want to change the password.', [], 'Modules.Psmoduler.Admin') .' '. $passwordHelp;
            $passwordConstraints = [];
        } else {
            $passwordConstraints = [
                new NotBlank(),
                $this->getLengthConstraint(Password::MAX_LENGTH, Password::MIN_LENGTH)
            ];
        }

        $builder->add('password', PasswordType::class, [
            'required' => !$options['is_for_editing'],
            'label' => 'Password',
            'help' => $passwordHelp,
            'translation_domain' => 'Admin.Global',
            'constraints' => $passwordConstraints,
        ])
            ->add('id_lang', ChoiceType::class, [
                'label' => 'Language',
                'translation_domain' => 'Admin.Global',
                'choices' => $this->languagesChoices,
            ])
            ->add('phone', TextType::class, [
                'label' => 'Phone',
                'translation_domain' => 'Modules.Psmoduler.Admin',
                'required' => false,
                'constraints' => [
                    $this->getLengthConstraint(255),
                ]
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'id_representative' => null,
                'is_for_editing' => false,
            ])
            ->setAllowedTypes('id_representative', [
                'integer',
                'null'
            ])
            ->setAllowedTypes('is_for_editing', 'bool');
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
