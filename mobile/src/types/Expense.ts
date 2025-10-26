export type Expense = {
  id?: number;
  title: string;
  amount: string;
  description: string;
  expense_date: string;
  status: "pago" | "pendente" | "atrasado";
  payment: "pix" | "cartao" | "dinheiro";
  created_at: string;
  register_by_user_id: number;
}; 

