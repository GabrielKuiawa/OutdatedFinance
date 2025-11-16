import axios from "axios";
import { useEffect, useState } from "react";

export default function useFetch<T>(url?: string, token?:string): {
  data: T | null;
  loading: boolean;
  error: Error | null;
} {
  const [data, setData] = useState<T | null>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<Error | null>(null); 

  console.log(token);
  

  useEffect(() => {
    if (!url) return;
    const fetchData = async () => {
      try {
        const response = await axios.get(url, {
          headers: {
            Authorization: token ? `Bearer ${token}` : '',
          },
        });
        setData(response.data);
      } catch (err) {        
        setError(err as Error);
      } finally {
        setLoading(false);
      }
    };

    fetchData();
  }, [url]);

  return { data, loading, error };
}


