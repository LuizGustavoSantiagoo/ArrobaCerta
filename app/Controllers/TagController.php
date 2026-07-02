<?php

namespace App\Controllers;

use Core\Http\Controllers\Controller;
use App\Models\Cattle;
use Core\Http\Request;
use Lib\FlashMessage;

class TagController extends Controller
{
    protected string $layout = 'authentication_layout';


    public function create(Request $request): void
    {
        $cattleId = $request->getParam('id');
        $tags = $request->getParam('tag')['tags'];
        $date = $request->getParam('tag')['date'];

        $cattle = Cattle::findById($cattleId);

        if (!$cattle) {
            FlashMessage::danger('Animal não encontrado.');
            $this->redirectBack();
            return;
        }

        if (empty($tags)) {
            FlashMessage::danger('Selecione uma tag.');
            $this->redirectBack();
            return;
        }

        for ($i = 0; $i <= sizeof($tags); $i++) {
            $success = $cattle->tags()->attach((int)$tags, [
            'application_date' => $date
        ]);
        }

        if ($success) {
            FlashMessage::success('Vacina registrada no histórico do animal!');
        } else {
            FlashMessage::danger('Erro ao registrar a vacina.');
        }

        $this->redirectTo(route('cattle.index'));
    }
}
