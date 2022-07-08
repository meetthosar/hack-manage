<div>

     


    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
           
       
            
            <livewire:activity-logs.datatable.activity-logs-table />

        </div>



        <x-jet-dialog-modal wire:model="activityLogCreateModal">
            <x-slot name='title'>

                <div class="flex items-start justify-between p-5 border-b border-solid border-gray-200 rounded-t">

                    <h1 class="text-grey-200 font-semibold">
                        Create ActivityLogs
                    </h1>
                    
                </div>

            </x-slot>

            <x-slot name='content'>

                <div class="flex col-span-12 gap-4 mt-4">

                    <form wire:submit.prevent="submit" class="w-full">
                        <!--body-->
                        <div class="relative p-6 flex-auto">

                            @include('livewire.activity-logs.partials.form')

                        </div>
                        

                        <!--footer-->
                        <div class="flex items-center justify-end p-6 border-t border-solid border-gray-200 rounded-b">
                            <button type="submit" class="text-black-500 background-transparent font-bold uppercase px-6 py-2 text-sm outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150" type="button">
                                Create
                            </button>
                            <button wire:click="$toggle('activityLogCreateModal')" class="text-black-500 background-transparent font-bold uppercase px-6 py-2 text-sm outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150" type="button">
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
        <x-jet-dialog-modal wire:model="activityLogEditModal">

            <x-slot name='title'>

                <div class="flex items-start justify-between p-5 border-b border-solid border-gray-200 rounded-t">

                    <h1 class="text-grey-200 font-semibold">
                        Edit activityLog
                    </h1>
                    
                </div>

            </x-slot>

            <x-slot name='content'>

                <div class="flex col-span-12 gap-4 mt-4">

                    <form wire:submit.prevent="editSubmit" class="w-full">
                        <!--body-->
                        <div class="relative p-6 flex-auto">

                            @include('livewire.activity-logs.partials.form')

                        </div>

                        <!--footer-->
                        <div class="flex items-center justify-end p-6 border-t border-solid border-gray-200 rounded-b">
                            <button type="submit" class="text-black-500 background-transparent font-bold uppercase px-6 py-2 text-sm outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150" type="button">
                                Update
                            </button>
                            <button wire:click="$toggle('activityLogEditModal')" class="text-black-500 background-transparent font-bold uppercase px-6 py-2 text-sm outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150" type="button">
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