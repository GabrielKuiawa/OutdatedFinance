export async function loginApi(email: string, password: string) {
  const response = await fetch('http://192.168.3.103/api/login', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ email, password }),
  });
  return response.json();
}