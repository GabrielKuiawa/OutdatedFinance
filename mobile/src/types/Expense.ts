export type Expense = {
  id?: number;
  title: string;
  amount: string;
  description: string;
  expense_date: string;
  status: "pago" | "pendente" | "atrasado";
  payment: "pix" | "cartao" | "dinheiro" | null;
  created_at: string;
  register_by_user_id: number;
  group_id?: number | null;
  register_payment_user_id?: number | null;
};

export type ExpenseResponse = {
  results: Expense[];
};