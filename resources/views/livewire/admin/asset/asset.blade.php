@section('title', __('Assets'))

<div x-data="{ toggleCreate: '{{ $editing }}', toggleUpload: false }">
    <div class="mb-5" x-data="{ isOpen: @if ($openFilter || request('openFilter')) true @else false @endif }">

        <button type="button" @click="isOpen = !isOpen"
            class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs leading-4 font-medium rounded-t text-grey-700 bg-gray-200 hover:bg-grey-300 dark:bg-gray-700 dark:text-gray-200 transition ease-in-out duration-150">
            <svg class="h-5 w-5 text-gray-500 dark:text-gray-200" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            {{ __('Advanced Search') }}
        </button>

        <button type="button" wire:click="resetFilters" @click="isOpen = false"
            class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs leading-4 font-medium rounded text-grey-700 bg-gray-200 hover:bg-grey-300 dark:bg-gray-700 dark:text-gray-200 transition ease-in-out duration-150">
            <svg class="h-5 w-5 text-gray-500 dark:text-gray-200" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            {{ __('Reset form') }}
        </button>

        <div x-show="isOpen" x-transition:enter="transition ease-out duration-100 transform"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75 transform"
            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
            class="bg-gray-200 dark:bg-gray-700 rounded-b-md p-5" wire:ignore.self>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                <x-form.input type="tag" id="tag" name="tag" :label="__('Tag')" wire:model="tag" />
                <x-form.input type="name" id="name" name="name" :label="__('Name')" wire:model="name" />
                <x-form.select id="category_id" name="category_id" :label="__('Category')" wire:model="category_id">
                    <option value="">Select</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </x-form.select>
                <x-form.checkbox wire:model="is_active" name="is_active" value="1"></x-form.checkbox>
            </div>
        </div>

    </div>

    @include('errors.success')
    <div id="scan"></div>
    <div>
        <div class="flow-root ">
            <h1 class="float-left">{{ __('Assets') }}</h1>
            <div class="float-right">
                <x-button wire:click="resetAsset" @click="toggleCreate = !toggleCreate">
                    {{ __('Create Asset') }}</x-button>

                <x-button class="ml-2" @click="toggleUpload = !toggleUpload">
                    {{ __('Import Data') }}</x-button>

                <x-button @click="openScanner" class="ml-2">
                    {{ __('Scan Barcode') }}</x-button>

            </div>
        </div>

        <div x-cloak x-show="toggleUpload" x-transition.otigin.top.right class="card">
            <h3 class="mb-4"> {{ __('Import Assets') }}</h3>



            <form wire:submit.prevent="import" enctype="multipart/form-data">
                <input type="file" wire:model="file">
                @error('file')
                    <span class="error">{{ $message }}</span>
                @enderror

                <button type="submit">Import</button>
            </form>
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
                    <x-form.checkbox wire:model="asset.is_active" name="asset.is_active" value="1">
                    </x-form.checkbox>
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
                @foreach ($this->assetss() as $asset)
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
    {{ $this->assetss()->links() }}
</div>


@push('scripts')
    <script>
        // import Quagga from 'quagga';
        // Function to open the barcode scanner

        function openScanner() {
            console.log('openScanner:', Quagga)
            // Code to open the barcode scanner and initialize QuaggaJS
            // You can use the QuaggaJS API and configuration options here

            Quagga.init({
                inputStream: {
                    name: "Live",
                    type: "LiveStream",
                    target: document.querySelector('#scan') // Or '#yourElement' (optional)
                    // area: { // defines rectangle of the detection/localization area
                    //     top: "0%", // top offset
                    //     right: "0%", // right offset
                    //     left: "0%", // left offset
                    //     bottom: "0%" // bottom offset
                    // },
                    // singleChannel: false // true: only the red color-channel is read
                },
                decoder: {
                    readers: ["ean_reader", "ean_8_reader"],
                    debug: {
                        drawBoundingBox: true,
                        showFrequency: false,
                        drawScanline: true,
                        showPattern: true
                    },
                    multiple: false
                }
            }, function(err) {
                if (err) {
                    console.log(err);
                    return
                }
                console.log("Initialization finished. Ready to start");
                Quagga.start();
            });


            // Quagga.init({
            //     inputStream: {
            //         type: 'LiveStream',
            //         constraints: {
            //             width: 640,
            //             height: 480,
            //             facingMode: 'environment' // Use 'environment' for rear camera, 'user' for front camera
            //         }
            //     },
            //     decoder: {
            //         readers: ['ean_reader'] // Specify the barcode reader(s) you want to use
            //     }
            // }, function(err) {
            //     if (err) {
            //         console.error(err);
            //         return;
            //     }
            //     console.log('QuaggaJS initialization succeeded.');

            //     // Start the scanner
            //     Quagga.start();
            // });

            // Event listener for barcode detection
            Quagga.onDetected(function(result) {
                console.log('result:', result)
                if (result && result.codeResult && result.codeResult.code && result.codeResult.format ===
                    'ean_13') {
                    let scannedBarcode = result.codeResult.code;
                    console.log('scannedBarcode:', scannedBarcode)
                    // Quagga.stop();
                    Livewire.emit('barcodeScanned', scannedBarcode);
                }
            });
        }

        // Function to close the barcode scanner
        function closeScanner() {
            // Code to stop the barcode scanner and clean up any resources
            Quagga.stop();
        }

        // Attach event listener to the scan button click event
        document.querySelector('button[data-wire-click="scanBarcode"]').addEventListener('click', function() {
            openScanner();
        });
    </script>
@endpush
