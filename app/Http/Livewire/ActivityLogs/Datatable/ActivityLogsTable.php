<?php
namespace App\Http\Livewire\ActivityLogs\Datatable;

use App\Imports\ActivityLogsImport;
use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Zekini\CrudGenerator\Traits\HandlesFile;
use Zekini\CrudGenerator\Helpers\CrudModelList;
use App\Models\ActivityLog;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\BooleanColumn;

class ActivityLogsTable extends LivewireDatatable
{
    use AuthorizesRequests;
    use HandlesFile;
    use WithFileUploads;

    public $model = ActivityLog::class;

    public $importBtn = true;

    public $exportable = true;

    public $file;

    public $softdeletes = false;

        public $showBtns = false;
    
    public $launchCreateEventModal = 'launchActivityLogCreateModal';

    protected $customListeners = [
        'downloadTemplate',
        'toggleSoftDeletes',
    ];

    public function builder()
    {
        $query = ActivityLog::query();

        $query = $this->softdeletes ? $query->onlyTrashed() : $query; 

        return $query
                    ->with(['causer', 'subject'])
               ;
    }

    public function columns()
    {
        return [
            // adding causer and subject for logs
                            Column::callback(['id', 'causer_type'], function($id, $causer_type){
                $causer = ActivityLog::withTrashed()->findOrFail($id)->causer;
                $explode = explode("\\", $causer_type);
                $type = $explode[array_key_last($explode)];
                return "$causer->name ($type)";
            })->label('Causer'),
            
                            
                
                                                                            
                        Column::name('batch_uuid')
                            ->label('Batch_uuid')
                            ->defaultSort('asc')
                                            ->searchable()
                                            ->hideable()
                            ->filterable(),
                                                                    
                                // checks if the column name is a relation  eg table_id
                                                                                            // for activitylog polymorphic relations we skip so we handle differently
                                    
                
                                                        
                        Column::name('description')
                            ->label('Description')
                            ->defaultSort('asc')
                                            ->hideable()
                            ->filterable(),
                                                                    
                
                                                                            
                        Column::name('event')
                            ->label('Event')
                            ->defaultSort('asc')
                                            ->hideable()
                            ->filterable(),
                                                                    
                
                                                                            
                        Column::name('log_name')
                            ->label('Log_name')
                            ->defaultSort('asc')
                                            ->hideable()
                            ->filterable(),
                                                                    
                
                                                        
                        Column::name('properties')
                            ->label('Properties')
                            ->defaultSort('asc')
                                            ->hideable()
                            ->filterable(),
                                                                    
                                // checks if the column name is a relation  eg table_id
                                                                            
                
                                                                            
                        Column::name('subject_type')
                            ->label('Subject_type')
                            ->defaultSort('asc')
                                            ->hideable()
                            ->filterable(),
                                                    
            // belongs to many relationship tables
            
            // Non pivot belongs to relationships
            
                    ];
    }

    protected function getListeners()
    {
        return array_merge($this->listeners, $this->customListeners);
    }

    /**
     * Force deletes a model
     *
     * @param    int $id
     * @return  void
     */
    public function forceDelete(int $id): void
    {
        $this->authorize('admin.activity_log.delete');

        $activitylog = ActivityLog::withTrashed()->find($id);

        $fileCols = $this->checkForFiles($activitylog);
        foreach($fileCols as $files){
            $this->deleteFile($files);
        }

        $activitylog->forceDelete();

        $this->emit('flashMessageEvent', 'Item Deleted succesfully');

        $this->emit('refreshLivewireDatatable');
    }

    /**
     * Deletes  a model
     *
     * @param  int $id
     * @return  void
     */
    public function delete($id): void
    {
        $this->authorize('admin.activity_log.delete');

        $activitylog = ActivityLog::find($id);

        $fileCols = $this->checkForFiles($activitylog);
        foreach($fileCols as $files){
            $this->deleteFile($files);
        }

        $activitylog->delete();

        $this->emit('flashMessageEvent', 'Item Trashed succesfully');

        $this->emit('refreshLivewireDatatable');
    }

    /**
     * Restores a deleted model
     *
     * @param    int $id
     * @return  void
     */
    public function restore(int $id): void
    {
        $this->authorize('admin.activity_log.delete');

        $activitylog = ActivityLog::withTrashed()->find($id);

        $fileCols = $this->checkForFiles($activitylog);
        foreach($fileCols as $files){
            $this->deleteFile($files);
        }

        $activitylog->restore();

        $this->emit('flashMessageEvent', 'Item Restored succesfully');

        $this->emit('refreshLivewireDatatable');
    }

    /**
     * Checks if a model has files or images and deletes it
     *
     * @param    mixed $model
     * @return  array
     */
    protected function checkForFiles($model)
    {
        return collect($model->getAttributes())->filter(function($col, $index){
            return Str::likelyFile($index);
        })
            ->toArray();
    }

    public function launchActivityLogEditModal(ActivityLog $ActivityLog)
    {
        $this->emit('launchActivityLogEditModal', $ActivityLog);
    }

    public function toggleSoftDeletes()
    {
        $this->softdeletes = ! $this->softdeletes;

        $this->emit('refreshLivewireDatatable');
    }

    public function render()
    {
        $this->emit('refreshDynamic');

        if ($this->persistPerPage) {
            session()->put([$this->sessionStorageKey() . '_perpage' => $this->perPage]);
        }

        return view('zekini/livewire-crud-generator::datatable.datatable')->layoutData(['title' => $this->title]);
    }

    public function downloadTemplate()
    {
        $filename = 'activitylog.xlsx';

        if (!Storage::disk('templates')->exists($filename)) {
            $this->emit('flashMessageEvent', "Failed to find template $filename");

            return;
        }

        return response()->download(storage_path('app/public/templates/' . $filename));
    }

    public function updatedFile()
    {
        $filename = $this->file->store('imports');

        Excel::import(new ActivityLogsImport($this->file), $filename);

        $this->emit('flashMessageEvent', 'Imported');
        $this->emit('refreshLivewireDatatable');
    }
}
