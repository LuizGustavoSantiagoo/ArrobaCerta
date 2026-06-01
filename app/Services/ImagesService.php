<?php

namespace App\Services;

use App\Models\CattleImages;
use Core\Constants\Constants;
use Core\Database\ActiveRecord\Model;

class ImagesService
{
    /** @var array<string, mixed> $image */
    private array $image;

    /** @var array<string, mixed> $validations */
    private array $validations;

    private ?string $error = null;

    /** @param array<string, mixed> $validations */
    public function __construct(
        private Model $model,
        array $validations = []
    ) {
        $this->validations = $validations;
    }

    public function getError(): ?string
    {
        return $this->error;
    }

    /** @return array<CattleImages> */
    public function all(): array
    {
        return CattleImages::where(['cattle_id' => $this->model->id]);
    }

    public function path(CattleImages $image): string
    {
        return $this->baseDir() . $image->file_name;
    }

    /** @param array<string, mixed> $image */
    public function create(array $image, string $type, int $registeredById): bool
    {
        $this->image = $image;

        $cattleImage = new CattleImages([
            'type' => $type,
            'cattle_id' => $this->model->id,
            'registered_by_id' => $registeredById,
        ]);

        if (!$this->isValidImage($cattleImage)) {
            $this->error = $cattleImage->errors('image');
            return false;
        }

        $fileName = $this->getFileName();

        if (!$this->moveFile($fileName)) {
            return false;
        }

        $cattleImage->file_name = $fileName;
        $cattleImage->file_path = $this->baseDir() . $fileName;

        return $cattleImage->save();
    }

    public function destroy(CattleImages $image): bool
    {
        $this->removeFile($image);

        return $image->destroy();
    }

    private function removeFile(CattleImages $image): void
    {
        $path = (string) Constants::rootPath()->join('public' . $image->file_path);

        if (is_file($path)) {
            unlink($path);
        }
    }

    private function moveFile(string $fileName): bool
    {
        if (empty($this->getTmpFilePath())) {
            return false;
        }

        $resp = move_uploaded_file(
            $this->getTmpFilePath(),
            $this->storeDir() . $fileName
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

    private function getFileName(): string
    {
        $file_name_splitted = explode('.', $this->image['name']);
        $file_extension = end($file_name_splitted);

        return uniqid('img_', true) . '.' . $file_extension;
    }

    private function baseDir(): string
    {
        return "/assets/uploads/{$this->model::table()}/{$this->model->id}/";
    }

    private function storeDir(): string
    {
        $path = (string) Constants::rootPath()->join('public' . $this->baseDir());
        if (!is_dir($path)) {
            mkdir(directory: $path, recursive: true);
        }

        return $path;
    }

    private function isValidImage(CattleImages $cattleImage): bool
    {
        if (isset($this->validations['extension'])) {
            $this->validateImageExtension($cattleImage);
        }

        if (isset($this->validations['size'])) {
            $this->validateImageSize($cattleImage);
        }

        return $cattleImage->errors('image') === null;
    }

    private function validateImageExtension(CattleImages $cattleImage): void
    {
        $file_name_splitted = explode('.', $this->image['name']);
        $file_extension = strtolower(end($file_name_splitted));

        if (!in_array($file_extension, $this->validations['extension'])) {
            $cattleImage->addError('image', 'Extensão de arquivo inválida');
        }
    }

    private function validateImageSize(CattleImages $cattleImage): void
    {
        if (($this->image['size'] ?? 0) > $this->validations['size']) {
            $cattleImage->addError('image', 'Tamanho do arquivo inválido');
        }
    }
}
