// app/_layout.tsx
import React from 'react';
import { Stack } from 'expo-router';

import { GluestackUIProvider } from '@/components/ui/gluestack-ui-provider';
import '@/global.css';

export default function Layout() {
  return (
    
    <GluestackUIProvider mode="dark">
      <Stack>
      <Stack.Screen name="index" options={{ headerShown: false }} />
      <Stack.Screen name="home" options={{ title: 'Tela Inicial usuário' }} />
      <Stack.Screen name="homeAdmin" options={{ title: 'Tela Inicial Administrador' }} />
    </Stack>
    </GluestackUIProvider>
  
  );
}
