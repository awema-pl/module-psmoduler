<?php

namespace Psmoduler\Admin\Sections\Representatives\Services;

use Doctrine\ORM\EntityManagerInterface;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\DataHandler\FormDataHandlerInterface;
use PrestaShopBundle\Entity\Repository\LangRepository;
use Psmoduler\Exceptions\PsmodulerException;
use Psmoduler\Admin\Sections\Representatives\Repositories\Contracts\RepresentativeRepository;
use Psmoduler\Admin\Sections\Representatives\Services\Contracts\RepresentativeService as RepresentativeServiceContract;

class RepresentativeService implements RepresentativeServiceContract
{
    /** @var LangRepository $langRepository */
    private $langRepository;

    /** @var RepresentativeRepository $representativeRepository */
    private $representativeRepository;

    /**
     * @param LangRepository $langRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(LangRepository $langRepository, RepresentativeRepository $representativeRepository) {
        $this->langRepository = $langRepository;
        $this->representativeRepository = $representativeRepository;
    }

    /**
     * Generate code
     *
     * @return string
     */
    public function generateCode(){
        $newCode = substr(str_shuffle(str_repeat("123456789ABCDEFGHIJKLMNPRSTUWYZ", 6)), 0, 6);
        $tries= 1000;
        for ($i = 0; $i < 100 ; $i++){
            $representative = $this->representativeRepository->findOneByCode($newCode);
            if (!$representative){
                return $newCode;
            }
        }
        throw new PsmodulerException('Not generate code for representative.');
    }
}
