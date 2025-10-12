

const BASE_URL: string = 'http://localhost:8000'; 

export interface LoginData {
  token?: string; 
  user?: {
    id: number;
    email: string;
    
  };
  message?: string; 
}


export interface LoginResponse {
  success: boolean;
  status: number;
  data: LoginData;
  error?: Error | unknown; 
}


const performLogin = async (endpoint: string, email: string, password: string): Promise<LoginResponse> => {
  try {
    const response: Response = await fetch(`${BASE_URL}${endpoint}`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ email, password }),
    });

    const data: LoginData = await response.json();

    return {
      success: response.ok,
      status: response.status,
      data: data,
    };
  } catch (error) {
   
    console.error('Erro na requisição da API:', error);
    
    return {
      success: false,
      status: 500,
      data: { message: 'Não foi possível conectar ao servidor.' },
      error: error,
    };
  }
};


export const loginUser = (email: string, password: string): Promise<LoginResponse> => {
  return performLogin('/api/users', email, password);
};


export const loginAdmin = (email: string, password: string): Promise<LoginResponse> => {
  return performLogin('/api/admin', email, password);
};