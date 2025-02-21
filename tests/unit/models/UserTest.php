<?php

namespace tests\unit\models;

use app\models\user;

class UserTest extends \Codeception\Test\Unit
{
    public function testFindUserById()
    {
        verify($user = user::findIdentity(100))->notEmpty();
        verify($user->username)->equals('admin');

        verify(user::findIdentity(999))->empty();
    }

    public function testFindUserByAccessToken()
    {
        verify($user = user::findIdentityByAccessToken('100-token'))->notEmpty();
        verify($user->username)->equals('admin');

        verify(user::findIdentityByAccessToken('non-existing'))->empty();
    }

    public function testFindUserByUsername()
    {
        verify($user = user::findByUsername('admin'))->notEmpty();
        verify(user::findByUsername('not-admin'))->empty();
    }

    /**
     * @depends testFindUserByUsername
     */
    public function testValidateUser()
    {
        $user = user::findByUsername('admin');
        verify($user->validateAuthKey('test100key'))->notEmpty();
        verify($user->validateAuthKey('test102key'))->empty();

        verify($user->validatePassword('admin'))->notEmpty();
        verify($user->validatePassword('123456'))->empty();        
    }

}
