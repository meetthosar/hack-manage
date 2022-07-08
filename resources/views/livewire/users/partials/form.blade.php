<div class="form-group row align-items-center">
    <label for="created_by" class="col-form-label text-md-right col-md-3">Created by</label>
    <div class="col-xl-8">
        <input class="form-control col-md-9" id="created_by" wire:model="state.created_by" type="text" name="created_by">
        @error('state.created_by') <span> {{ $message }} </span> @enderror
    </div>
</div>

<div class="form-group row align-items-center">
    <label for="current_team_id" class="col-form-label text-md-right col-md-3">Current team id</label>
    <div class="col-xl-8">
        <input class="form-control col-md-9" id="current_team_id" wire:model="state.current_team_id" type="text" name="current_team_id">
        @error('state.current_team_id') <span> {{ $message }} </span> @enderror
    </div>
</div>

<div class="form-group row align-items-center">
    <label for="deleted_by" class="col-form-label text-md-right col-md-3">Deleted by</label>
    <div class="col-xl-8">
        <input class="form-control col-md-9" id="deleted_by" wire:model="state.deleted_by" type="text" name="deleted_by">
        @error('state.deleted_by') <span> {{ $message }} </span> @enderror
    </div>
</div>

<div class="form-group row align-items-center">
    <label for="email" class="col-form-label text-md-right col-md-3">Email</label>
    <div class="col-xl-8">
        <input class="form-control col-md-9" id="email" wire:model="state.email" type="text" name="email">
        @error('state.email') <span> {{ $message }} </span> @enderror
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
    <label for="profile_photo_path" class="col-form-label text-md-right col-md-3">Profile photo path</label>
    <div class="col-xl-8">
        <input class="form-control col-md-9" id="profile_photo_path" wire:model="state.profile_photo_path" type="text" name="profile_photo_path">
        @error('state.profile_photo_path') <span> {{ $message }} </span> @enderror
    </div>
</div>
<div class="form-group row align-items-center">
    <label for="two_factor_confirmed_at" class="col-form-label text-md-right col-md-3">Two factor confirmed at</label>
    <div class="col-xl-8 col-md-9">
        <input id="two_factor_confirmed_at" type="datetime-local" wire:model="state.two_factor_confirmed_at" name="two_factor_confirmed_at">
        @error('state.two_factor_confirmed_at') <span> {{ $message }} </span> @enderror
    </div>
</div>

<div class="form-group row align-items-center">
    <label for="updated_by" class="col-form-label text-md-right col-md-3">Updated by</label>
    <div class="col-xl-8">
        <input class="form-control col-md-9" id="updated_by" wire:model="state.updated_by" type="text" name="updated_by">
        @error('state.updated_by') <span> {{ $message }} </span> @enderror
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
