import { API_BASE_URL, Token } from "@/env";
import { Expense, ExpenseDetail, ExpenseResponse } from "../types/Expense";
import { postApi } from "../hooks/usePost";
import useFetch from "../hooks/useFetch";
import { useInfiniteFetch } from "../hooks/useInfiniteFetch";
import { useUpdate } from "../hooks/useUpdate";
import { getData } from "./storage";
import AsyncStorage from "@react-native-async-storage/async-storage";
import { PickedImage } from "../types/PickedImage";
import { ResultFiles } from "../types/Files";
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
    return useInfiniteFetch<Expense>(`${API_BASE_URL}/expenses/page/1`, Token);
  },
  getExpensesFileApi(id?: number) {
    return useFetch<ResultFiles>(`${API_BASE_URL}/expenses/${id}/files`,Token)
  }
};

export async function loginApi(email: string, password: string) {
  const response = await fetch(API_BASE_URL + "/login", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ email, password }),
  });
  return response.json();
}

export async function saveFormData(id: number, pickedImages: PickedImage[]) {
  const formData = new FormData();

  pickedImages.forEach((img) => {
    formData.append("files[]", {
      uri: img.uri,
      type: img.mimeType,
      name: img.name,
      size: img.size,
    } as any);
  });

  const { data, error } = await postApi<PickedImage>(
    `${API_BASE_URL}/expenses/${id}/files`,
    formData,
    Token
  );
  return { data, error };
}

export async function saveExpenseApi(expense: Expense) {
  const { data, error } = await postApi<ExpenseDetail>(
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
