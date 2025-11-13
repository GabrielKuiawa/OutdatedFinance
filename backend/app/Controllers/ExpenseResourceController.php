<?php

namespace App\Controllers;

use Core\Http\Controllers\Controller;
use Core\Http\Request;

class ExpenseResourceController extends Controller
{
    public function index(Request $request): void
    {
        /** @var \App\Models\Expense $expenses */
        $expenses = $this->currentUser()->expenses()->findById($request->getParam('id'));
        $resources = $expenses->resource()->get();
        $this->renderJson("expenses/files/index", compact('resources'));
    }

    public function create(Request $request): void
    {
        $files = $request->getParam('files');
        /** @var \App\Models\Expense $expenses */
        $expenses = $this->currentUser()->expenses()->findById($request->getParam('id'));
        /** @var \App\Models\Resource $resource */
        $resource = $expenses->resource()->new();
        $normalizedFiles =  $resource->resourceFiles()->formatedArrayFile($files);
        foreach ($normalizedFiles as $file) {
            /** @var \App\Models\Resource $resourceInstance */
            $resourceInstance = $expenses->resource()->new();
            $resourceInstance->resourceFiles()->upload($file);
        }
        $this->renderJson([
            'message' => 'Files uploaded successfully',
            'images' => $normalizedFiles
        ]);
    }

    public function destroy(Request $request): void
    {
        $fileId = $request->getParam('file_id');

        $expenseId = $request->getParam('id');
        /** @var \App\Models\Expense $expenses */
        $expenses = $this->currentUser()->expenses()->findById($expenseId);
        /** @var \App\Models\Resource $resource */
        $resource = $expenses->resource()->findById($fileId);

        if (!$resource) {
            $this->renderJson(['error' => 'Resource not found']);
            return;
        }

        if ($resource->resourceFiles()->deleteImage()) {
            $this->renderJson(['message' => 'File deleted successfully']);
        }

        $this->renderJson(['error' => 'Error deleting file']);
    }
}
