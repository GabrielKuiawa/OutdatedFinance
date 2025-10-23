<?php

namespace App\Controllers;

use Core\Http\Request;
use Core\Http\Controllers\Controller;

class ExpensesController extends Controller
{
    public function index(): void
    {
        $expenses = $this->currentUser()->expenses()->get();
        $this->renderJson("expenses/index", compact('expenses'));
    }

    public function show(Request $request): void
    {
        $expense = $this->currentUser()->expenses()->findById($request->getParam('id'));

        if ($expense) {
            $this->renderJson(['expense' => $expense->toArray()]);
        }
    }

    public function create(Request $request): void
    {
        $params = $request->getParams();
        $expense = $this->currentUser()->expenses()->new($params);
        if ($expense->save()) {
            $this->renderJson([
                'message' => 'Despesa criada com sucesso!',
                'expense' => $expense->toArray()
            ]);
        } else {
            $this->renderJson(['error' => 'Erro ao criar despesa']);
        }
    }

    public function update(Request $request): void
    {
        $id = $request->getParam('id');
        $params = $request->getParams();
        /** @var \App\Models\Expense $expense */
        $expense = $this->currentUser()->expenses()->findById($id);

        $expense->title = $params['title'];
        $expense->description = $params['description'];
        $expense->amount = $params['amount'];
        $expense->expense_date = $params['expense_date'];
        $expense->group_id = $params['group_id'];
        $expense->register_payment_user_id = $params['register_payment_user_id'];
        $expense->status = $params['status'];
        $expense->payment = $params['payment'];

        if ($expense->save()) {
            $this->renderJson([
                'message' => 'Despesa atualizada com sucesso!',
                'expense' => $expense->toArray()
            ]);
        } else {
            $this->renderJson(['error' => 'Erro ao atualizar despesa']);
        }
    }

    public function destroy(Request $request): void
    {
        $expense = $this->currentUser()->expenses()->findById($request->getParam('id'));
        $expense->destroy();

        $this->renderJson(['message' => 'Despesa excluída com sucesso!']);
    }
}
