

const BASE_URL: string = 'http://192.168.0.100'; 

export interface LoginData {
  token?: string; 
  role?: 'admin' | 'user'; 
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
     data: {
        token: data.token,
        role: data.role,
        message: data.message
      },
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


export const login = (email: string, password: string): Promise<LoginResponse> => {
  return performLogin('/api/login', email, password);
};


