<?php

namespace Tests\Acceptance\cattlevaccines;

use Tests\Acceptance\BaseAcceptanceCest;
use Tests\Support\AcceptanceTester;

class CattleVaccinesCest extends BaseAcceptanceCest
{
    private const USER_EMAIL = 'test@example.com';
    private const USER_PASSWORD = 'password';

    public function attachVaccineWithSuccess(AcceptanceTester $page): void
    {
        $page->login(self::USER_EMAIL, self::USER_PASSWORD);
        $page->amOnPage('/cattle/1/edit');
        $page->see('Editar Gado', '//h1');

        $page->click('Vacinas');
        $page->waitForElementVisible('#vaccine_id', 5);

        $page->selectOption('vaccine_id', '1');
        $page->executeJS(
            "const input = document.getElementById('application_date');\n" .
            "if (input) {\n" .

            "  input.value = arguments[0];\n" .

            "  input.dispatchEvent(new Event('input', { bubbles: true }));\n" .
            "  input.dispatchEvent(new Event('change', { bubbles: true }));\n" .
            "}",
            ['2026-06-20']
        );

        $page->click('Salvar Aplicação');
        $page->waitForText('Vacina registrada no histórico do animal!', 5);
        $page->see('Vacina registrada no histórico do animal!');
        $page->click('Vacinas');
        $page->see('20/06/2026');
    }

    public function detachVaccineWithSuccess(AcceptanceTester $page): void
    {
        $page->login(self::USER_EMAIL, self::USER_PASSWORD);
        $page->amOnPage('/cattle/1/edit');

        $page->click('Vacinas');
        $page->see('20/06/2026');
        $page->click('Excluir');
        $page->seeInPopup('Deseja realmente apagar este registro do histórico?');
        $page->acceptPopup();
        $page->waitForText('Registro de vacina removido com sucesso.', 5);
        $page->see('Registro de vacina removido com sucesso.');
    }
}
