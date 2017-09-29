<?php
namespace Authorization\Entity;

/**
 * @Entity @Table(name="public.token")
 */
class TokenModel
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
     * @Column(type="string", name="token")
     */
    protected $token;

    /**
     * Имя
     *
     * @Column(type="string", name="uptime")
     */
    protected $uptime;

    public function setToken($token)
    {
        $this->token = $token;
    }

    public function getToken()
    {
        return $this->token;
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

    public function getClassLocation()
    {
        return __CLASS__;
    }
}
