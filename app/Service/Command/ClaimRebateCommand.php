<?php


namespace Mabes\Service\Command;

use Mabes\Core\CommonBehaviour\MassAssignmentTrait;
use Mabes\Core\CommonBehaviour\RepositoryAwareTrait;
use Mabes\Core\Contracts\CommandInterface;
use Mabes\Entity\Member;
use Symfony\Component\Validator\Constraints as Assert;

class ClaimRebateCommand implements CommandInterface
{
    use RepositoryAwareTrait;
    use MassAssignmentTrait;

    /**
     * @Assert\NotBlank(message="MyHotForex ID tidak boleh kosong")
     * @Assert\Regex(pattern="/^\d*(\,|\.)?\d+$/", message="MyHotForex ID harus angka")
     * @var int
     */
    private $account_id;

    /**
     * @Assert\NotBlank(message="No akun trading tidak boleh kosong")
     * @Assert\Regex(pattern="/^\d*(\,|\.)?\d+$/", message="No akun trading harus angka")
     * @var int
     */
    private $mt4_account;

    /**
     * @Assert\NotBlank(message="type tidak boleh kosong")
     * @var string
     */
    private $type;

    /**
     * @return int
     */
    public function getAccountId()
    {
        return $this->account_id;
    }

    /**
     * @param int $account_id
     */
    public function setAccountId($account_id)
    {
        $this->account_id = $account_id;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return int
     */
    public function getMt4Account()
    {
        return $this->mt4_account;
    }

    /**
     * @param int $mt4_account
     */
    public function setMt4Account($mt4_account)
    {
        $this->mt4_account = $mt4_account;
    }

    /**
     * @Assert\False(message="MyHotForex ID tidak ditemukan didalam database, silahkan validasi akun anda terlebih dahulu")
     */
    public function isMemberExist()
    {
        $member = $this->getRepository()->findOneBy(["account_id" => $this->getAccountId()]);

        return $member instanceof Member ? false : true;
    }
}

// EOF
