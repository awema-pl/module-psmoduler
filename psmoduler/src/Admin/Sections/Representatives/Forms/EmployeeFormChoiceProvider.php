<?php
declare(strict_types=1);

namespace Psmoduler\Admin\Sections\Representatives\Forms;

use Doctrine\ORM\EntityManagerInterface;
use PrestaShop\PrestaShop\Core\Form\FormChoiceProviderInterface;
use Employee;
use PrestaShopBundle\Entity\Repository\LangRepository;
use Psmoduler\Admin\Sections\Employees\Repositories\EmployeeRepository;
use Psmoduler\Admin\Sections\Representatives\Repositories\RepresentativeRepository;
use Symfony\Component\Translation\TranslatorInterface;

class EmployeeFormChoiceProvider implements FormChoiceProviderInterface
{
    /** @var TranslatorInterface $translator */
    private $translator;

    /** @var EmployeeRepository $employeeRepository */
    private $employeeRepository;

    /** @var RepresentativeRepository $representativeRepository */
    private $representativeRepository;

    public function __construct(TranslatorInterface $translator, EmployeeRepository $employeeRepository, RepresentativeRepository $representativeRepository)
    {
        $this->employeeRepository = $employeeRepository;
        $this->representativeRepository = $representativeRepository;
        $this->translator = $translator;
    }

    /**
     * Get employee choices.
     *
     * @return array
     */
    public function getChoices()
    {
        $employeesForChoice = $this->employeeRepository->getEmployeesForChoices();
        $representativeEmployeeIds = $this->representativeRepository->getEmployeeIds();
        $choices = [];
        $choices[$this->translator->trans('Select an employee', [], 'Modules.Psmoduler.Admin')] = '';
        foreach ($employeesForChoice as $employee) {
            $idEmployee = $employee['id_employee'];
            if (!in_array($idEmployee, $representativeEmployeeIds)) {
                $choices[sprintf('%d. %s (%s %s)', $idEmployee, $employee['email'], $employee['firstname'], $employee['lastname'])] = $employee['id_employee'];
            }
        }
        return $choices;
    }
}
