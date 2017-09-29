<?php
namespace Authorization\Entity;

/**
 * @Entity @Table(name="public.token_data_cryptic")
 */
class UserDataModel
{

    /**
     * Primary key
     *
     * @id @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

    /**
     * Имя
     *
     * @Column(type="string", name="hash")
     */
    protected $hash;

    /**
     * Имя
     *
     * @Column(type="string", name="data")
     */
    protected $data;

    /**
     * Имя
     *
     * @Column(type="string", name="uptime")
     */
    protected $uptime;

    public function setData($data)
    {
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setHash($hash)
    {
        $this->hash = $hash;
    }

    public function getHash()
    {
        return $this->hash;
    }

    public function setUptime($uptime)
    {
        $this->uptime = $uptime;
    }

    public function getUptime()
    {
        return $this->uptime;
    }

    public function getId()
    {
        return $this->id;
    }
}
