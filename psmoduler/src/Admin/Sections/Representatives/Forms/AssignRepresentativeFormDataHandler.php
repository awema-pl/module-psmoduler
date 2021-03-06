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

class AssignRepresentativeFormDataHandler implements FormDataHandlerInterface
{
    /** @var RepresentativeRepository $representativeRepository */
    private $representativeRepository;

    /** @var ProfileRepository $profileRepository */
    private $profileRepository;

    
    /** @var EntityManagerInterface $entityManager */
    private $entityManager;

    public function __construct(RepresentativeRepository $representativeRepository,ProfileRepository $profileRepository, EntityManagerInterface $entityManager) {
        $this->representativeRepository = $representativeRepository;
        $this->profileRepository = $profileRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $data)
    {
        $employee = new Employee($data['id_employee']);
        $profile = $this->profileRepository->find($employee->id_profile);
        if (!$profile){
            $employee->id_profile = $this->profileRepository->getIdProfileByName(ProfileInstaller::PROFILE_NAME);
        }
        $employee->active = true;
        $employee->last_passwd_gen = date('Y-m-d h:i:s', strtotime('-360 minutes'));
        if (false === $employee->save()) {
            throw new PsmodulerException(
                sprintf('Failed to save employee with email "%s"', $data['email'])
            );
        }

        /** @var PsmodulerRepresentative $representative */
        $representative = new PsmodulerRepresentative();
        $representative->setIdEmployee($data['id_employee']);
        $representative->setCode($data['code']);
        $representative->setPhone($data['phone']);
        $this->entityManager->persist($representative);
        $this->entityManager->flush();

        return $representative->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function update($id, array $data)
    {
        return null;
    }
}
