<?php

namespace App\Controllers;

use Core\Http\Controllers\Controller;
use Core\Http\Request;

class GroupController extends Controller {

    public function index(): void {

        $groups = $this->currentUser()->groups()->get();
        $this->renderJson("groups/index", compact('groups'));
    }

    public function show(): void {
        $group = $this->currentUser()->groups()->findById($request->getParam('id'));

        if ($group) {
            $this->renderJson(['groups' => $group->toArray()]);
        }
    }

    public function create(Request $request): void {
        $params = $request->getParams();
        $group = $this->currentUser()->groups()->new($params);
        if ($group->save()) {
            $this->renderJson([
                'message' => 'Grupo criado com sucesso!',
                'groups' => $group->toArray()
            ]);
        } else {
            $this->renderJson(['error' => 'Erro ao criar grupo']);
        }
    }

    public function update(Request $request): void {
        $id = $request->getParam('id');
        $params = $request->getParams();

        $group = $this->currentUser()->groups()->findById($id);
        
        $group->name = $params['name'];
        $group->description = $params['description'];

        if($group->save()) {
            $this->renderJson([
                'message' => 'As informações do grupo foram atualizadas! ',
                'group' => $group->toArray()
            ]);
        }else {
             $this->renderJson(['error' => 'Erro ao atualizar as informações do grupo!']);
        }

    }

    public function destroy(Request $request): void {
        $group = $this->currentUser()->groups()->findById($request->getParam('id'));
        $group->destroy();

        $this->renderJson(['message' => "Grupo excluído com sucesso!"]);
    }
}

