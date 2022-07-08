<?php
namespace App\Http\Livewire\Roles;

use App\Models\Role;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Zekini\CrudGenerator\Traits\HandlesFile;
use Zekini\Generics\Helpers\FlashHelper;


class Roles extends Component
{
        use WithFileUploads;
    use HandlesFile;
    use AuthorizesRequests;

    public Role $role;

    public $state;

    public $roleCreateModal = false;

    public $roleEditModal = false;

    protected $listeners = [
        'launchRoleCreateModal',
        'launchRoleEditModal',
        'flashMessageEvent' => 'flashMessageEvent'
    ];

    public function mount()
    {
       $this->state = [];
    }
    
    public function render()
    {
        return view('livewire.roles.index')
            ->extends('zekini/livewire-crud-generator::admin.layout.default')
            ->section('body');
    }

        public function submit()
    {
        //access control
        $this->authorize('admin.role.create');

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
        $this->authorize('admin.role.edit');

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
        $this->roleCreateModal = false;
        $this->roleEditModal = false;
    }

    public function launchRoleCreateModal()
    {
        $this->roleCreateModal = true;
    }

    public function launchRoleEditModal(Role $role)
    {
        $this->state = $role->toArray();
                $this->state['permissions'] = $role->permissions()->allRelatedIds()->toArray();
                $this->roleEditModal = true;
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
        
        $model = Role::create($data);
                        $model->permissions()->sync($this->state['permissions']);
                    }

    private function update($data, $id): void
    {
        $model = Role::findOrFail($id);

        //image processing
              
        $model->update($data);
                        $model->permissions()->sync($this->state['permissions']);
                
            }
    }
