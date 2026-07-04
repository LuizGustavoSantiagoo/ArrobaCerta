<?php

namespace App\Controllers;

use Core\Http\Controllers\Controller;
use Core\Http\Request;
use App\Models\Cattle;
use App\Models\Vaccine;
use Lib\FlashMessage;

class CattleVaccinesController extends Controller
{
    public function index(): void
    {
        $vaccine = Vaccine::all();
        $user = $this->current_user;
        $title = 'vaccines';

        $this->render('vaccines/index', compact('vaccine', 'user', 'title'));
    }

    public function findByName(Request $request): void
    {
        $vaccine = Vaccine::findByName($request->getParam('name'));
        $user = $this->current_user;
        $title = 'vaccines';

        if ($request->acceptJson()) {
            $this->renderJson('vaccines/findByName', compact('vaccine', 'title'));
        } else {
            $this->render('vaccines/index', compact('vaccine', 'user', 'title'));
        }
    }

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
}
