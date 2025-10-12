import React, { useState } from 'react';
import { View, Text, TextInput, Button, Alert, ActivityIndicator, StyleSheet } from 'react-native';
import { useRouter } from 'expo-router';

const BASE_URL = 'http://10.0.2.2:8000';

export default function LoginScreen() {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [isLoading, setIsLoading] = useState(false);
  const router = useRouter();

  const handleLogin = async () => {
    !email || !password
      ? Alert.alert('Erro', 'Preencha todos os campos.')
      : loginRequest();
  };

  const loginRequest = async () => {
    setIsLoading(true);
    try {
      const response = await fetch(`${BASE_URL}/api/login`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email, password }),
      });

      const data = await response.json();

      response.ok
   //     ? router.push('/home') 
     //   : Alert.alert('Erro de Login', data.message || 'E-mail ou senha inválidos.');
    } catch (error) {
      console.error(error);
      Alert.alert('Erro de Conexão', 'Não foi possível conectar ao servidor.');
    } finally {
      setIsLoading(false);
    }
  };

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Acesso ao Sistema</Text>

      <TextInput
        style={styles.input}
        placeholder="E-mail (ex: user1@email ou teste@email)"
        keyboardType="email-address"
        autoCapitalize="none"
        value={email}
        onChangeText={setEmail}
        editable={!isLoading}
      />

      <TextInput
        style={styles.input}
        placeholder="Senha (ex: 12345)"
        secureTextEntry
        value={password}
        onChangeText={setPassword}
        editable={!isLoading}
      />

      <Button
        title={isLoading ? 'Carregando...' : 'Entrar'}
        onPress={handleLogin}
        disabled={isLoading}
      />

      {isLoading && (
        <View style={styles.loading}>
          <ActivityIndicator size="small" color="#0000ff" />
        </View>
      )}
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, justifyContent: 'center', padding: 20, backgroundColor: '#fff' },
  title: { fontSize: 24, fontWeight: 'bold', marginBottom: 30, textAlign: 'center' },
  input: { height: 50, borderColor: '#ccc', borderWidth: 1, marginBottom: 15, paddingHorizontal: 15, borderRadius: 8 },
  loading: { marginTop: 10 },
});
