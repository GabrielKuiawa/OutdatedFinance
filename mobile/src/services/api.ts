import { API_BASE_URL, Token } from "@/env";
import { Expense, ExpenseDetail, ExpenseResponse } from "../types/Expense";
import { postApi } from "../hooks/usePost";
import useFetch from "../hooks/useFetch";
import { useInfiniteFetch } from "../hooks/useInfiniteFetch";
import { useUpdate } from "../hooks/useUpdate";
import { getData } from "./storage";
import AsyncStorage from "@react-native-async-storage/async-storage";
const getToken = async () => {
  const token = await getData("userToken");
  return token;
};

export const ApiService = {
  getExpensesApi() {
    const { data, error } = useFetch<ExpenseResponse>(
      `${API_BASE_URL}/expenses`,
      Token
    );
    return data;
  },
  getExpenseByIdApi(id: number, editMode = false) {
    if (editMode)
      return useFetch<ExpenseDetail>(`${API_BASE_URL}/expenses/${id}`, Token);
    return;
  },
  getExpensesInfiniteApi() {    
    return useInfiniteFetch<Expense>(`${API_BASE_URL}/expenses/page/1`,Token);
  },
};
export async function loginApi(email: string, password: string) {
  const response = await fetch(API_BASE_URL + "/login", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ email, password }),
  });
  return response.json();
}

export async function saveExpenseApi(expense: Expense) {
  const { data, error } = await postApi<Expense>(
    `${API_BASE_URL}/expenses`,
    expense,
    Token
  );
  return { data, error };
}
export async function updateExpenseApi(id: number, expense: Expense) {
  const { data, error } = await useUpdate<Expense>(
    `${API_BASE_URL}/expenses/${id}`,
    expense,
    Token
  );
  return { data, error };
}
export async function deleteExpenseApi(id: number) {
  const response = await fetch(`${API_BASE_URL}/expenses/${id}`, {
    method: "DELETE",
    headers: {
      "Content-Type": "application/json",
      Authorization: `Bearer ${Token}`,
    },
  });
  return response.json();
}
