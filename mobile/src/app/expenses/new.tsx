import React, { useState } from "react";
import {
  View,
  Text,
  TextInput,
  TouchableOpacity,
  Alert,
  StyleSheet,
  ScrollView,
} from "react-native";
import { Ionicons } from "@expo/vector-icons";
import { useRouter } from "expo-router";
import { saveExpenseApi } from "@/src/services/api";

export default function NewExpense() {
  const router = useRouter();
  const [title, setTitle] = useState("testeMobileeeeeee");
  const [amount, setAmount] = useState("09");
  const [description, setDescription] = useState("deee");
  const [date, setDate] = useState("2025-10-10");
  const [status, setStatus] = useState<"pago" | "pendente" | "atrasado" | "">(
    "pago"
  );
  const [payment, setPayment] = useState<"pix" | "cartao" | "dinheiro" | "">(
    "pix"
  );

  const handleSave = async () => {
    if (
      !title.trim() ||
      !amount.trim() ||
      !date.trim() ||
      !status ||
      !payment
    ) {
      Alert.alert("Erro", "Preencha todos os campos obrigatórios.");
      return;
    }

    if (isNaN(Number(amount))) {
      Alert.alert("Erro", "O valor deve ser um número válido.");
      return;
    }

    const { data } = await saveExpenseApi({
      title,
      amount,
      description,
      expense_date: date,
      register_by_user_id: 2,
      status,
      payment,
      created_at: "2025-10-21 12:06:32",
    });

    if (data) {
      Alert.alert("Sucesso", "Despesa salva com sucesso!");
      setTitle("");
      setAmount("");
      setDescription("");
      setDate("");
      setStatus("");
      setPayment("");
      router.push("/expenses");
      return;
    }
    Alert.alert("Erro", "Não foi possível salvar a despesa.");
  };

  return (
    <ScrollView
      style={styles.container}
      contentContainerStyle={{ paddingBottom: 40 }}
    >
      {/* <Text style={styles.title}>Nova Despesa</Text> */}

      <View style={styles.row}>
        <TextInput
          style={[styles.input, { flex: 1 }]}
          placeholder="Título"
          placeholderTextColor="#aaa"
          value={title}
          onChangeText={setTitle}
        />
        <TextInput
          style={[styles.input, { flex: 1, marginLeft: 8 }]}
          placeholder="Valor"
          placeholderTextColor="#aaa"
          keyboardType="numeric"
          value={amount}
          onChangeText={setAmount}
        />
      </View>

      <TextInput
        style={[styles.input, { height: 100, textAlignVertical: "top" }]}
        placeholder="Descrição (opcional)"
        placeholderTextColor="#aaa"
        multiline
        value={description}
        onChangeText={setDescription}
      />

      <TextInput
        style={styles.input}
        placeholder="Data"
        placeholderTextColor="#aaa"
        value={date}
        onChangeText={setDate}
      />

      <Text style={styles.label}>Status</Text>
      <View style={styles.statusContainer}>
        {[
          { key: "pago", color: "#22C55E", icon: "checkmark-circle-outline" },
          { key: "pendente", color: "#FACC15", icon: "time-outline" },
          { key: "atrasado", color: "#EF4444", icon: "close-circle-outline" },
        ].map((s) => (
          <TouchableOpacity
            key={s.key}
            style={[
              styles.statusButton,
              { backgroundColor: status === s.key ? s.color : "#333" },
            ]}
            onPress={() => setStatus(s.key as any)}
          >
            <Ionicons name={s.icon as any} size={18} color="#fff" />
            <Text style={styles.statusText}>{s.key}</Text>
          </TouchableOpacity>
        ))}
      </View>

      <Text style={styles.label}>Forma de Pagamento</Text>
      <View style={styles.paymentContainer}>
        {[
          { key: "pix", icon: "qr-code-outline" },
          { key: "cartao", icon: "card-outline" },
          { key: "dinheiro", icon: "cash-outline" },
        ].map((p) => (
          <TouchableOpacity
            key={p.key}
            style={[
              styles.paymentButton,
              {
                backgroundColor: payment === p.key ? "#22C55E" : "#333",
              },
            ]}
            onPress={() => setPayment(p.key as any)}
          >
            <Ionicons name={p.icon as any} size={18} color="#fff" />
            <Text style={styles.paymentText}>{p.key}</Text>
          </TouchableOpacity>
        ))}
      </View>

      <TouchableOpacity style={styles.saveButton} onPress={handleSave}>
        <Text style={styles.saveButtonText}>Salvar</Text>
      </TouchableOpacity>
    </ScrollView>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: "#FFFFFF",
    padding: 20,
  },
  title: {
    color: "#222",
    fontSize: 22,
    fontWeight: "bold",
    textAlign: "center",
    marginBottom: 20,
  },
  input: {
    backgroundColor: "#F8F8F8",
    color: "#000",
    borderRadius: 8,
    padding: 12,
    borderWidth: 1,
    borderColor: "#DDD",
    marginBottom: 15,
  },
  label: {
    color: "#333",
    fontWeight: "600",
    marginBottom: 8,
  },
  row: {
    flexDirection: "row",
  },
  statusContainer: {
    flexDirection: "row",
    justifyContent: "space-between",
    marginBottom: 15,
  },
  statusButton: {
    flex: 1,
    alignItems: "center",
    paddingVertical: 10,
    borderRadius: 8,
    marginHorizontal: 4,
  },
  statusText: {
    color: "#fff",
    fontWeight: "600",
    textTransform: "capitalize",
  },
  paymentContainer: {
    flexDirection: "row",
    justifyContent: "space-between",
    marginBottom: 20,
  },
  paymentButton: {
    flex: 1,
    alignItems: "center",
    paddingVertical: 10,
    borderRadius: 8,
    marginHorizontal: 4,
  },
  paymentText: {
    color: "#fff",
    fontWeight: "600",
    textTransform: "capitalize",
  },
  switchContainer: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "center",
    marginBottom: 25,
  },
  switchLabel: {
    color: "#222",
    fontSize: 16,
    fontWeight: "600",
  },
  saveButton: {
    backgroundColor: "#3B82F6",
    paddingVertical: 14,
    borderRadius: 10,
  },
  saveButtonText: {
    color: "#fff",
    fontWeight: "700",
    textAlign: "center",
    fontSize: 16,
  },
});
