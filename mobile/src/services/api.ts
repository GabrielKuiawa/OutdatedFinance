import { API_BASE_URL } from "@/env";
import { Expense } from "../types/Expense";
import { postApi } from "../hooks/usePost";

export async function loginApi(email: string, password: string) {
  const response = await fetch(API_BASE_URL + "/login", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ email, password }),
  });
  return response.json();
}

let token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyIjp7ImlkIjoyLCJlbWFpbCI6InVzZXIxQGVtYWlsIiwicm9sZSI6InVzZXIifSwiZXhwIjoxNzYxNDk4NDkzfQ.';

export async function saveExpenseApi(expense: Expense) {
  const { data, error } = await postApi<Expense>(
    `${API_BASE_URL}/expenses`,
    expense,
    token
  );    
  return { data, error };
}
