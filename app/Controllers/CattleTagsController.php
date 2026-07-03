<?php

namespace App\Controllers;

use Core\Http\Request;
use App\Models\Cattle;
use Lib\FlashMessage;

class CattleTagsController
{
    public function store(Request $request): void
    {
        $cattleId = $request->getParam('cattle_id');
        $tagId = $request->getParam('tag_id');
        $applicationDate = $request->getParam('application_date');

        $cattle = Cattle::findById($cattleId);

        if (!$cattle) {
            FlashMessage::danger('Animal não encontrado.');
            $this->redirectBack();
            return;
        }

        if (empty($tagId) || empty($applicationDate)) {
            FlashMessage::danger('Selecione uma tag e informe a data de aplicação.');
            $this->redirectBack();
            return;
        }

        $pdo = \Core\Database\Database::getDatabaseConn();
        $stmt = $pdo->prepare("SELECT id FROM cattle_tags WHERE cattle_id = ? AND tag_id = ?");
        $stmt->execute([$cattleId, $tagId]);
    
        if ($stmt->fetch()) {
            FlashMessage::danger('Esta tag já está associada a este animal.');
            $this->redirectBack();
            return; 
        }

        $success = $cattle->tags()->attach($tagId, [
            'application_date' => $applicationDate
        ]);

        if ($success) {
            FlashMessage::success('Tag registrada no histórico do animal!');
        } else {
            FlashMessage::danger('Erro ao registrar a Tag.');
        }

        $this->redirectBack();
    }

    public function destroy(Request $request): void
    {
        $cattleId = $request->getParam('cattle_id');
        $tagId = $request->getParam('tag_id');

        $cattle = Cattle::findById($cattleId);

        if ($cattle && $tagId) {
            $cattle->tags()->detach($tagId);
            FlashMessage::success('Registro de tag removido com sucesso.');
        }

        $this->redirectBack();
    }

    private function redirectBack(): void
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        header("Location: $referer");
        exit;
    }
}
