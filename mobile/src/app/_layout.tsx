// app/_layout.tsx
import React from 'react';
import { Stack } from 'expo-router';

export default function Layout() {
  return (
    <Stack>
      <Stack.Screen name="index" options={{ headerShown: false }} />
      <Stack.Screen name="home" options={{ title: 'Tela Inicial usuário' }} />
      <Stack.Screen name="homeAdmin" options={{ title: 'Tela Inicial Administrador' }} />
    </Stack>
  );
}
