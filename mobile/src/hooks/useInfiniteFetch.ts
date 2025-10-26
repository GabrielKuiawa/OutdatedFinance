import { useEffect, useState } from "react";
import useFetch from "./useFetch";
import { PaginatedResponse } from "../types/PaginatedResponse";


export function useInfiniteFetch<T>(initialUrl: string, token: string) {
  const [url, setUrl] = useState(initialUrl);
  const [items, setItems] = useState<T[]>([]);
  const { data, loading, error } = useFetch<PaginatedResponse<T>>(url,token);

  useEffect(() => {
    setUrl(initialUrl);
    setItems([]);
  }, [initialUrl]);

  useEffect(() => {
    if (!data?.results) return;

    const isFirstPage = !data.previous;

    setItems((prev) => {
      if (isFirstPage) return data.results;

      const existingIds = new Set(prev.map((item: any) => item.id));
      const newItems = data.results.filter(
        (item: any) => !existingIds.has(item.id)
      );

      return [...prev, ...newItems];
    });
  }, [data]);

  const loadMore = () => {
    if (!loading && data?.next) {
      setUrl(data.next);
    }
  };

  return { data: items, loading, error, loadMore };
}