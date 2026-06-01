<?php

namespace App\Controllers;

use App\Models\Cattle;
use App\Models\CattleImages;
use Core\Http\Controllers\Controller;
use Core\Http\Request;
use Lib\FlashMessage;

class CattleImagesController extends Controller
{
    public function create(Request $request): void
    {
        $cattle = Cattle::findById((int) $request->getParam('cattle_id'));

        if (!$cattle) {
            FlashMessage::danger('Vaca não encontrada!');
            $this->redirectTo(route('cattle.index'));
            return;
        }

        $image = $_FILES['foto'] ?? null;
        $type = $request->getParam('type', 'none');

        if (!$image || $image['error'] !== UPLOAD_ERR_OK) {
            FlashMessage::danger('Selecione um arquivo válido para enviar.');
            $this->redirectBack();
            return;
        }

        $service = $cattle->cattleImages();

        if ($service->create($image, $type, $this->currentUser()->id)) {
            FlashMessage::success('Foto enviada com sucesso!');
        } else {
            FlashMessage::danger($service->getError() ?? 'Erro ao salvar imagem!');
        }

        $this->redirectBack();
    }

    public function update(Request $request): void
    {
        $image = CattleImages::findById((int) $request->getParam('id'));

        if (!$image) {
            FlashMessage::danger('Foto não encontrada!');
            $this->redirectBack();
            return;
        }

        if ($image->update(['type' => $request->getParam('type')])) {
            FlashMessage::success('Tipo atualizado com sucesso!');
        } else {
            FlashMessage::danger('Erro ao atualizar o tipo.');
        }

        $this->redirectBack();
    }

    public function destroy(Request $request): void
    {
        $image = CattleImages::findById((int) $request->getParam('id'));

        if (!$image) {
            FlashMessage::danger('Foto não encontrada!');
            $this->redirectBack();
            return;
        }

        $cattle = Cattle::findById($image->cattle_id);

        if ($cattle && $cattle->cattleImages()->destroy($image)) {
            FlashMessage::success('Foto removida com sucesso!');
        } else {
            FlashMessage::danger('Erro ao remover a foto!');
        }

        $this->redirectBack();
    }
}
