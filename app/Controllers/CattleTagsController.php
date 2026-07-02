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

        $cattle = Cattle::findById($cattleId);

        if (!$cattle) {
            FlashMessage::danger('Animal não encontrado.');
            $this->redirectBack();
            return;
        }

        if (empty($tagId)) {
            FlashMessage::danger('Selecione uma TAG.');
            $this->redirectBack();
            return;
        }

        $success = $cattle->tags()->attach($tagId);

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
            $cattle->vaccines()->detach($tagId);
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
