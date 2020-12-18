<?php
declare(strict_types=1);

namespace Psmoduler\Admin\Sections\Representatives\Forms;

use Doctrine\DBAL\Exception\NonUniqueFieldNameException;
use Doctrine\ORM\EntityManagerInterface;
use Psmoduler\Admin\Sections\Commons\Exceptions\PsmodulerException;
use Psmoduler\Admin\Sections\Installations\Services\Database\ProfileInstaller;
use Psmoduler\Admin\Sections\Profiles\Repositories\ProfileRepository;
use Psmoduler\Admin\Sections\Representatives\Services\Contracts\RepresentativeService;
use Psmoduler\Entity\Admin\Sections\Representatives\PsmodulerRepresentative;
use Psmoduler\Admin\Sections\Representatives\Repositories\RepresentativeRepository;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\DataHandler\FormDataHandlerInterface;
use Employee;
use Tab;

class RepresentativeFormDataHandler implements FormDataHandlerInterface
{
    /** @var RepresentativeRepository $representativeRepository */
    private $representativeRepository;

    /** @var ProfileRepository $profileRepository */
    private $profileRepository;
    
    /** @var EntityManagerInterface $entityManager */
    private $entityManager;

    public function __construct(RepresentativeRepository $representativeRepository, ProfileRepository $profileRepository, EntityManagerInterface $entityManager) {
        $this->representativeRepository = $representativeRepository;
        $this->profileRepository = $profileRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $data)
    {
        $employee = new Employee();
        $employee->firstname = $data['firstname'];
        $employee->lastname = $data['lastname'];
        $employee->email = $data['email'];
        $employee->setWsPasswd($data['password']);
        $employee->id_lang = (int) $data['id_lang'];
        $employee->active = true;
        $employee->optin = true;
        $employee->last_passwd_gen = date('Y-m-d h:i:s', strtotime('-360 minutes'));
        $employee->bo_theme = 'default';
        $employee->bo_menu = 1;
        $employee->default_tab  = (int)Tab::getIdFromClassName('PsmodulerAdminRepresentativesRepresentative');;
        $employee->id_profile = $this->profileRepository->getIdProfileByName(ProfileInstaller::PROFILE_NAME);
        if (false === $employee->add()) {
            throw new PsmodulerException(
                sprintf('Failed to add new employee with email "%s"', $data['email'])
            );
        }
        $representative = new PsmodulerRepresentative();
        $representative->setCode($data['code']);
        $representative->setPhone($data['phone']);
        $representative->setIdEmployee($employee->id);
        $this->entityManager->persist($representative);
        $this->entityManager->flush();
        return $representative->getId();
        
    }

    /**
     * {@inheritdoc}
     */
    public function update($id, array $data)
    {
        $representative = $this->representativeRepository->findOneById($id);
        
        $employee = new Employee($representative->getIdEmployee());
        $employee->firstname = $data['firstname'];
        $employee->lastname = $data['lastname'];
        $employee->email = $data['email'];
        if ($data['password']){
            $employee->setWsPasswd($data['password']);
            $employee->last_passwd_gen = date('Y-m-d h:i:s', strtotime('-360 minutes'));
        }
        $employee->id_lang = (int) $data['id_lang'];
       if (false === $employee->save()) {
            throw new PsmodulerException(
                sprintf('Failed to save employee with email "%s"', $data['email'])
            );
        }
        $representative->setCode($data['code']);
        $representative->setPhone($data['phone']);
        $this->entityManager->flush();
        return $representative->getId();
    }
}
