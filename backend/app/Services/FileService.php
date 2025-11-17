<?php

namespace App\Services;

use App\Models\Resource;
use Core\Constants\Constants;
use Core\Database\ActiveRecord\Model;

class FileService
{
    /** @var array<string, mixed> $image */
    private array $image;
    private string $file_name;

    /** @param array<string, mixed> $validations */
    public function __construct(
        private Resource $model,
        private string $storeDir,
        private array $validations = [],
    ) {}

    public function path(): string
    {
        if (!empty($this->model->file_path)) {
            // Generate MD5 hash of the avatar file to use as cache buster in URL
            // aqui gera o hash para forçar o navegador a recarregar se o arquivo mudar (por isso tbm da ?)
            $filePath = $this->getAbsoluteSavedFilePath();

            // Return the avatar URL with hash parameter to force browser to reload when file changes
            if (file_exists($filePath)) {
                $hash = md5_file($filePath);
                return $this->baseDir() . $this->model->file_path . '?' . $hash;
            }
        }

        return "/assets/images/defaults/no-image.png";
    }

    /**
     * @param array<string, mixed> $image
     */
    public function upload(array $image): bool
    {
        $this->image = $image;

        if (!$this->isValidImage()) {
            return false;
        }
        $this->file_name = time() . "-" . md5_file($this->getTmpFilePath());

        $this->model->file_path = '/assets/uploads/uploads/resources/' . $this->getFileName();
        if ($this->model->save()) {
            $this->updateFile();
            return true;
        }

        return false;
    }

    protected function updateFile(): bool
    {
        if (empty($this->getTmpFilePath())) {
            return false;
        }

        $this->removeOldImage();

        $resp = move_uploaded_file(
            $this->getTmpFilePath(),
            $this->getAbsoluteDestinationPath()
        );

        if (!$resp) {
            $error = error_get_last();
            throw new \RuntimeException(
                'Failed to move uploaded file: ' . ($error['message'] ?? 'Unknown error')
            );
        }

        return true;
    }

    private function getTmpFilePath(): string
    {
        return $this->image['tmp_name'] ?? '';
    }

    private function removeOldImage(): void
    {
        if ($this->model->file_path) {
            $oldPath = $this->getAbsoluteSavedFilePath();
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }
    }

    private function getFileName(): string
    {
        $parts = explode('.', $this->image['name']);
        $extension = strtolower(end($parts));
        return "{$this->file_name}.{$extension}";
    }

    private function getAbsoluteDestinationPath(): string
    {
        return $this->storeDir() . $this->getFileName();
    }

    private function baseDir(): string
    {
        return "/assets/uploads/{$this->storeDir}/";
    }

    private function storeDir(): string
    {
        $path = Constants::rootPath()->join('public' . $this->baseDir());
        if (!is_dir($path)) {
            mkdir(directory: $path, recursive: true);
        }

        return $path;
    }

    private function getAbsoluteSavedFilePath(): string
    {
        return Constants::rootPath()
            ->join('public' . $this->baseDir())
            ->join($this->model->file_path);
    }

    private function isValidImage(): bool
    {
        if (isset($this->validations['extension'])) {
            $this->validateImageExtension();
        }

        if (empty($this->getTmpFilePath())) {
            $this->model->addError('image', 'Não pode ser vazia');
        }

        if (isset($this->validations['size'])) {
            $this->validateImageSize();
        }

        return $this->model->errors('image') === null;
    }

    private function validateImageExtension(): void
    {
        $parts = explode('.', $this->image['name']);
        $extension = strtolower(end($parts));

        if (!in_array($extension, $this->validations['extension'])) {
            $this->model->addError('image', 'Extensão de arquivo inválida.');
        }
    }

    private function validateImageSize(): void
    {
        if ($this->image['size'] > $this->validations['size']) {
            $this->model->addError('avatar', 'Tamanho do arquivo inválido');
        }
    }

    public function deleteImage(): bool
    {
        $filePath = $this->model->file_path ?? null;

        if (empty($filePath)) {
            return false;
        }

        $absolutePath = Constants::rootPath()->join('public' . $filePath);

        if ($this->model->destroy()) {
            if (file_exists($absolutePath)) {
                unlink($absolutePath);
            }
            return true;
        }

        return false;
    }

    /**
     * @param array<string, array<int, mixed>> $files
     * @return array<int, array<string, mixed>>
     */
    public function formatedArrayFile(array $files): array
    {
        $normalizedFiles = [];

        if (!empty($files['name'])) {
            foreach ($files['name'] as $index => $name) {
                $normalizedFiles[] = [
                    'name' => $name,
                    'full_path' => $files['full_path'][$index] ?? null,
                    'type' => $files['type'][$index] ?? null,
                    'tmp_name' => $files['tmp_name'][$index] ?? null,
                    'error' => $files['error'][$index] ?? null,
                    'size' => $files['size'][$index] ?? null,
                ];
            }
        }
        return $normalizedFiles;
    }
}
