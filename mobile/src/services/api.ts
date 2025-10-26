import { API_BASE_URL } from "@/env";
import { Expense, ExpenseResponse } from "../types/Expense";
import { postApi } from "../hooks/usePost";
import { use } from "react";
import useFetch from "../hooks/useFetch";

export async function loginApi(email: string, password: string) {
  const response = await fetch(API_BASE_URL + "/login", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ email, password }),
  });
  return response.json();
}

let token =
  "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyIjp7ImlkIjoyLCJlbWFpbCI6InVzZXIxQGVtYWlsIiwicm9sZSI6InVzZXIifSwiZXhwIjoxNzYxNTAzOTc3fQ.";

export async function saveExpenseApi(expense: Expense) {
  const { data, error } = await postApi<Expense>(
    `${API_BASE_URL}/expenses`,
    expense,
    token
  );
  return { data, error };
}

export function getExpensesApi() {
  const { data, error } =  useFetch<ExpenseResponse>(
    `${API_BASE_URL}/expenses`,
    token
  );
  // console.log(data);
  
  return data;
}
