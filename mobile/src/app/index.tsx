import React, { useState } from "react";
import {
  View,
  Text,
  TextInput,
  Button,
  Alert,
  ActivityIndicator,
  StyleSheet,
} from "react-native";
import { useRouter } from "expo-router";
import { login } from "../services/authService";

export default function LoginScreen() {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [isLoading, setIsLoading] = useState(false);
  const router = useRouter();

  const handleLogin = async () => {
    if (!email || !password) {
      Alert.alert("Erro", "Preencha todos os campos.");
      return;
    }
    await loginRequest();
  };

  const loginRequest = async () => {
    setIsLoading(true);
    try {
      let result = await login(email, password);

      if (result.success) {
        const role = result.data.role;

        if (role === "admin") {
          Alert.alert(
            "Sucesso",
            "Login de Administrador efetuado com sucesso!"
          );
          router.push("/homeAdmin");
        } else if (role === "user") {
          Alert.alert("Sucesso", "Login de Usuário efetuado com sucesso!");
          router.push("/home");
        } else {
          Alert.alert("Erro", "Usuário inválido.");
        }
        return;
      }

      Alert.alert("Erro de Login", "E-mail ou senha inválida.");
    } catch (error) {
      console.error(error);
      Alert.alert(
        "Erro de Conexão",
        "Não foi possível completar a requisição."
      );
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
        title={isLoading ? "Carregando..." : "Entrar"}
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
  container: { flex: 1, justifyContent: "center", padding: 20 },
  title: {
    fontSize: 24,
    fontWeight: "bold",
    marginBottom: 30,
    textAlign: "center",
  },
  input: {
    height: 50,
    borderColor: "#ccc",
    borderWidth: 1,
    marginBottom: 15,
    paddingHorizontal: 15,
    borderRadius: 8,
  },
  loading: { marginTop: 10 },
});
