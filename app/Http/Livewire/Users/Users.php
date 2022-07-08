<?php
namespace App\Http\Livewire\Users;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Zekini\CrudGenerator\Traits\HandlesFile;
use Zekini\Generics\Helpers\FlashHelper;


class Users extends Component
{
        use WithFileUploads;
    use HandlesFile;
    use AuthorizesRequests;

    public User $user;

    public $state;

    public $userCreateModal = false;

    public $userEditModal = false;

    protected $listeners = [
        'launchUserCreateModal',
        'launchUserEditModal',
        'flashMessageEvent' => 'flashMessageEvent'
    ];

    public function mount()
    {
       $this->state = [];
    }
    
    public function render()
    {
        return view('livewire.users.index')
            ->extends('zekini/livewire-crud-generator::admin.layout.default')
            ->section('body');
    }

        public function submit()
    {
        //access control
        $this->authorize('admin.user.create');

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
        $this->authorize('admin.user.edit');

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
        $this->userCreateModal = false;
        $this->userEditModal = false;
    }

    public function launchUserCreateModal()
    {
        $this->userCreateModal = true;
    }

    public function launchUserEditModal(User $user)
    {
        $this->state = $user->toArray();
                $this->state['roles'] = $user->roles()->allRelatedIds()->toArray();
                $this->userEditModal = true;
    }

    protected function rules()
    {
        return [
                                    'state.created_by' => 'required',
                                                'state.current_team_id' => 'required',
                                                'state.deleted_by' => 'required',
                                                'state.email' => 'required|email:rfc|min:3|max:255|unique:users,email,' . @$this->state['id'],
                                                'state.name' => 'required|min:3|max:255|unique:users,name,' . @$this->state['id'],
                                                'state.profile_photo_path' => 'required',
                                                'state.two_factor_confirmed_at' => 'required',
                                                'state.updated_by' => 'required',
                                ];
    }

    protected function resetState(): void
    {
        $this->state = [];
    }

    private function create($data): void
    {
        // image processing
        
        $model = User::create($data);
                        $model->roles()->syncWithPivotValues($this->state['roles'], [
            'model_type' => 'App\Models\User'
        ]);
                    }

    private function update($data, $id): void
    {
        $model = User::findOrFail($id);

        //image processing
              
        $model->update($data);
                        $model->roles()->syncWithPivotValues($this->state['roles'], [
            'model_type' => 'App\Models\User'
        ]);
                
            }
    }
