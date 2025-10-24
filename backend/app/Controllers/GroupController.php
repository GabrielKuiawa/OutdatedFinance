<?php

namespace App\Controllers;

use Core\Http\Controllers\Controller;
use Core\Http\Request;

class GroupController extends Controller
{
    public function index(): void
    {
        $groups = $this->currentUser()->groups()->get();
        $this->renderJson("groups/index", compact('groups'));
    }

    public function show(Request $request): void
    {
        $group = $this->currentUser()->groups()->findById($request->getParam('id'));

        if (!$group) {
            $this->renderJson(['error' => 'Grupo não encontrado']);
            return;
        }
        $this->renderJson(['groups' => $group->toArray()]);
    }

    public function create(Request $request): void
    {
        $params = $request->getParams();
        $group = $this->currentUser()->groups()->new($params);
        $this->saveAndRespond($group, 'Grupo criado com sucesso!', 'group', 'Erro ao criar o grupo!');
    }

    public function update(Request $request): void
    {
        $id = $request->getParam('id');
        $params = $request->getParams();
        /** @var \App\Models\Group $group */
        $group = $this->currentUser()->groups()->findById($id);
        $group->name = $params['name'];
        $group->description = $params['description'];

        $this->saveAndRespond(
            $group,
            'As informações do grupo foram atualizadas!',
            'group',
            'Erro ao atualizar as informações do grupo!'
        );
    }

    public function destroy(Request $request): void
    {
        $group = $this->currentUser()->groups()->findById($request->getParam('id'));
        $group->destroy();

        $this->renderJson(['message' => "Grupo excluído com sucesso!"]);
    }
}
