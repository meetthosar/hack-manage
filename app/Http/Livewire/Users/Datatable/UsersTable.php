<?php
namespace App\Http\Livewire\Users\Datatable;

use App\Imports\UsersImport;
use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Zekini\CrudGenerator\Traits\HandlesFile;
use Zekini\CrudGenerator\Helpers\CrudModelList;
use App\Models\User;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\BooleanColumn;

class UsersTable extends LivewireDatatable
{
    use AuthorizesRequests;
    use HandlesFile;
    use WithFileUploads;

    public $model = User::class;

    public $importBtn = true;

    public $exportable = true;

    public $file;

    public $softdeletes = false;

        public $showBtns = true;
    
    public $launchCreateEventModal = 'launchUserCreateModal';

    protected $customListeners = [
        'downloadTemplate',
        'toggleSoftDeletes',
    ];

    public function builder()
    {
        $query = User::query();

        $query = $this->softdeletes ? $query->onlyTrashed() : $query; 

        return $query
                        ->with([
                               'roles'
                           ])
                       ;
    }

    public function columns()
    {
        return [
            // adding causer and subject for logs
            
                            
                
                                                        
                        Column::name('created_by')
                            ->label('Created_by')
                            ->defaultSort('asc')
                                            ->searchable()
                                            ->hideable()
                            ->filterable(),
                                                                    
                                // checks if the column name is a relation  eg table_id
                                                                            
                
                                                        
                        Column::name('deleted_by')
                            ->label('Deleted_by')
                            ->defaultSort('asc')
                                            ->hideable()
                            ->filterable(),
                                                                    
                
                                                                            
                        Column::name('email')
                            ->label('Email')
                            ->defaultSort('asc')
                                            ->hideable()
                            ->filterable(),
                                                                    
                
                                                                            
                        Column::name('name')
                            ->label('Name')
                            ->defaultSort('asc')
                                            ->hideable()
                            ->filterable(),
                                                                    
                
                                                                                Column::callback(['profile_photo_path'], function ($profile_photo_path) {
                            return view('zekini/livewire-crud-generator::datatable.image-display', ['file' => $profile_photo_path]);
                        })->unsortable()->excludeFromExport(),
                                                                    
                
                                    DateColumn::name('two_factor_confirmed_at')
                    ->label('Two_factor_confirmed_at')
                    ->filterable()
                    ->hide(),
                                                
                
                                                        
                        Column::name('updated_by')
                            ->label('Updated_by')
                            ->defaultSort('asc')
                                            ->hideable()
                            ->filterable(),
                                                    
            // belongs to many relationship tables
                            Column::name('roles.name')
                ->label('role')
            ,
            
            // Non pivot belongs to relationships
            
                        Column::callback(['id'], function ($id) {
                return view('zekini/livewire-crud-generator::datatable.table-actions', [
                    'id' => $id, 
                    'view' => 'user',
                    'model'=> 'user',
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
        $this->authorize('admin.user.delete');

        $user = User::withTrashed()->find($id);

        $fileCols = $this->checkForFiles($user);
        foreach($fileCols as $files){
            $this->deleteFile($files);
        }

        $user->forceDelete();

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
        $this->authorize('admin.user.delete');

        $user = User::find($id);

        $fileCols = $this->checkForFiles($user);
        foreach($fileCols as $files){
            $this->deleteFile($files);
        }

        $user->delete();

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
        $this->authorize('admin.user.delete');

        $user = User::withTrashed()->find($id);

        $fileCols = $this->checkForFiles($user);
        foreach($fileCols as $files){
            $this->deleteFile($files);
        }

        $user->restore();

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

    public function launchUserEditModal(User $User)
    {
        $this->emit('launchUserEditModal', $User);
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
        $filename = 'user.xlsx';

        if (!Storage::disk('templates')->exists($filename)) {
            $this->emit('flashMessageEvent', "Failed to find template $filename");

            return;
        }

        return response()->download(storage_path('app/public/templates/' . $filename));
    }

    public function updatedFile()
    {
        $filename = $this->file->store('imports');

        Excel::import(new UsersImport($this->file), $filename);

        $this->emit('flashMessageEvent', 'Imported');
        $this->emit('refreshLivewireDatatable');
    }
}
