<?php
namespace App\Http\Livewire\Permissions;

use App\Models\Permission;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Zekini\CrudGenerator\Traits\HandlesFile;
use Zekini\Generics\Helpers\FlashHelper;


class Permissions extends Component
{
        use WithFileUploads;
    use HandlesFile;
    use AuthorizesRequests;

    public Permission $permission;

    public $state;

    public $permissionCreateModal = false;

    public $permissionEditModal = false;

    protected $listeners = [
        'launchPermissionCreateModal',
        'launchPermissionEditModal',
        'flashMessageEvent' => 'flashMessageEvent'
    ];

    public function mount()
    {
       $this->state = [];
    }

    public function render()
    {
        return view('livewire.permissions.index')
            ->extends('zekini/livewire-crud-generator::admin.layout.default')
            ->section('body');
    }

        public function submit()
    {
        //access control
        // $this->authorize('admin.permission.create');

        $this->validate();

        $this->create($this->state);

        $this->flashMessageEvent('Item successfully created');

        $this->emit('refreshLivewireDatatable');

        $this->resetState();

        $this->closeModalButton();
    }

    public function editSubmit()
    {
        //access control
        $this->authorize('admin.permission.edit');

        $this->validate();

        $this->update($this->state, $this->state['id']);

        $this->flashMessageEvent('Item successfully updated');

        $this->emit('refreshLivewireDatatable');

        $this->resetState();

        $this->closeModalButton();
    }

    public function flashMessageEvent($message)
    {
        FlashHelper::success($message);
    }

    public function closeModalButton()
    {
        $this->permissionCreateModal = false;
        $this->permissionEditModal = false;
    }

    public function launchPermissionCreateModal()
    {
        $this->permissionCreateModal = true;
    }

    public function launchPermissionEditModal(Permission $permission)
    {
        $this->state = $permission->toArray();
                $this->state['roles'] = $permission->roles()->allRelatedIds()->toArray();
                $this->permissionEditModal = true;
    }

    protected function rules()
    {
        return [
                                    'state.guard_name' => 'required',
                                                'state.name' => 'required',
                                ];
    }

    protected function resetState(): void
    {
        $this->state = [];
    }

    private function create($data): void
    {
        // image processing

        $model = Permission::create($data);
                        $model->roles()->sync($this->state['roles']);
                    }

    private function update($data, $id): void
    {
        $model = Permission::findOrFail($id);

        //image processing

        $model->update($data);
                        $model->roles()->sync($this->state['roles']);

            }
    }
