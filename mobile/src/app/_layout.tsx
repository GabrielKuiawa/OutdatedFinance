import React, { useEffect } from "react";
import { Stack } from "expo-router";

import { GluestackUIProvider } from "@/components/ui/gluestack-ui-provider";
import "@/global.css";
import { loadToken } from "../services/storage";

export default function Layout() {
  useEffect(() => {
    loadToken();
  }, []);
  return (
    <GluestackUIProvider>
      <Stack>
        <Stack.Screen name="index" options={{ headerShown: false }} />
        <Stack.Screen name="home" options={{ title: "Tela Inicial usuário" }} />
        <Stack.Screen
          name="expenses/index"
          options={{ title: "Minhas Despesas" }}
        />
        <Stack.Screen name="expenses/form" options={{ title: "Despesa" }} />
        <Stack.Screen
          name="homeAdmin"
          options={{ title: "Tela Inicial Administrador" }}
        />
      </Stack>
    </GluestackUIProvider>
  );
}
