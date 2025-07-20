<?php

namespace App\Repositories\Bahan;

use App\Models\Bahan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use LaravelEasyRepository\Implementations\Eloquent;

class BahanRepositoryImplement extends Eloquent implements BahanRepository
{

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected Bahan $model;

    public function __construct(Bahan $model)
    {
        $this->model = $model;
    }

    public function getData()
    {
        return $this->model->all();
    }

    public function store1($data)
    {
        return $this->model->create($data);
    }

    public function store($data)
    {
        return DB::transaction(function () use ($data) {
            $variants = $data['variants'] ?? [];
            unset($data['variants']);

            $bahan = $this->model->create($data);

            foreach ($variants as $variant) {
                $bahan->variants()->create($variant);
            }

            return $bahan;
        });
    }

    public function show($id)
    {
        return $this->model->with('variants')->find($id);
    }

    public function update1($data, $id)
    {
        $query = $this->model->find($id);
        return $query->update($data);
    }

    public function update($data, $id)
    {
        return DB::transaction(function () use ($data, $id) {
            $variants = $data['variants'] ?? [];
            unset($data['variants']);

            $bahan = $this->model->findOrFail($id);
            $bahan->update($data);

            // Hapus semua variants lama
            $bahan->variants()->delete();

            // Tambah ulang variants baru
            foreach ($variants as $variant) {
                $bahan->variants()->create($variant);
            }

            return $bahan;
        });
    }

    public function destroy($id)
    {
        return DB::transaction(function () use ($id) {
            $bahan = $this->model->findOrFail($id);

            // Hapus semua variants terlebih dahulu
            $bahan->variants()->delete();

            // Hapus bahan
            return $bahan->delete();
        });
    }


    public function destroy1($id)
    {
        $query = $this->model->find($id);
        return $query->delete();
    }
}
