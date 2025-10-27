import AsyncStorage from "@react-native-async-storage/async-storage";

export const getData = async (key: string) => {
  try {
    const value = await AsyncStorage.getItem(key);
    if (value !== null) {
      return value.trim();
    }
  } catch (e) {
    console.error("Erro ao ler", e);
  }
};

export const storeData = async (key: string, value: string) => {
  try {
    await AsyncStorage.setItem(key, value);
    console.log("Dado salvo!");
  } catch (e) {
    console.error("Erro ao salvar", e);
  }
};

export const removeData = async (key: string) => {
  try {
    await AsyncStorage.removeItem(key);
    console.log("Dado removido!");
  } catch (e) {
    console.error("Erro ao remover", e);
  }
};
let cachedToken: string | null = null;

export const loadToken = async () => {
  cachedToken = await AsyncStorage.getItem("userToken");
};

export const getToken = () => cachedToken;
