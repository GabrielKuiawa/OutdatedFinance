import { View, Text, StyleSheet } from "react-native";

export default function Home() {
    return (
        <View style={styles.container}>
             <Text style={styles.text}> 
                Bem vindo a página inicial do usuário!
             </Text>
        </View>
    );
}

const styles = StyleSheet.create({
  container: { flex: 1, justifyContent: 'center', padding: 20, backgroundColor: '#fff' },
  text: { fontSize: 24, fontWeight: 'bold', marginBottom: 30, textAlign: 'center' },
  
});