<?php

namespace App\Controllers;

use App\Services\TagService;
use Core\Http\Controllers\Controller;
use Core\Http\Request;

class TagController extends Controller
{
    private TagService $tagService;

    public function __construct()
    {
        $this->tagService = new TagService($this->db());
    }

    public function index(): void
    {
        $userId = $this->currentUser()->id;
        $tags = $this->tagService->allByUser($userId);
        $this->renderJson(['tags' => $tags]);
    }

    public function show(Request $request): void
    {
        $tag = $this->tagService->find($request->getParam('id'));

        if (!$tag) {
            $this->renderJson(['error' => 'Tag não encontrada']);
            return;
        }

        $this->renderJson(['tag' => $tag]);
    }

    public function create(Request $request): void
    {
        $params = $request->getParams();
        $params['user_id'] = $this->currentUser()->id;

        $tag = $this->tagService->create($params);

        if (!$tag) {
            $this->renderJson(['error' => 'Erro ao criar a tag']);
            return;
        }

        $this->renderJson(['message' => 'Tag criada com sucesso!', 'tag' => $tag]);
    }

    public function update(Request $request): void
    {
        $id = $request->getParam('id');
        $params = $request->getParams();

        $tag = $this->tagService->update($id, $params);

        if (!$tag) {
            $this->renderJson(['error' => 'Erro ao atualizar a tag']);
            return;
        }

        $this->renderJson(['message' => 'Tag atualizada com sucesso!', 'tag' => $tag]);
    }

    public function destroy(Request $request): void
    {
        $id = $request->getParam('id');
        $deleted = $this->tagService->delete($id);

        if (!$deleted) {
            $this->renderJson(['error' => 'Erro ao excluir a tag']);
            return;
        }

        $this->renderJson(['message' => 'Tag excluída com sucesso!']);
    }
}
