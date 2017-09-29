<?php
namespace Authorization\Model;

use Authorization\Model\Crypt;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Authorization\Entity\UserDataModel;
use SebastianBergmann\CodeCoverage\Report\PHP;

class UserData
{

    /** @var $storage UserDataModel */
    protected $storage;

    /**
     * @var $crypt Crypt
     * */
    protected $crypt;

    /** @var string $hashCritical токен+соль+critical */
    protected $hashKeyCritical;

    /** @var string $hash токен+соль */
    protected $hashKey;

    /**
     * @var $storage PostgreUserDataStorage
     */
    public function __construct($token, $storage)
    {
        $this->crypt = new Crypt();

        $this->hash = $this->crypt->generateKeyHash($token);

        $this->storage = $storage;

        $this->hashCritical = $this->crypt->generateKeyHash($token,true);
    }

    /**
     * Сохранение данных
     * @var array $data
     * @return integer id
     * */
    public function save($data, $critical = false)
    {
        $key = $this->getHashKey($critical);

        $data = $this->prepareSave($data, $key);

        empty($data) ?: $this->storage->set($key, $data);

        return true;
    }

    /**
     * Поиск данных
     * @var string $hash
     * @return array
     * */
    public function find($hash)
    {
        $result = $this->storage->get($hash);

        return $this->afterGet($result, $hash);
    }

    /**
     * Перед сохранением
     * Превратит в json и закодирует строку
     * @var $data array данные для кодирваония
     * @var $cryptKey string ключь кодировки
     * @return string json
     * */
    public function prepareSave($data, $cryptKey)
    {
        $toJson = $this->dataEncode($data);
        return $this->crypt->enCrypt($toJson, $cryptKey);
    }

    /**
     * Раскодирует данные
     * @var $data string данные для декодирования
     * @var $cryptKey string ключь кодировки
     * @return mixed
     * */
    public function afterGet($data, $cryptKey)
    {
        $data = $this->dataDecode($this->crypt->deCrypt($data, $cryptKey));
        return $data;
    }

    /**
     * Вернет ключь в зависимости от $critical
     * @var $critical boolean
     * @return string
     * */
    public function getHashKey($critical = false)
    {
        return $critical ? $this->hashCritical : $this->hash;
    }

    /**
     * @return string
     * */
    public function dataEncode($data)
    {
        return json_encode($data);
    }

    /**
     * @return array
     * */
    public function dataDecode($data)
    {
        return json_decode($data, true);
    }
}