<?php

use App\Tests\AcceptanceTester;
use Codeception\Attribute\Examples;
use Codeception\Example;

class CreateFakesCest
{
    #[Examples(1)]
    #[Examples(5)]
    #[Examples(20)]
    public function createFakeTask(AcceptanceTester $I, Example $example)
    {
        $I->amAuthenticated();
        $I->amOnPage("tasks/create/fake/$example[0]");
        $I->see('Access Denied');
    }
}