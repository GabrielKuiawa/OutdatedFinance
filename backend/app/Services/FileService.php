<?php

namespace App\Services;

use Core\Constants\Constants;
use Core\Database\ActiveRecord\Model;

class FileService
{
    // pra guardar o arquivo temporariamente
    private array $file;

    // recebe uma instância do model a resource
    public function __construct(
        private Model $model
    ) {
    }

    // retorna o caminho público da imagem pra mostrar no front
    public function path(): string
    {
        // se tiver um arquivo salvo no banco retorna o caminho dele
        if ($this->model->file_path) {
            return $this->baseDir() . $this->model->file_path;
        }

        // se não tiver retorna uma imagem padrão
        return "/assets/images/defaults/no-image.png";
    }

    // método pra atualizar ou salvar o arquivo
    public function update(array $file): void
    {
        // guarda o arquivo enviado
        $this->file = $file;

        // verifica se o arquivo temporário existe
        if (!empty($this->getTmpFilePath())) {
            // remove o arquivo antigo (se existir)
            $this->removeOldFile();
            // atualiza o nome do arquivo no banco
            $this->model->update(['file_path' => $this->getFileName()]);
            // move o novo arquivo para o diretório certo
            move_uploaded_file($this->getTmpFilePath(), $this->getAbsoluteFilePath());
        }
    }

    // retorna o caminho temporário do upload
    private function getTmpFilePath(): string
    {
        return $this->file['tmp_name'];
    }

    // remove o arquivo antigo se tiver
    private function removeOldFile(): void
    {
        if ($this->model->file_path) {
            $path = Constants::rootPath()->join('public' . $this->baseDir())->join($this->model->file_path);
            if (file_exists($path)) {
                unlink($path); // apaga o arquivo
            }
        }
    }

    // gera um novo nome único pro arquivo
    private function getFileName(): string
    {
        $file_name_splitted  = explode('.', $this->file['name']);
        $file_extension = end($file_name_splitted);
        // gera nome tipo "resource_abc123.jpg"
        return uniqid('resource_') . '.' . $file_extension;
    }

    // retorna o caminho completo (absoluto) pra onde o arquivo vai ser salvo
    private function getAbsoluteFilePath(): string
    {
        return $this->storeDir() . $this->getFileName();
    }

    // monta o caminho base dentro de /public/assets/uploads
    private function baseDir(): string
    {
        return "/assets/uploads/{$this->model::table()}/{$this->model->id}/";
    }

    // cria o diretório se ele não existir
    private function storeDir(): string
    {
        $path = Constants::rootPath()->join('public' . $this->baseDir());
        if (!is_dir($path)) {
            mkdir(directory: $path, recursive: true);
        }

        return $path;
    }
}
