<?php

namespace App\Controllers;

use App\Models\Expense;
use App\Models\Resource;
use App\Services\FileService;
use Core\Http\Request;
use Core\Http\Response;

class ExpenseResourceController
{
    // método pra fazer upload de arquivo e associar à despesa
    public function create(Request $request, int $expenseId): Response
    {
        // procura a despesa pelo id
        $expense = Expense::find($expenseId);

        // se não achar, retorna erro
        if (!$expense) {
            return Response::json(['error' => 'Expense not found'], 404);
        }

        // pega o arquivo enviado no request
        $file = $request->file('file');

        // se nenhum arquivo foi enviado, retorna erro
        if (!$file) {
            return Response::json(['error' => 'No file uploaded'], 400);
        }

        // cria um registro em branco no banco pro resource
        $resource = Resource::create([
            'expenses_id' => $expense->id,
            'file_path' => '' // será atualizado depois com o nome real
        ]);

        // usa o serviço pra salvar o arquivo no disco e atualizar o caminho
        $fileService = new FileService($resource);
        $fileService->update($file);

        // retorna sucesso e os dados do resource criado
        return Response::json([
            'message' => 'Resource uploaded successfully',
            'resource' => $resource
        ]);
    }

    // método pra deletar um arquivo (resource)
    public function destroy(int $id): Response
    {
        // busca o resource no banco
        $resource = Resource::find($id);

        // se não existir, retorna erro
        if (!$resource) {
            return Response::json(['error' => 'Resource not found'], 404);
        }

        // usa o serviço pra remover o arquivo físico
        $fileService = new FileService($resource);
        $fileService->removeOldFile();

        // apaga o registro do banco
        $resource->delete();

        // retorna mensagem de sucesso
        return Response::json(['message' => 'Resource deleted successfully']);
    }
}
