import axios from "axios";

export async function useUpdate<T>(
  url: string,
  body: any,
  token?: string
): Promise<{ data: T | null; error: Error | null }> {
  try {
    const response = await axios.put(url, body, {
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
