
<div class="form-group row align-items-center">
    <label for="name" class="col-form-label text-md-right col-md-3">Name</label>
    <div class="col-xl-8">
        <input class="form-control col-md-9" id="name" wire:model="state.name" type="text" name="name">
        @error('state.name') <span> {{ $message }} </span> @enderror
    </div>
</div>

<div class="form-group row align-items-center">
    <label for="description" class="col-form-label text-md-right col-md-3">Description</label>
    <div class="col-md-9 col-xl-8">
        <div>
            <textarea class="form-control col-md-9" wire:model="state.description" id="description" name="description"></textarea>
            @error('state.description') <span> {{ $message }} </span> @enderror
        </div>
    </div>
</div>



