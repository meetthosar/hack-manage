<?php
namespace App\Http\Livewire\Roles\Datatable;

use App\Imports\RolesImport;
use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Zekini\CrudGenerator\Traits\HandlesFile;
use Zekini\CrudGenerator\Helpers\CrudModelList;
use App\Models\Role;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\BooleanColumn;

class RolesTable extends LivewireDatatable
{
    use AuthorizesRequests;
    use HandlesFile;
    use WithFileUploads;

    public $model = Role::class;

    public $importBtn = true;

    public $exportable = true;

    public $file;

    public $softdeletes = false;

        public $showBtns = true;
    
    public $launchCreateEventModal = 'launchRoleCreateModal';

    protected $customListeners = [
        'downloadTemplate',
        'toggleSoftDeletes',
    ];

    public function builder()
    {
        $query = Role::query();

        $query = $this->softdeletes ? $query->onlyTrashed() : $query; 

        return $query
                        ->with([
                               'permissions'
                           ])
                       ;
    }

    public function columns()
    {
        return [
            // adding causer and subject for logs
            
                            
                
                                                                            
                        Column::name('guard_name')
                            ->label('Guard_name')
                            ->defaultSort('asc')
                                            ->searchable()
                                            ->hideable()
                            ->filterable(),
                                                                    
                
                                                                            
                        Column::name('name')
                            ->label('Name')
                            ->defaultSort('asc')
                                            ->hideable()
                            ->filterable(),
                                                    
            // belongs to many relationship tables
                            Column::name('permissions.name')
                ->label('permission')
            ,
            
            // Non pivot belongs to relationships
            
                        Column::callback(['id'], function ($id) {
                return view('zekini/livewire-crud-generator::datatable.table-actions', [
                    'id' => $id, 
                    'view' => 'role',
                    'model'=> 'role',
                    'softdeletes'=> $this->softdeletes
                ]);
            })->label('Actions')->excludeFromExport()
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
        $this->authorize('admin.role.delete');

        $role = Role::withTrashed()->find($id);

        $fileCols = $this->checkForFiles($role);
        foreach($fileCols as $files){
            $this->deleteFile($files);
        }

        $role->forceDelete();

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
        $this->authorize('admin.role.delete');

        $role = Role::find($id);

        $fileCols = $this->checkForFiles($role);
        foreach($fileCols as $files){
            $this->deleteFile($files);
        }

        $role->delete();

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
        $this->authorize('admin.role.delete');

        $role = Role::withTrashed()->find($id);

        $fileCols = $this->checkForFiles($role);
        foreach($fileCols as $files){
            $this->deleteFile($files);
        }

        $role->restore();

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

    public function launchRoleEditModal(Role $Role)
    {
        $this->emit('launchRoleEditModal', $Role);
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
        $filename = 'role.xlsx';

        if (!Storage::disk('templates')->exists($filename)) {
            $this->emit('flashMessageEvent', "Failed to find template $filename");

            return;
        }

        return response()->download(storage_path('app/public/templates/' . $filename));
    }

    public function updatedFile()
    {
        $filename = $this->file->store('imports');

        Excel::import(new RolesImport($this->file), $filename);

        $this->emit('flashMessageEvent', 'Imported');
        $this->emit('refreshLivewireDatatable');
    }
}
