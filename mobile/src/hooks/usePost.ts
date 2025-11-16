import axios from "axios";

export async function postApi<T>(
  url: string,
  body: any,
  token?: string
): Promise<{ data: T | null; error: Error | null }> {
  try {
    const isFormData = body instanceof FormData;

    console.log(body);
    
    const response = await axios.post(url, body, {
      headers: {
        ...(isFormData
          ? { "Content-Type": "multipart/form-data" }
          : { "Content-Type": "application/json" }),
        ...(token ? { Authorization: `Bearer ${token}` } : {}),
      },
    });

    return { data: response.data, error: null };
  } catch (err) {
    return { data: null, error: err as Error };
  }
}

export async function useDelete<T>(
  url: string,
  token?: string
): Promise<{ data: T | null; error: Error | null }> {
  try {
    const response = await axios.delete(url, {
      headers: {
        "Content-Type": "application/json",
        ...(token ? { Authorization: `Bearer ${token}` } : {}),
      },
    });
    return { data: response.data, error: null };
  } catch (err) {
    return { data: null, error: err as Error };
  }
}