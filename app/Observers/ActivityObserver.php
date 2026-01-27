<?php

namespace App\Observers;

use App\Models\Activity;
use Illuminate\Support\Facades\Auth;

class ActivityObserver
{
    public function created($model)
    {
        $this->logActivity('create', $model);
    }

    public function updated($model)
    {
        $this->logActivity('update', $model);
    }

    public function deleted($model)
    {
        $this->logActivity('delete', $model);
    }

    // Handle soft deletes if applicable
    public function restored($model)
    {
        $this->logActivity('restore', $model);
    }

    protected function logActivity($action, $model)
    {
        if (!Auth::check())
            return;

        $description = $this->generateDescription($action, $model);

        Activity::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'description' => $description,
            'subject_type' => get_class($model),
            'subject_id' => $model->id,
        ]);
    }

    protected function generateDescription($action, $model)
    {
        $modelName = class_basename($model);
        $name = $model->name ?? $model->nama_lengkap ?? $model->nama_barang ?? $model->nama_ruangan ?? $model->nama_kelas ?? $model->kode_unit ?? 'Item';

        switch ($action) {
            case 'create':
                return "Menambahkan $modelName baru: $name";
            case 'update':
                return "Memperbarui $modelName: $name";
            case 'delete':
                return "Menghapus $modelName: $name";
            case 'restore':
                return "Memulihkan $modelName: $name";
            default:
                return ucfirst($action) . " $modelName";
        }
    }
}
