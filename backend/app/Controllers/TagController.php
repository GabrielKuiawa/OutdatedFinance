<?php

namespace App\Controllers;

use App\Services\TagService;
use Core\Http\Controllers\Controller;
use Core\Http\Request;

class TagController extends Controller
{
  
    public function index(): void
    {
        $tags = $this->currentUser()->tags()->get();
        $this->renderJson("tags/index", compact('tags'));
    }

    public function show(Request $request): void
    {
       
        $tags = $this->currentUser()->tags()->findById($request->getParam('id'));

        if (!$tags) {
            $this->renderJson(['error' => 'Tag não encontrada']);
            return;
        }
        $this->renderJson(['tags' => $tags->toArray()]);


    }

    public function create(Request $request): void
    {
        $params = $request->getParams();
        $tags = $this->currentUser()->tags()->new($params);
       
        //está registrando a tag vazia


        if (!$tags) {
            $this->renderJson(['error' => 'Erro ao criar a tag']);
            return;
        }
        $tags->save();

        $this->renderJson(['message' => 'Tag criada com sucesso!', 'tags' => $tags]);
    }

    public function update(Request $request): void
    {
        $id = $request->getParam('id');
        $params = $request->getParams();


        $tags = $this->currentUser()->tags()->findById($id);
        $tags->name = $params['name'];

        if (!$tags) {
            $this->renderJson(['error' => 'Erro ao atualizar a tag']);
            return;
        }

        $this->renderJson(['message' => 'Tag atualizada com sucesso!', 'tag' => $tags]);

    }

    public function destroy(Request $request): void
    {
        $tags = $this->currentUser()->tags()->findById($request->getParam('id'));
        $tags->destroy();

        if (!$tags) {
            $this->renderJson(['error' => 'Erro ao excluir a tag']);
            return;
        }

        $this->renderJson(['message' => 'Tag excluída com sucesso!']);
    }
}
