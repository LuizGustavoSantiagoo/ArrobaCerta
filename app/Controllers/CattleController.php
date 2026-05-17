<?php

namespace App\Controllers;

use App\Models\Cattle;
use Core\Http\Controllers\Controller;
use Core\Http\Request;
use Lib\FlashMessage;
use Lib\Paginator;

class CattleController extends Controller
{
    public function index(): void
    {

        $title = "Cattle's";
        $user = $this->current_user->findBy(['id' => $this->current_user->id]);

        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 10;
        $attributes = ['breed', 'purchase_value_in_cents', 'purchase_date', 'purchase_type', 'state'];
        $conditions = [];

        $paginator = new Paginator(
            Cattle::class,
            $page,
            $perPage,
            'cattle',
            $attributes,
            $conditions,
            'cattle.index'
        );

        $this->render('cattle/index', compact('user', 'title', 'paginator'));
    }

    public function store(Request $request): void
    {
        $params = $request->getParam('cattle');
        $params['state'] = 'active';
        $params['registered_by_user_id'] = $this->current_user->id;
        $cattle = new Cattle($params);

        if ($cattle->save()) {
            FlashMessage::success('Gado salvo com sucesso!');
            $this->redirectTo(route('cattle.index'));
        } else {
            FlashMessage::danger('Erro ao salvar o gado. Verifique os dados e tente novamente.');
            $this->redirectTo(route('cattle.index'));
        }
    }

    public function update(Request $request): void
    {
        $params = $request->getParam('cattle');
        $cattle = Cattle::findById($request->getParam('cow_id'));

        if (!$cattle) {
            FlashMessage::danger('Gado não encontrado.');
            $this->redirectTo(route('cattle.index'));
            return;
        }

        $cattle->breed = $params['breed'] ?? null;
        $cattle->purchase_date = $params['purchase_date'] ?? null;
        $cattle->purchase_type = $params['purchase_type'] ?? null;
        $cattle->state = $params['state'] ?? null;

        $cattle->purchase_value_in_cents = isset($params['purchase_value_in_cents']) && $params['purchase_value_in_cents'] !== ''
            ? (string)$cattle->setRealValueInCents($params['purchase_value_in_cents'])
            : null;

        $cattle->sale_value_in_cents = isset($params['sale_value_in_cents']) && $params['sale_value_in_cents'] !== ''
            ? (string)$cattle->setRealValueInCents($params['sale_value_in_cents'])
            : null;

        $cattle->sale_date = $params['sale_date'] ?? null;
        $cattle->death_date = $params['death_date'] ?? null;
        $cattle->death_reason = $params['death_reason'] ?? null;

        if ($cattle->save()) {
            FlashMessage::success('Gado atualizado com sucesso!');
            $this->redirectTo(route('cattle.edit', ['cow_id' => $cattle->id]));
        } else {
            FlashMessage::danger('Erro ao atualizar o gado. Verifique os dados e tente novamente.');
            $this->redirectTo(route('cattle.edit', ['cow_id' => $cattle->id]));
        }
    }

    public function edit(Request $request): void
    {
        $title = "Editar Gado";
        $user = $this->current_user->findBy(['id' => $this->current_user->id]);
        $cattle_data = Cattle::findById($request->getParam('cow_id'));

        if (!$cattle_data) {
            FlashMessage::danger('Gado não encontrado.');
            $this->redirectTo(route('cattle.index'));
            return;
        }

        $this->render('cattle/edit', compact('cattle_data', 'title', 'user'));
    }

    public function destroy(Request $request): void
    {

        $cattle = Cattle::findById((int)$request->getParam('cow_id'));

        if (!$cattle) {
            FlashMessage::danger('Gado não encontrado.');
            $this->redirectTo(route('cattle.index'));
            return;
        }

        if ($cattle->destroy()) {
            FlashMessage::success('Gado deletado com sucesso!');
            $this->redirectBack();
        } else {
            FlashMessage::danger('Erro ao deletar o gado. Tente novamente.');
        }
        $this->redirectBack();

        $this->redirectTo(route('cattle.index'));
    }
}
