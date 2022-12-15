<?php

use App\Tests\AcceptanceTester;

class AuthCest
{

    public function failedLoginWorks(AcceptanceTester $I)
    {
        $I->amOnPage('/login');
        $I->fillField(['name' => 'email'], 'undefine@test.com');
        $I->fillField(['name' => 'password'], 'undefine');
        $I->click("Sign in");
        $I->see('Username could not be found.');
    }

    public function loginWorks(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->fillField(['name' => 'email'], 'test@test.com');
        $I->fillField(['name' => 'password'], 'test');
        $I->click("Sign in");
        $I->see('Create');
    }


    public function logoutWorks(AcceptanceTester $I)
    {
        $I->amAuthenticated();
        $I->amOnPage('/');
        $I->click("Log Out");
        $I->see('Sign in');
        $I->deleteSessionSnapshot('login');
    }

}