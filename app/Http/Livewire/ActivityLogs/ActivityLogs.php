<?php
namespace App\Http\Livewire\ActivityLogs;

use App\Models\ActivityLog;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Zekini\CrudGenerator\Traits\HandlesFile;
use Zekini\Generics\Helpers\FlashHelper;


class ActivityLogs extends Component
{
    
    public function render()
    {
        return view('livewire.activity-logs.index')
            ->extends('zekini/livewire-crud-generator::admin.layout.default')
            ->section('body');
    }

    }
