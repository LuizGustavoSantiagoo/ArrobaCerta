<?php

namespace Tests\Acceptance\cattle;

use Tests\Acceptance\BaseAcceptanceCest;
use Tests\Support\AcceptanceTester;

class CattleIndexCest extends BaseAcceptanceCest
{
    private const USER_EMAIL = 'test@example.com';
    private const USER_PASSWORD = 'password';

    public function registerWithInvalidData(AcceptanceTester $page): void
    {
        $page->login(self::USER_EMAIL, self::USER_PASSWORD);
        $page->amOnPage('/cattle');
        $page->see('Listagem de vacas', '//h1');
        $page->click('Adicionar vacas');
        $page->waitForElementVisible('#breed', 5);

        $page->click('Salvar');
        $page->waitForText('Erro ao salvar o gado. Verifique os dados e tente novamente.', 5);
        $page->see('Erro ao salvar o gado. Verifique os dados e tente novamente.');
    }

    public function registerWithValidData(AcceptanceTester $page): void
    {
        $page->login(self::USER_EMAIL, self::USER_PASSWORD);
        $page->amOnPage('/cattle');
        $page->see('Listagem de vacas', '//h1');

        $page->click('Adicionar vacas');
        $page->waitForElementVisible('#breed', 5);
        $page->fillField('cattle[breed]', 'nelore-01');
        $page->fillField('cattle[purchase_value_in_cents]', '1200');
        $page->executeJS(
            "const input = document.getElementById('purchase_date');\n" .
            "if (input) {\n" .
            "  input.value = arguments[0];\n" .
            "  input.dispatchEvent(new Event('input', { bubbles: true }));\n" .
            "  input.dispatchEvent(new Event('change', { bubbles: true }));\n" .
            "}",
            ['2026-05-16T00:00']
        );
        $page->fillField('cattle[purchase_type]', 'Leilao');
        $page->click('Salvar');
        $page->waitForText('Gado salvo com sucesso!', 5);
        $page->see('Gado salvo com sucesso!');

        $page->see('nelore-01');
    }

    public function updateWithInvalidData(AcceptanceTester $page): void
    {
        $page->login(self::USER_EMAIL, self::USER_PASSWORD);
        $page->amOnPage('/cattle');
        $page->see('Listagem de vacas', '//h1');

        $page->click('Adicionar vacas');
        $page->waitForElementVisible('#breed', 5);
        $page->fillField('cattle[breed]', ' ');
        $page->fillField('cattle[purchase_value_in_cents]', '1500');
        $page->executeJS(
            "const input = document.getElementById('purchase_date');\n" .
            "if (input) {\n" .
            "  input.value = arguments[0];\n" .
            "  input.dispatchEvent(new Event('input', { bubbles: true }));\n" .
            "  input.dispatchEvent(new Event('change', { bubbles: true }));\n" .
            "}",
            ['2026-05-16T00:00']
        );
        $page->click('Salvar');
        $page->waitForText('Gado salvo com sucesso!', 5);
        $page->see('Gado salvo com sucesso!');

        $page->click('a[href^="/cattle/edit/"]');
        $page->waitForElementVisible('#breed', 5);
        $page->see('Editar Gado', '//h1');
        $page->fillField('cattle[breed]', '');
        $page->click('Atualizar');

        $page->waitForText('Erro ao atualizar o gado. Verifique os dados e tente novamente.', 5);
        $page->see('Erro ao atualizar o gado. Verifique os dados e tente novamente.');
    }

    public function updateWithValidData(AcceptanceTester $page): void
    {
        $page->login(self::USER_EMAIL, self::USER_PASSWORD);
        $page->amOnPage('/cattle');
        $page->see('Listagem de vacas', '//h1');

        $page->click('Adicionar vacas');
        $page->waitForElementVisible('#breed', 5);
        $page->fillField('cattle[breed]', 'nelore-03');
        $page->fillField('cattle[purchase_value_in_cents]', '1800');
        $page->executeJS(
            "const input = document.getElementById('purchase_date');\n" .
            "if (input) {\n" .
            "  input.value = arguments[0];\n" .
            "  input.dispatchEvent(new Event('input', { bubbles: true }));\n" .
            "  input.dispatchEvent(new Event('change', { bubbles: true }));\n" .
            "}",
            ['2026-05-16T00:00']
        );
        $page->click('Salvar');
        $page->waitForText('Gado salvo com sucesso!', 5);
        $page->see('Gado salvo com sucesso!');

        $page->click('a[href^="/cattle/edit/"]');
        $page->waitForElementVisible('#breed', 5);
        $page->see('Editar Gado', '//h1');
        $page->fillField('cattle[breed]', 'nelore-03-updated');
        $page->fillField('cattle[purchase_value_in_cents]', '2000');
        $page->executeJS(
            "const input = document.getElementById('purchase_date');\n" .
            "if (input) {\n" .
            "  input.value = arguments[0];\n" .
            "  input.dispatchEvent(new Event('input', { bubbles: true }));\n" .
            "  input.dispatchEvent(new Event('change', { bubbles: true }));\n" .
            "}",
            ['2026-05-17']
        );
        $page->click('Atualizar');

        $page->waitForText('Gado atualizado com sucesso!', 5);
        $page->see('Gado atualizado com sucesso!');
        $page->seeInField('cattle[breed]', 'nelore-03-updated');
    }

    public function listAllRecords(AcceptanceTester $page): void
    {
        $page->login(self::USER_EMAIL, self::USER_PASSWORD);
        $page->amOnPage('/cattle');
        $page->see('Listagem de vacas', '//h1');

        $page->click('Adicionar vacas');
        $page->waitForElementVisible('#breed', 5);
        $page->fillField('cattle[breed]', 'nelore-04');
        $page->fillField('cattle[purchase_value_in_cents]', '1100');
        $page->executeJS(
            "const input = document.getElementById('purchase_date');\n" .
            "if (input) {\n" .
            "  input.value = arguments[0];\n" .
            "  input.dispatchEvent(new Event('input', { bubbles: true }));\n" .
            "  input.dispatchEvent(new Event('change', { bubbles: true }));\n" .
            "}",
            ['2026-05-16T00:00']
        );
        $page->click('Salvar');
        $page->waitForText('Gado salvo com sucesso!', 5);
        $page->see('Gado salvo com sucesso!');

        $page->click('Adicionar vacas');
        $page->waitForElementVisible('#breed', 5);
        $page->fillField('cattle[breed]', 'nelore-05');
        $page->fillField('cattle[purchase_value_in_cents]', '1150');
        $page->executeJS(
            "const input = document.getElementById('purchase_date');\n" .
            "if (input) {\n" .
            "  input.value = arguments[0];\n" .
            "  input.dispatchEvent(new Event('input', { bubbles: true }));\n" .
            "  input.dispatchEvent(new Event('change', { bubbles: true }));\n" .
            "}",
            ['2026-05-16T00:00']
        );
        $page->click('Salvar');
        $page->waitForText('Gado salvo com sucesso!', 5);
        $page->see('Gado salvo com sucesso!');

        $page->see('nelore-04');
        $page->see('nelore-05');
    }

    public function removeRecord(AcceptanceTester $page): void
    {
        $page->login(self::USER_EMAIL, self::USER_PASSWORD);
        $page->amOnPage('/cattle');
        $page->see('Listagem de vacas', '//h1');

        $page->click('Adicionar vacas');
        $page->waitForElementVisible('#breed', 5);
        $page->fillField('cattle[breed]', 'nelore-06');
        $page->fillField('cattle[purchase_value_in_cents]', '1300');
        $page->executeJS(
            "const input = document.getElementById('purchase_date');\n" .
            "if (input) {\n" .
            "  input.value = arguments[0];\n" .
            "  input.dispatchEvent(new Event('input', { bubbles: true }));\n" .
            "  input.dispatchEvent(new Event('change', { bubbles: true }));\n" .
            "}",
            ['2026-05-16T00:00']
        );
        $page->click('Salvar');
        $page->waitForText('Gado salvo com sucesso!', 5);
        $page->see('Gado salvo com sucesso!');

        $page->click('button[title="Excluir"]');
        $page->seeInPopup('Tem certeza que deseja excluir esta vaca?');
        $page->acceptPopup();

        $page->waitForText('Gado deletado com sucesso!', 5);
        $page->see('Gado deletado com sucesso!');
        $page->see('Nenhuma vaca encontrada.');
    }
}
