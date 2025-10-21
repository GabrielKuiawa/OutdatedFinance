<?php

namespace App\Controllers;

use App\Models\Expense;
use Core\Http\Request;
use Core\Http\Controllers\Controller;

class ExpensesController extends Controller
{
    public function index()
    {
        $expenses = Expense::All();
        $this->renderJson("expenses/index", compact('expenses'));
    }

    public function show(Request $request)
    {
        $id = $request->getParam('id');

        if (!$id) {
            return $this->renderJson(['error' => 'ID inválido'], ['status' => 400]);
        }

        $expense = Expense::findById($id);

        if ($expense) {
            return $this->renderJson(['expense' => [
                'id' => $expense->id,
                'title' => $expense->title,
                'description' => $expense->description,
                'amount' => $expense->amount,
                'expense_date' => $expense->expense_date,
                'register_by_user_id' => $expense->register_by_user_id,
                'group_id' => $expense->group_id,
                'register_payment_user_id' => $expense->register_payment_user_id,
                'status' => $expense->status,
                'payment' => $expense->payment,
                'created_at' => $expense->created_at,
            ]]);
        }

        return $this->renderJson(['error' => 'Despesa não encontrada'], ['status' => 404]);
    }

    public function store(Request $request)
    {
        $data = $request->getParams();

        if (empty($data)) {
            return $this->renderJson(['error' => 'Dados não recebidos'], ['status' => 400]);
        }

        $expense = new Expense([
            'title' => $data['title'],
            'description' => $data['description'],
            'amount' => $data['amount'],
            'expense_date' => $data['expense_date'],
            'register_by_user_id' => $data['register_by_user_id'],
            'group_id' => $data['group_id'],
            'created_at' => date('Y-m-d H:i:s'),
            'register_payment_user_id' => $data['register_payment_user_id'],
            'status' => $data['status'],
            'payment' => $data['payment']
        ]);

        $expense->save();

        return $this->renderJson([
            'message' => 'Despesa criada com sucesso!',
            'expense' => $expense
        ], ['status' => 201]);
    }

    public function update(Request $request)
    {
        $id = $request->getParam('id');
        $data = $request->getParams();

        if (!$id) {
            return $this->renderJson(['error' => 'ID inválido'], ['status' => 400]);
        }

        $expense = Expense::findById($id);
        if (!$expense) {
            return $this->renderJson(['error' => 'Despesa não encontrada'], ['status' => 404]);
        }

        $expense->update([
            'title' => $data['title'],
            'description' => $data['description'],
            'amount' => $data['amount'],
            'expense_date' => $data['expense_date'],
            'register_by_user_id' => $data['register_by_user_id'],
            'group_id' => $data['group_id'],
            'register_payment_user_id' => $data['register_payment_user_id'],
            'status' => $data['status'],
            'payment' => $data['payment']
        ]);

        return $this->renderJson([
            'message' => 'Despesa atualizada com sucesso!',
            'expense' => $expense
        ]);
    }

    public function destroy(Request $request)
    {
        $id = $request->getParam('id');

        if (!$id) {
            return $this->renderJson(['error' => 'ID inválido'], ['status' => 400]);
        }

        $expense = Expense::findById($id);
        if (!$expense) {
            return $this->renderJson(['error' => 'Despesa não encontrada'], ['status' => 404]);
        }

        $deleted = $expense->destroy();

        if (!$deleted) {
            return $this->renderJson(['error' => 'Erro ao excluir despesa'], ['status' => 500]);
        }

        return $this->renderJson(['message' => 'Despesa excluída com sucesso!']);
    }
}
