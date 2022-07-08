<?php
namespace App\Http\Livewire\Hackthons;

use App\Models\Hackthon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Zekini\CrudGenerator\Traits\HandlesFile;
use Zekini\Generics\Helpers\FlashHelper;


class Hackthons extends Component
{
    use WithFileUploads;
    use HandlesFile;
    use AuthorizesRequests;

    public Hackthon $hackthon;

    public $state;

    public $hackthonCreateModal = false;

    public $hackthonEditModal = false;

    protected $listeners = [
        'launchHackthonCreateModal',
        'launchHackthonEditModal',
        'flashMessageEvent' => 'flashMessageEvent'
    ];

    public function mount()
    {
       $this->state = [];
    }

    public function render()
    {
        return view('livewire.hackthons.index')
            ->extends('zekini/livewire-crud-generator::admin.layout.default')
            ->section('body');
    }

        public function submit()
    {
        //access control
        // $this->authorize('admin.hackthon.create');

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
        // $this->authorize('admin.hackthon.edit');

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
        $this->hackthonCreateModal = false;
        $this->hackthonEditModal = false;
    }

    public function launchHackthonCreateModal()
    {
        $this->hackthonCreateModal = true;
    }

    public function launchHackthonEditModal(Hackthon $hackthon)
    {
        $this->state = $hackthon->toArray();
                $this->hackthonEditModal = true;
    }

    protected function rules()
    {
        return [
            'state.description' => 'required',
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

        $model = Hackthon::create($data);
            }

    private function update($data, $id): void
    {
        $model = Hackthon::findOrFail($id);

        //image processing

        $model->update($data);
            }
    }
