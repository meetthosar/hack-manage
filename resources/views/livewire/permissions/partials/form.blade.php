<div class="form-group row align-items-center">
    <label for="guard_name" class="col-form-label text-md-right col-md-3">Guard name</label>
    <div class="col-xl-8">
        <input class="form-control col-md-9" id="guard_name" wire:model="state.guard_name" type="text" name="guard_name">
        @error('state.guard_name') <span> {{ $message }} </span> @enderror
    </div>
</div>

<div class="form-group row align-items-center">
    <label for="name" class="col-form-label text-md-right col-md-3">Name</label>
    <div class="col-xl-8">
        <input class="form-control col-md-9" id="name" wire:model="state.name" type="text" name="name">
        @error('state.name') <span> {{ $message }} </span> @enderror
    </div>
</div>


<div class="form-group row align-items-center">

    <label for="roles" class="col-form-label text-md-right col-md-3">Roles</label>
    <div class="col-xl-8">
        <select class="form-control col-md-9" wire:model="state.roles" id="roles" name="roles" multiple>
            @foreach(\App\Models\Role::all() as $item)
            <option wire:key="{{ $item->id }}" value="{{ $item->id }}"> {{ $item->name }}</option>
            @endforeach
        </select>
        @error('state.roles') <span> {{ $message }} </span> @enderror
    </div>
</div>
