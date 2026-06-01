<?php

namespace Tests\Acceptance\cattle;

use App\Models\Cattle;
use App\Models\CattleImages;
use Tests\Acceptance\BaseAcceptanceCest;
use Tests\Support\AcceptanceTester;

class CattlePhotosCest extends BaseAcceptanceCest
{
    private const USER_EMAIL = 'test@example.com';
    private const USER_PASSWORD = 'password';

    public function uploadImage(AcceptanceTester $page): void
    {
        $cattle = $this->createCattle();

        $page->login(self::USER_EMAIL, self::USER_PASSWORD);
        $page->amOnPage("/cattle/{$cattle->id}/edit");
        $page->click('Fotos');

        $page->attachFile('input[name=foto]', 'foto-test.png');
        $page->selectOption('#type', 'cattle');
        $page->click('Enviar');

        $page->click('Fotos');
        $page->seeNumberOfElements('#photos-gallery select', 1);
    }

    public function removeImage(AcceptanceTester $page): void
    {
        $cattle = $this->createCattle();
        $this->createPhoto($cattle->id, 'foto1.png');
        $this->createPhoto($cattle->id, 'foto2.png');

        $page->login(self::USER_EMAIL, self::USER_PASSWORD);
        $page->amOnPage("/cattle/{$cattle->id}/edit");
        $page->click('Fotos');

        $page->seeNumberOfElements('#photos-gallery select', 2);

        $page->click('#photos-gallery button.bg-red-500');
        $page->acceptPopup();
        $page->waitForText('Foto removida com sucesso!', 5);

        $page->click('Fotos');
        $page->seeNumberOfElements('#photos-gallery select', 1);
    }

    public function editImageType(AcceptanceTester $page): void
    {
        $cattle = $this->createCattle();
        $this->createPhoto($cattle->id, 'foto1.png');

        $page->login(self::USER_EMAIL, self::USER_PASSWORD);
        $page->amOnPage("/cattle/{$cattle->id}/edit");
        $page->click('Fotos');

        $page->seeOptionIsSelected('#photos-gallery select', 'foto da vaca');

        $page->selectOption('#photos-gallery select', 'foto do obito');
        $page->click('#photos-gallery button.bg-green-500');
        $page->waitForText('Tipo atualizado com sucesso!', 30);

        $page->click('Fotos');
        $page->seeOptionIsSelected('#photos-gallery select', 'foto do obito');
    }

    public function viewImage(AcceptanceTester $page): void
    {
        $cattle = $this->createCattle();
        $this->createPhoto($cattle->id, 'foto1.png');

        $page->login(self::USER_EMAIL, self::USER_PASSWORD);
        $page->amOnPage("/cattle/{$cattle->id}/edit");
        $page->click('Fotos');

        $page->seeElement('#photos-gallery img');
    }

    private function createCattle(): Cattle
    {
        $cattle = new Cattle([
            'breed' => 'Nelore-foto',
            'purchase_value_in_cents' => 100000,
            'purchase_date' => '2026-01-10',
            'state' => 'active',
            'registered_by_user_id' => 1,
        ]);
        $cattle->save();

        return $cattle;
    }

    private function createPhoto(int $cattleId, string $fileName): void
    {
        $photo = new CattleImages([
            'file_name' => $fileName,
            'file_path' => "/assets/uploads/cattle/{$cattleId}/{$fileName}",
            'type' => 'cattle',
            'cattle_id' => $cattleId,
            'registered_by_id' => 1,
        ]);
        $photo->save();
    }
}
