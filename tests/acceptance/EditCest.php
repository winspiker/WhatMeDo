<?php

use App\Tests\AcceptanceTester;

class EditCest
{
    private string $editUrl;


    public function createSuccessfullyTask(AcceptanceTester $I)
    {
        $I->amAuthenticated();
        $I->amOnPage('/');
        $I->fillField('title','Bad title task');
        $I->fillField('description','Bad description task');
        $I->click('Create');
        $I->see('Bad title task');
    }


    public function editPageWorks(AcceptanceTester $I): void
    {
        $I->amAuthenticated();
        $I->amOnPage('/');
        $I->click(['link' => 'Edit']);
        $I->seeElement('input', ['value' => 'Bad title task']);
        $this->editUrl = $I->grabFromCurrentUrl();
    }

    /**
     * @depends editPageWorks
     */
    public function updateEmptyErrorTask(AcceptanceTester $I)
    {
        $I->amAuthenticated();
        $I->amOnPage($this->editUrl);
        $I->fillField('title','');
        $I->fillField('description','');
        $I->click('Update');
        $I->see('Title must be at least 5 characters long');
    }

    /**
     * @depends editPageWorks
     */
    public function updateLongErrorTask(AcceptanceTester $I)
    {
        $I->amAuthenticated();
        $I->amOnPage($this->editUrl);
        $I->fillField('title','This is more 50 char <------------------------------');
        $I->fillField('description','test task');
        $I->click('Update');
        $I->see('Title cannot be longer than 50 characters');
    }

    /**
     * @depends editPageWorks
     */
    public function updateSuccessfullyTask(AcceptanceTester $I)
    {
        $I->amAuthenticated();
        $I->amOnPage($this->editUrl);
        $I->fillField('title','Updated title');
        $I->fillField('description','Updated description');
        $I->click('Update');
        $I->see('Updated title');
    }

    public function deleteCreatedTask(AcceptanceTester $I)
    {
        $I->amAuthenticated();
        $I->amOnPage('/');
        $I->click('Delete');
        $I->dontSee('Updated title');
    }

}