<?php

namespace App\Controllers;

use Core\Http\Request;
use App\Models\Cattle;
use Lib\FlashMessage;

class CattleVaccinesController
{
    public function store(Request $request): void
    {
        $cattleId = $request->getParam('cattle_id');
        $vaccineId = $request->getParam('vaccine_id');
        $applicationDate = $request->getParam('application_date');

        $cattle = Cattle::findById($cattleId);

        if (!$cattle) {
            FlashMessage::danger('Animal não encontrado.');
            $this->redirectBack();
            return;
        }

        if (empty($vaccineId) || empty($applicationDate)) {
            FlashMessage::danger('Selecione uma vacina e informe a data de aplicação.');
            $this->redirectBack();
            return;
        }

        $success = $cattle->vaccines()->attach($vaccineId, [
            'application_date' => $applicationDate
        ]);

        if ($success) {
            FlashMessage::success('Vacina registrada no histórico do animal!');
        } else {
            FlashMessage::danger('Erro ao registrar a vacina.');
        }

        $this->redirectBack();
    }

    public function destroy(Request $request): void
    {
        $cattleId = $request->getParam('cattle_id');
        $vaccineId = $request->getParam('vaccine_id');

        $cattle = Cattle::findById($cattleId);

        if ($cattle && $vaccineId) {
            $cattle->vaccines()->detach($vaccineId);
            FlashMessage::success('Registro de vacina removido com sucesso.');
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
