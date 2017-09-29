<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 24.08.17
 * Time: 11:52
 */

namespace Search\Model;


use PHPUnit\Framework\TestCase;
use Search\Model\UserInfo;

class UserInfoTest extends TestCase
{
public $userInfo;
    public function setUp()
    {
        $this->userInfo=new UserInfo();
        parent::setUp();
    }
   //ToDo Написать тесты, когда будет понятно, что тут будет
public function testSetInfo(){
        $token='token';
    $this->userInfo->setInfo($token);
    $this->assertObjectHasAttribute('login',$this->userInfo);
    $this->assertObjectHasAttribute('password',$this->userInfo);
    $this->assertObjectHasAttribute('ogaiCode',$this->userInfo);
}
}