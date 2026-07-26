<?php

namespace MeaCms\Menu\Services;

use MeaCms\Menu\Models\Picture;
use MeaCms\Menu\Interfaces\Repositories\PictureRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

class PictureService
{
    protected PictureRepositoryInterface $pictureRepository;

    public function __construct(PictureRepositoryInterface $pictureRepository)
    {
        $this->pictureRepository = $pictureRepository;
    }

    public function getAllPictures(array $filters = []): array|Collection|SupportCollection
    {
        return $this->pictureRepository->all($filters);
    }

    public function getPictureById(int $pictureId): ?Picture
    {
        return $this->pictureRepository->find($pictureId);
    }

    public function createPicture(Model $model, array $data): ?Picture
    {
        return $this->pictureRepository->create($model, $data);
    }

    public function updatePicture(Model $model, int $pictureId, array $data): ?Picture
    {
        return $this->pictureRepository->update($model, $pictureId, $data);
    }

    public function deletePicture(int $pictureId): bool
    {
        return $this->pictureRepository->delete($pictureId);
    }

    public function setPrimaryPicture(Model $model, int $pictureId): bool
    {
        return $this->pictureRepository->setPrimary($model, $pictureId);
    }

    public function replacePicture(Model $model, int $pictureId, array $data): ?Picture
    {
        return $this->pictureRepository->update($model, $pictureId, $data);
    }

    public function uploadStandalonePicture(array $data): ?string
    {
        $directory = $data['path'] ?? 'pictures';
        $file = $data['picture'];

        if (!$file->isValid()) {
            return null;
        }

        return $file->store($directory, 'public');
    }
}
