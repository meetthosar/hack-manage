<div class="form-group row align-items-center">
    <label for="batch_uuid" class="col-form-label text-md-right col-md-3">Batch uuid</label>
    <div class="col-xl-8">
        <input class="form-control col-md-9" id="batch_uuid" wire:model="state.batch_uuid" type="text" name="batch_uuid">
        @error('state.batch_uuid') <span> {{ $message }} </span> @enderror
    </div>
</div>

<div class="form-group row align-items-center">
    <label for="causer_id" class="col-form-label text-md-right col-md-3">Causer id</label>
    <div class="col-xl-8">
        <input class="form-control col-md-9" id="causer_id" wire:model="state.causer_id" type="text" name="causer_id">
        @error('state.causer_id') <span> {{ $message }} </span> @enderror
    </div>
</div>

<div class="form-group row align-items-center">
    <label for="causer_type" class="col-form-label text-md-right col-md-3">Causer type</label>
    <div class="col-xl-8">
        <input class="form-control col-md-9" id="causer_type" wire:model="state.causer_type" type="text" name="causer_type">
        @error('state.causer_type') <span> {{ $message }} </span> @enderror
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

<div class="form-group row align-items-center">
    <label for="event" class="col-form-label text-md-right col-md-3">Event</label>
    <div class="col-xl-8">
        <input class="form-control col-md-9" id="event" wire:model="state.event" type="text" name="event">
        @error('state.event') <span> {{ $message }} </span> @enderror
    </div>
</div>

<div class="form-group row align-items-center">
    <label for="log_name" class="col-form-label text-md-right col-md-3">Log name</label>
    <div class="col-xl-8">
        <input class="form-control col-md-9" id="log_name" wire:model="state.log_name" type="text" name="log_name">
        @error('state.log_name') <span> {{ $message }} </span> @enderror
    </div>
</div>

<div class="form-group row align-items-center">
    <label for="properties" class="col-form-label text-md-right col-md-3">Properties</label>
    <div class="col-xl-8">
        <input class="form-control col-md-9" id="properties" wire:model="state.properties" type="text" name="properties">
        @error('state.properties') <span> {{ $message }} </span> @enderror
    </div>
</div>

<div class="form-group row align-items-center">
    <label for="subject_id" class="col-form-label text-md-right col-md-3">Subject id</label>
    <div class="col-xl-8">
        <input class="form-control col-md-9" id="subject_id" wire:model="state.subject_id" type="text" name="subject_id">
        @error('state.subject_id') <span> {{ $message }} </span> @enderror
    </div>
</div>

<div class="form-group row align-items-center">
    <label for="subject_type" class="col-form-label text-md-right col-md-3">Subject type</label>
    <div class="col-xl-8">
        <input class="form-control col-md-9" id="subject_type" wire:model="state.subject_type" type="text" name="subject_type">
        @error('state.subject_type') <span> {{ $message }} </span> @enderror
    </div>
</div>


