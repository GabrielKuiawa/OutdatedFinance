import React, { useState, useEffect } from 'react';
import { View, Text, TextInput, TouchableOpacity, Alert, StyleSheet } from 'react-native';
import { useLocalSearchParams, useRouter } from 'expo-router';

export default function EditExpense() {
  const router = useRouter();
  const { id } = useLocalSearchParams();

  const [title, setTitle] = useState('');
  const [amount, setAmount] = useState('');
  const [status, setStatus] = useState('pendente');

  useEffect(() => {
    // Mock pra simular carregar os dados pelo is
    const mockData = {
      1: { title: 'Supermercado', amount: '150.75', status: 'pago' },
      2: { title: 'Internet', amount: '99.90', status: 'pendente' },
      3: { title: 'Transporte', amount: '60.00', status: 'atrasado' },
    };

    const expense = mockData[Number(id) as keyof typeof mockData];
    if (expense) {
      setTitle(expense.title);
      setAmount(expense.amount);
      setStatus(expense.status);
    }
  }, [id]);

  const handleSave = () => {
    if (!title || !amount) {
      Alert.alert('Erro', 'Preencha todos os campos obrigatórios.');
      return;
    }

    Alert.alert('Sucesso', 'Despesa atualizada com sucesso!');
    router.back();
  };

  const handleDelete = () => {
    Alert.alert('Confirmação', 'Tem certeza que deseja excluir?', [
      { text: 'Cancelar', style: 'cancel' },
      {
        text: 'Excluir',
        style: 'destructive',
        onPress: () => {
          Alert.alert('Excluída', 'Despesa removida com sucesso!');
          router.back();
        },
      },
    ]);
  };

  return (
    <View style={styles.container}>
      <Text style={styles.header}>Editar Despesa</Text>

      <TextInput
        style={styles.input}
        placeholder="Título"
        value={title}
        onChangeText={setTitle}
      />
      <TextInput
        style={styles.input}
        placeholder="Valor (ex: 120.50)"
        keyboardType="numeric"
        value={amount}
        onChangeText={setAmount}
      />
      <TextInput
        style={styles.input}
        placeholder="Status"
        value={status}
        onChangeText={setStatus}
      />

      <TouchableOpacity style={styles.saveButton} onPress={handleSave}>
        <Text style={styles.saveText}>Salvar Alterações</Text>
      </TouchableOpacity>

      <TouchableOpacity style={styles.deleteButton} onPress={handleDelete}>
        <Text style={styles.deleteText}>Excluir Despesa</Text>
      </TouchableOpacity>
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#fff', padding: 20 },
  header: { fontSize: 22, fontWeight: 'bold', textAlign: 'center', marginBottom: 25, color: '#333' },
  input: {
    backgroundColor: '#f8f9fa',
    borderRadius: 8,
    padding: 12,
    borderWidth: 1,
    borderColor: '#ddd',
    marginBottom: 15,
  },
  saveButton: {
    backgroundColor: '#3B82F6',
    paddingVertical: 14,
    borderRadius: 10,
    marginBottom: 10,
  },
  saveText: { color: '#fff', textAlign: 'center', fontSize: 16, fontWeight: '700' },
  deleteButton: { backgroundColor: '#EF4444', paddingVertical: 14, borderRadius: 10 },
  deleteText: { color: '#fff', textAlign: 'center', fontSize: 16, fontWeight: '700' },
});
