import React, { useEffect, useState } from "react";
import {
  View,
  Text,
  TouchableOpacity,
  Image,
  FlatList,
  StyleSheet,
} from "react-native";
import * as DocumentPicker from "expo-document-picker";
import { PickedImage } from "../types/PickedImage";
import { ApiService } from "../services/api";

export default function MultiImagePicker({
  initialImages = [],
  onChange,
}: {
  initialImages?: PickedImage[];
  onChange: (imgs: PickedImage[]) => void;
}) {
  const [images, setImages] = useState<PickedImage[]>([]);

  useEffect(() => {
    if (initialImages.length > 0) {
      setImages(initialImages);
    }
  }, [initialImages]);

  async function pickImages() {
    try {
      const result = await DocumentPicker.getDocumentAsync({
        type: "image/*",
        multiple: true,
        copyToCacheDirectory: false,
      });

      if (result.canceled) return;

      let picked: PickedImage[] = [];

      if ("assets" in result && Array.isArray(result.assets)) {
        picked = result.assets.map((a) => ({
          uri: a.uri,
          name: a.name ?? `image_${Date.now()}.jpg`,
          mimeType: a.mimeType ?? "image/jpeg",
          size: a.size ?? 0,
        }));
      }

      const newList = [...images, ...picked];

      setImages(newList);

      onChange(newList);
    } catch (error) {
      console.error("Erro ao selecionar imagens:", error);
    }
  }

  function removeImage(uri: string) {
    const newList = images.filter((img) => img.uri !== uri);

    setImages(newList);

    onChange(newList);
  }

  const renderItem = ({ item }: { item: PickedImage }) => (
    <View style={styles.thumbContainer}>
      <TouchableOpacity
        style={styles.removeButton}
        onPress={() => removeImage(item.uri)}
      >
        <Text style={styles.removeButtonText}>X</Text>
      </TouchableOpacity>

      <Image source={{ uri: item.uri }} style={styles.thumb} />
      <Text numberOfLines={1} style={styles.filename}>
        {item.name}
      </Text>
    </View>
  );

  return (
    <View style={styles.container}>
      <TouchableOpacity style={styles.button} onPress={pickImages}>
        <Text style={styles.buttonText}>Selecionar imagens</Text>
      </TouchableOpacity>

      {images.length === 0 ? (
        <Text style={styles.empty}>Nenhuma imagem selecionada</Text>
      ) : (
        <FlatList
          horizontal
          data={images}
          renderItem={renderItem}
          keyExtractor={(item, index) => item.uri + index}
          showsHorizontalScrollIndicator={false}
          style={styles.list}
        />
      )}
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    padding: 16,
  },
  button: {
    backgroundColor: "#2b8aef",
    paddingVertical: 12,
    paddingHorizontal: 16,
    borderRadius: 8,
    alignItems: "center",
    marginBottom: 12,
  },
  buttonText: {
    color: "white",
    fontWeight: "600",
  },
  empty: {
    color: "#666",
    textAlign: "center",
    marginTop: 8,
  },
  list: {
    marginTop: 8,
  },
  thumbContainer: {
    width: 100,
    marginRight: 10,
    alignItems: "center",
    position: "relative",
  },
  thumb: {
    width: 96,
    height: 96,
    borderRadius: 8,
    resizeMode: "cover",
  },
  filename: {
    marginTop: 4,
    fontSize: 12,
    maxWidth: 96,
  },
  removeButton: {
    position: "absolute",
    top: -6,
    right: -6,
    backgroundColor: "red",
    width: 24,
    height: 24,
    borderRadius: 12,
    justifyContent: "center",
    alignItems: "center",
    zIndex: 10,
  },
  removeButtonText: {
    color: "white",
    fontWeight: "bold",
    fontSize: 14,
  },
});
