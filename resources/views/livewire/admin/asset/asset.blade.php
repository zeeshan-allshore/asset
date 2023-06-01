@section('title', __('Assets'))
<div x-data="{ toggleCreate: false }">
    @include('errors.success')
    <div>
        <div class="flow-root ">
            <h1 class="float-left">{{ __('Assets') }}</h1>
            <x-button wire:click="resetAsset" @click="toggleCreate = !toggleCreate" class="float-right">
                {{ __('Create Asset') }}</x-button>
        </div>

        <div x-cloak x-show="toggleCreate" x-transition.otigin.top.right class="card">
            <h3 class="mb-4"> {{ $editing ? __('Update') : __('Create') }} {{ __(' Asset') }}</h3>
            <x-form wire:submit.prevent="{{ $editing ? 'update' : 'create' }}" method="POST">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-form.input wire:model="asset.tag" name="asset.tag" :label="__('Asset Tag')" />
                    <x-form.input wire:model="asset.name" name="asset.name" :label="__('Asset Name')" />
                    <x-form.select id="asset.center_id" name="asset.center_id" :label="__('Center')"
                        wire:model="asset.center_id">
                        <option value="">Select</option>
                        @foreach ($centers as $center)
                            <option value="{{ $center->id }}">{{ $center->name }}</option>
                        @endforeach
                    </x-form.select>
                    <x-form.select id="asset.location_id" name="asset.location_id" :label="__('Location')"
                        wire:model="asset.location_id">
                        <option value="">Select</option>
                        @if (isset($locations))
                            @foreach ($locations as $location)
                                <option value="{{ $location->id }}">{{ $location->name }}</option>
                            @endforeach
                        @endif
                    </x-form.select>
                    <x-form.select id="asset.category_id" name="asset.category_id" :label="__('Category')"
                        wire:model="asset.category_id">
                        <option value="">Select</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </x-form.select>
                    <x-form.input wire:model="asset.weight" name="asset.weight" type="number" min="0"
                        step="1" :label="__('Weight (KG)')" />
                    <x-form.input wire:model="asset.height" name="asset.height" type="number" min="0"
                        step="1" max="99999" :label="__('Height (cm)')" />
                    <x-form.checkbox wire:model="asset.is_active" name="asset.is_active" value="1"> </x-form.checkbox>
                </div>
                <div class="mt-5">

                    <x-button>{{ $editing ? __('Update') : __('Create') }}</x-button>
                    <x-button type="button" wire:click="resetAsset" class="ml-2 absolute">
                        <svg class="h-5 w-5 text-gray-500 dark:text-gray-200" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg> {{ __('Reset form') }}
                    </x-button>
                    @include('errors.messages')
                </div>
            </x-form>
        </div>
    </div>

    <div class="overflow-x-scroll shadow-md">
        <table class="mt-5">
            <thead>
                <tr>
                    <th><a href="#" wire:click.prevent="sortBy('tag')">{{ __('Tag') }}</a></th>
                    <th><a href="#" wire:click.prevent="sortBy('name')">{{ __('Name') }}</a></th>
                    <th><a href="#" wire:click.prevent="sortBy('category')">{{ __('Category') }}</a></th>
                    <th><a href="#" wire:click.prevent="sortBy('location')">{{ __('Location') }}</a></th>
                    <th><a href="#" wire:click.prevent="sortBy('is_active')">{{ __('Status') }}</a></th>
                    <th><a href="#" wire:click.prevent="sortBy('created_at')">{{ __('Created at') }}</a></th>
                    <th><a href="#" wire:click.prevent="sortBy('updated_at')">{{ __('Updated at') }}</a></th>
                    <th>{{ __('Action') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($assets as $asset)
                    <tr>
                        <td>{{ $asset->tag }}</td>
                        <td>{{ $asset->name }}</td>
                        <td>{{ $asset->category->name }}</td>
                        <td>{{ $asset->location->name }}</td>
                        <td>{{ $asset->is_active ? 'Active' : 'Inactive' }}</td>
                        <td>{{ $asset->created_at }}</td>
                        <td>{{ $asset->updated_at }}</td>
                        <td class="flex space-x-2">
                            @can('edit_users')
                                <a href="#" wire:click="edit({{ $asset->id }})" @click="toggleCreate = true">
                                    {{ __('Edit') }}</a>
                                {{-- <a href="{{ route('admin.users.show', $asset) }}">{{ __('Profile') }}</a> --}}
                            @endcan
                            @if (can('delete_users'))
                                <x-modal>
                                    <x-slot name="trigger">
                                        <a href="#" @click="on = true">{{ __('Delete') }}</a>
                                    </x-slot>

                                    <x-slot name="title">{{ __('Confirm Delete') }}</x-slot>

                                    <x-slot name="content">
                                        <div class="text-asset">
                                            {{ __('Are you sure you want to delete') }}: <b>{{ $asset->name }}</b>
                                        </div>
                                    </x-slot>

                                    <x-slot name="footer">
                                        <button @click="on = false">{{ __('Cancel') }}</button>
                                        <button class="btn btn-red"
                                            wire:click="delete('{{ $asset->id }}')">{{ __('Delete Asset') }}</button>
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
