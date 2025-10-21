<?php

namespace App\Controllers;

use App\Models\Expense;
use Core\Http\Request;
use Core\Http\Controllers\Controller;
// use App\Enums\ExpenseStatus;
// use App\Enums\ExpensePayment;

class ExpensesController extends Controller
{
    // 🔹 GET /expenses → lista todas as despesas
    public function index()
    {
            $expenses = Expense::getAll();
    $this->renderJson("expenses/index", compact('expenses'));
    }

    // 🔹 GET /expenses/{id} → mostra uma despesa específica
    public function show(Request $request)
    {
        $uri = $request->getUri();
        $parts = explode('/', trim($uri, '/'));
        $id = (int) end($parts);

        if (!$id) {
            return $this->renderJson(['error' => 'ID inválido'], ['status' => 400]);
        }

        $expense = Expense::findById($id);

        if (!$expense) {
            return $this->renderJson(['error' => 'Despesa não encontrada'],['status' => 404]);
        }

        return $this->renderJson(['expense' => $expense]);
    }

    // 🔹 POST /expenses → cria nova despesa
    public function store(Request $request)
    {
        $data = $request->getParams();

        if (empty($data)) {
            return $this->renderJson(['error' => 'Dados não recebidos'],['status' => 400]);
        }

        $expense = new Expense(
            null,
            $data['title'] ?? '',
            $data['description'] ?? '',
            (float)($data['amount'] ?? 0),
            $data['expense_date'] ?? date('Y-m-d'),
            $data['register_by_user_id'] ?? null,
            $data['group_user_id'] ?? null,
            $data['register_payment_user_id'] ?? null,
            $data['status'] ?? 'pendente',
            $data['payment'] ?? null
        );

        $expense->create();

        return $this->renderJson([
            'message' => 'Despesa criada com sucesso!',
            'expense' => $expense
        ],['status' => 201]);
    }

    // 🔹 PUT /expenses/{id} → atualiza despesa existente
    public function update(Request $request)
    {
        $uri = $request->getUri();
        $parts = explode('/', trim($uri, '/'));
        $id = (int) end($parts);

        if (!$id) {
            return $this->renderJson(['error' => 'ID inválido'], ['status' => 400]);
        }

        $expense = Expense::findById($id);
        if (!$expense) {
            return $this->renderJson(['error' => 'Despesa não encontrada'], ['status' => 404]);
        }

        $data = $request->getParams();


        $expense->title = $data['title'] ?? $expense->title;
        $expense->description = $data['description'] ?? $expense->description;
        $expense->amount = (float)($data['amount'] ?? $expense->amount);
        $expense->expense_date = $data['expense_date'] ?? $expense->expense_date;
        $expense->status = isset($data['status']) 
        ? ExpenseStatus::from($data['status']) 
        : $expense->status;

        $expense->payment = isset($data['payment']) 
        ? ExpensePayment::from($data['payment']) 
        : $expense->payment;

        $expense->update();

        return $this->renderJson([
            'message' => 'Despesa atualizada com sucesso!',
            'expense' => $expense
        ]);
    }

    // 🔹 DELETE /expenses/{id} → exclui uma despesa
    public function destroy(Request $request)
    {
        $uri = $request->getUri();
        $parts = explode('/', trim($uri, '/'));
        $id = (int) end($parts);

        if (!$id) {
            return $this->renderJson(['error' => 'ID inválido'], ['status' => 400]);
        }

        $expense = Expense::findById($id);
        if (!$expense) {
            return $this->renderJson(['error' => 'Despesa não encontrada'], ['status' => 404]);
        }

        $deleted = $expense->destroy();

        if (!$deleted) {
            return $this->renderJson(['error' => 'Erro ao excluir despesa'],['status' => 500]);
        }

        return $this->renderJson(['message' => 'Despesa excluída com sucesso!']);
    }
}
