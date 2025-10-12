// src/services/authService.ts

const BASE_URL: string = 'http://localhost:8000'; // Tipagem para a URL

// --- Interfaces (Tipagem de Dados) ---

/**
 * Define a estrutura de uma resposta de login bem-sucedida ou de erro
 * que a API retorna (o payload do JSON).
 * Ajuste esta interface para refletir o JSON real da sua API.
 */
export interface LoginData {
  token?: string; // Token de autenticação (se for bem-sucedido)
  user?: {
    id: number;
    email: string;
    // Adicione outros campos do usuário aqui
  };
  message?: string; // Mensagem de erro (se houver)
}

/**
 * Define a estrutura padronizada de retorno das funções de serviço.
 */
export interface LoginResponse {
  success: boolean;
  status: number;
  data: LoginData;
  error?: Error | unknown; // Opcional, para erros de rede
}

// --- Funções de Serviço ---

/**
 * Função genérica para realizar a requisição de login.
 * @param endpoint O caminho da API (ex: '/api/login/user').
 * @param email O e-mail do usuário.
 * @param password A senha do usuário.
 * @returns Uma Promise que resolve para um objeto LoginResponse.
 */
const performLogin = async (endpoint: string, email: string, password: string): Promise<LoginResponse> => {
  try {
    const response: Response = await fetch(`${BASE_URL}${endpoint}`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ email, password }),
    });

    // Tipamos o JSON de resposta
    const data: LoginData = await response.json();

    return {
      success: response.ok,
      status: response.status,
      data: data,
    };
  } catch (error) {
    // Tratamento de erro de rede ou parse de JSON
    console.error('Erro na requisição da API:', error);
    
    // Retorna um objeto de erro padronizado para o componente
    return {
      success: false,
      status: 500, // Status HTTP genérico para erro interno/de rede
      data: { message: 'Não foi possível conectar ao servidor.' },
      error: error,
    };
  }
};

/**
 * Tenta logar como usuário padrão.
 * Rota: /api/login/user
 */
export const loginUser = (email: string, password: string): Promise<LoginResponse> => {
  return performLogin('/api/users', email, password);
};

/**
 * Tenta logar como usuário administrador.
 * Rota: /api/login/admin
 */
export const loginAdmin = (email: string, password: string): Promise<LoginResponse> => {
  return performLogin('/api/admin', email, password);
};