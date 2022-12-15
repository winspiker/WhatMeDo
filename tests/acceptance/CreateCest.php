<?php

use App\Tests\AcceptanceTester;

class CreateCest
{
    public function createEmptyErrorTask(AcceptanceTester $I)
    {
        $I->amAuthenticated();
        $I->amOnPage('/');
        $I->click('Create');
        $I->see('Title must be at least 5 characters long');
    }


    public function createLongErrorTask(AcceptanceTester $I)
    {
        $I->amAuthenticated();
        $I->amOnPage('/');
        $I->fillField('title','This is more 50 char <------------------------------');
        $I->fillField('description','test task');
        $I->click('Create');
        $I->see('Title cannot be longer than 50 characters');
    }

    public function createSuccessfullyTask(AcceptanceTester $I)
    {
        $I->amAuthenticated();
        $I->amOnPage('/');
        $I->fillField('title','Now created task title');
        $I->fillField('description','Now created task description');
        $I->click('Create');
        $I->see('Now created task title');
    }

    public function deleteCreatedTask(AcceptanceTester $I)
    {
        $I->amAuthenticated();
        $I->amOnPage('/');
        $I->click('Delete');
        $I->dontSee('Now created task title');
    }

}