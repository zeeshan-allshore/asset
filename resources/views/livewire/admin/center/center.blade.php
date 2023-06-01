@section('title', __('Centers'))
<div x-data="{ toggleCreate: false }">
    <div>
        <div class="flow-root ">
            <h1 class="float-left">{{ __('Centers') }}</h1>
            <x-button wire:click="resetCenter" @click="toggleCreate = !toggleCreate" class="float-right">
                {{ __('Create Center') }}</x-button>
        </div>

        <div x-cloak x-show="toggleCreate" x-transition.otigin.top.right class="card">
            <h3 class="mb-4"> {{ $editing ? __('Update') : __('Create') }} {{ __(' Center') }}</h3>
            <x-form wire:submit.prevent="{{ $editing ? 'update' : 'create' }}" method="POST">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-form.input wire:model="center.name" name="center" :label="__('Center Name')" />
                </div>
                <div>

                    <x-button>{{ $editing ? __('Update') : __('Create') }}</x-button>
                    <x-button type="button" wire:click="resetCenter" class="ml-2 absolute">
                        <svg class="h-5 w-5 text-gray-500 dark:text-gray-200" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg> {{ __('Reset form') }}
                    </x-button>
                </div>
            </x-form>
        </div>
    </div>

    <div class="overflow-x-scroll shadow-md">
        <table class="mt-5">
            <thead>
                <tr>
                    <th><a href="#" wire:click.prevent="sortBy('name')">{{ __('Name') }}</a></th>
                    <th><a href="#" wire:click.prevent="sortBy('created_at')">{{ __('Created at') }}</a></th>
                    <th><a href="#" wire:click.prevent="sortBy('updated_at')">{{ __('Updated at') }}</a></th>
                    <th>{{ __('Action') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($centers as $center)
                    <tr>
                        <td>{{ $center->name }}</td>
                        <td>{{ $center->created_at }}</td>
                        <td>{{ $center->updated_at }}</td>
                        <td class="flex space-x-2">
                            @can('edit_users')
                                <a href="#" wire:click="edit({{ $center->id }})" @click="toggleCreate = true">
                                    {{ __('Edit') }}</a>
                                {{-- <a href="{{ route('admin.users.show', $center) }}">{{ __('Profile') }}</a> --}}
                            @endcan
                            @if (can('delete_users'))
                                <x-modal>
                                    <x-slot name="trigger">
                                        <a href="#" @click="on = true">{{ __('Delete') }}</a>
                                    </x-slot>

                                    <x-slot name="title">{{ __('Confirm Delete') }}</x-slot>

                                    <x-slot name="content">
                                        <div class="text-center">
                                            {{ __('Are you sure you want to delete') }}: <b>{{ $center->name }}</b>
                                        </div>
                                    </x-slot>

                                    <x-slot name="footer">
                                        <button @click="on = false">{{ __('Cancel') }}</button>
                                        <button class="btn btn-red"
                                            wire:click="delete('{{ $center->id }}')">{{ __('Delete Center') }}</button>
                                    </x-slot>
                                </x-modal>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- {{ $this->users()->links() }} --}}
</div>
