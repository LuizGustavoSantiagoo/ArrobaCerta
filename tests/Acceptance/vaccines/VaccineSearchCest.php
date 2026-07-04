<?php

namespace Tests\Acceptance\vaccines;

use Tests\Acceptance\BaseAcceptanceCest;
use Tests\Support\AcceptanceTester;

class VaccineSearchCest extends BaseAcceptanceCest
{
    private const USER_EMAIL = 'test@example.com';
    private const USER_PASSWORD = 'password';

    public function searchVaccineWithAjax(AcceptanceTester $page): void
    {
        \Database\Populate\VaccinePopulate::populate();

        $page->login(self::USER_EMAIL, self::USER_PASSWORD);
        $page->amOnPage('/vaccines');
        $page->waitForElementVisible('#search', 5);

        $page->dontSee('Brucelose', '#vaccinesTableBody');

        $page->fillField('#search', 'Brucelose');
        $page->click('#searchBtn');

        $page->waitForText('Brucelose', 5, '#vaccinesTableBody');

        $page->see('Brucelose', '#vaccinesTableBody');
        $page->dontSee('Febre Aftosa', '#vaccinesTableBody');
    }
}
