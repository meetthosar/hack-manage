<div>

     


    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
           
       
            
            <livewire:roles.datatable.roles-table />

        </div>



        <x-jet-dialog-modal wire:model="roleCreateModal">
            <x-slot name='title'>

                <div class="flex items-start justify-between p-5 border-b border-solid border-gray-200 rounded-t">

                    <h1 class="text-grey-200 font-semibold">
                        Create Roles
                    </h1>
                    
                </div>

            </x-slot>

            <x-slot name='content'>

                <div class="flex col-span-12 gap-4 mt-4">

                    <form wire:submit.prevent="submit" class="w-full">
                        <!--body-->
                        <div class="relative p-6 flex-auto">

                            @include('livewire.roles.partials.form')

                        </div>
                        

                        <!--footer-->
                        <div class="flex items-center justify-end p-6 border-t border-solid border-gray-200 rounded-b">
                            <button type="submit" class="text-black-500 background-transparent font-bold uppercase px-6 py-2 text-sm outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150" type="button">
                                Create
                            </button>
                            <button wire:click="$toggle('roleCreateModal')" class="text-black-500 background-transparent font-bold uppercase px-6 py-2 text-sm outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150" type="button">
                                Close
                            </button>

                        </div>
                    </form>

                </div>

            </x-slot>

            <x-slot name='footer'>

            </x-slot>

        </x-jet-dialog-modal>


        <!-- Edit section -->
        <x-jet-dialog-modal wire:model="roleEditModal">

            <x-slot name='title'>

                <div class="flex items-start justify-between p-5 border-b border-solid border-gray-200 rounded-t">

                    <h1 class="text-grey-200 font-semibold">
                        Edit role
                    </h1>
                    
                </div>

            </x-slot>

            <x-slot name='content'>

                <div class="flex col-span-12 gap-4 mt-4">

                    <form wire:submit.prevent="editSubmit" class="w-full">
                        <!--body-->
                        <div class="relative p-6 flex-auto">

                            @include('livewire.roles.partials.form')

                        </div>

                        <!--footer-->
                        <div class="flex items-center justify-end p-6 border-t border-solid border-gray-200 rounded-b">
                            <button type="submit" class="text-black-500 background-transparent font-bold uppercase px-6 py-2 text-sm outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150" type="button">
                                Update
                            </button>
                            <button wire:click="$toggle('roleEditModal')" class="text-black-500 background-transparent font-bold uppercase px-6 py-2 text-sm outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150" type="button">
                                Close
                            </button>

                        </div>
                    </form>

                </div>

            </x-slot>

            <x-slot name='footer'>

            </x-slot>

        </x-jet-dialog-modal>

      



    </div>
</div>