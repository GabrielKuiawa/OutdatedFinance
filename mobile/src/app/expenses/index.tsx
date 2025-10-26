import React, { useEffect, useState } from "react";
import {
  View,
  Text,
  FlatList,
  TouchableOpacity,
  StyleSheet,
} from "react-native";
import { useRouter } from "expo-router";
import { Expense } from "@/src/types/Expense";
import { API_BASE_URL, Token } from "@/env";
import { useInfiniteFetch } from "@/src/hooks/useInfiniteFetch";
import { ApiService } from "@/src/services/api";

export default function ExpensesList() {
  const router = useRouter();
  
  const [expenses, setExpenses] = useState<Expense[]>();
  const { data, error, loadMore } = ApiService.getExpensesInfiniteApi();

  useEffect(() => {
    if (data) {
      setExpenses(data);
    }
  }, [data]);
  if (error) {
    return (
      <View style={styles.container}>
        <Text>Erro ao carregar despesas: {error.message}</Text>
      </View>
    );
  }

  return (
    <View style={styles.container}>
      <FlatList
        data={expenses}
        showsVerticalScrollIndicator={false}
        onEndReached={loadMore}
        renderItem={({ item }) => (
          <TouchableOpacity
            style={styles.item}
            onPress={() => router.push(`/expenses/form?id=${item.id}`)}
          >
            <View style={{ flex: 1 }}>
              <Text style={styles.title}>{item.title}</Text>
              <Text style={styles.details}>
                R$ {Number(item.amount).toFixed(2)} — {item.payment} (
                {item.status})
              </Text>
            </View>
            <Text style={styles.edit}>✏️</Text>
          </TouchableOpacity>
        )}
        keyExtractor={(item) => item.id?.toString() ?? Math.random().toString()}
        contentContainerStyle={{ paddingBottom: 80 }}
      />

      <TouchableOpacity
        style={styles.addButton}
        onPress={() => router.push("/expenses/form")}
      >
        <Text style={styles.addText}>+ Nova Despesa</Text>
      </TouchableOpacity>
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: "#fff", padding: 20 },
  header: {
    fontSize: 22,
    fontWeight: "bold",
    textAlign: "center",
    marginBottom: 15,
    color: "#333",
  },
  item: {
    flexDirection: "row",
    alignItems: "center",
    backgroundColor: "#f8f9fa",
    padding: 15,
    borderRadius: 10,
    marginBottom: 10,
    shadowColor: "#000",
    shadowOpacity: 0.05,
    shadowOffset: { width: 0, height: 1 },
    elevation: 1,
  },
  title: { fontSize: 16, fontWeight: "600", color: "#222" },
  details: { color: "#555", marginTop: 4 },
  edit: { fontSize: 18, color: "#3B82F6" },
  addButton: {
    backgroundColor: "#3B82F6",
    paddingVertical: 14,
    borderRadius: 10,
    position: "absolute",
    bottom: 30,
    left: 20,
    right: 20,
  },
  addText: {
    color: "#fff",
    fontSize: 16,
    fontWeight: "700",
    textAlign: "center",
  },
});
