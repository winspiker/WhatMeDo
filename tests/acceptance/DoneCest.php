<?php

use App\Tests\AcceptanceTester;

class DoneCest
{

    public function createSuccessfullyTask(AcceptanceTester $I)
    {
        $I->amAuthenticated();
        $I->amOnPage('/');
        $I->fillField('title','Now created task title');
        $I->fillField('description','Now created task description');
        $I->click('Create');
        $I->see('Now created task title');
    }

    public function doneTask(AcceptanceTester $I)
    {
        $I->amAuthenticated();
        $I->amOnPage('/');
        $I->click(['link' => 'Done']);
        $I->see('done', 'td');
    }

    public function deleteCreatedTask(AcceptanceTester $I)
    {
        $I->amAuthenticated();
        $I->amOnPage('/');
        $I->click('Delete');
        $I->dontSee('Now created task title');
    }



}