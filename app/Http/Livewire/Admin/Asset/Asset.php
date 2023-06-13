<?php

namespace App\Http\Livewire\Admin\Asset;

use App\Models\Asset as ModelsAsset;
use App\Models\Category;
use App\Models\Center;
use App\Models\Location;
use Illuminate\Support\Facades\DB;
use Livewire\WithFileUploads;
use Livewire\Component;
use Livewire\WithPagination;

class Asset extends Component
{
    use WithFileUploads, WithPagination;

    public $tag, $name, $category_id, $is_active,
        $file, $assets, $asset, $categories,
        $centers, $locations, $editing = false, $scannedBarcode,
        $paginate = '', $openFilter = false, $sortField = 'name',
        $sortAsc = true;

    public $listeners = ["barcodeScanned"];

    protected $rules = [
        'file' => 'required|file|mimes:csv,txt',
    ];
    // protected $rules = [
    //     'asset.tag'         => 'required|string|max:255',
    //     'asset.name'        => 'required|string|max:255',
    //     'asset.height'      => 'required',
    //     'asset.weight'      => 'required',
    //     'asset.center_id'   => 'present|exists:centers,id',
    //     'asset.location_id' => 'required|exists:locations,id',
    //     'asset.category_id' => 'required|exists:categories,id',
    //     'asset.is_active'   => 'present',
    // ];

    public function builder()
    {
        return ModelsAsset::with('category', 'location')->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc');

        // return User::with('roles')->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc');
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortAsc = !$this->sortAsc;
        } else {
            $this->sortAsc = true;
        }

        $this->sortField = $field;
    }

    public function assetss()
    {
        $query = $this->builder();

        if ($this->tag) {
            $this->openFilter = true;
            $query->where('tag', 'like', '%' . $this->tag . '%');
        }

        if ($this->name) {
            $this->openFilter = true;
            $query->where('name', 'like', '%' . $this->name . '%');
        }

        if ($this->category_id) {
            $this->openFilter = true;
            $query->where('category_id', $this->category_id);
        }
        if ($this->is_active) {
            $this->openFilter = true;
            $query->where('is_active', $this->is_active);
        }

        return $query->paginate($this->paginate);
    }

    public function resetFilters(): void
    {
        // dd("test");
        // $this->reset();
        $this->tag = '';
        $this->name = '';
        $this->category_id = '';
        $this->is_active = '';
        // $this->getCategories();
        // $this->assetss();
    }

    public function mount()
    {
        // $this->assets = ModelsAsset::with('category', 'location')->paginate(10);
        $this->centers = Center::with('locations')->get();

        // dd($this->centers);
        // $this->locations = Location::with('center')->get();
        // $this->categories = Category::all();
        $this->getCategories();
    }
    //
    public function barcodeScanned($scannedBarcode)
    {
        $this->assets = ModelsAsset::where('tag', $scannedBarcode)->with('category', 'location')->get();

        if (count($this->assets) < 1) {
            $this->asset = new ModelsAsset();

            $this->centers = Center::with('locations')->get();

            $this->getCategories();

            // $this->categories = Category::all();

            $this->asset->tag = $scannedBarcode;

            $this->editing = true;
            // dd($this->asset);
        }

        // dd("barcode", $scannedBarcode);
    }

    public function resetAsset()
    {
        $this->asset = new ModelsAsset();
        $this->editing = false;
    }

    public function updatedAssetCenterId($centerId)
    {
        $this->asset->location_id = '';

        $center = $this->centers->firstWhere('id', $centerId);

        $this->locations = $center ? $center->locations : collect([]);

        // $this->locations = Location::where('center_id', $centerId)->get();
    }

    public function create()
    {
        $this->validate();

        $this->asset->is_active = $this->asset->is_active ? '1' : '0';

        unset($this->asset->center_id);

        $this->asset->save();

        $this->assetss();
        // $this->assets = ModelsAsset::with('category', 'location')->get();

        $this->resetAsset();
    }

    public function edit($assetId)
    {
        $this->asset = ModelsAsset::findOrFail($assetId);

        $center = Center::where('id', $this->asset->location->center_id)->with('locations')->first();
        $this->asset->center_id = $center->id;
        // dd($this->centers);
        $this->locations = $center ? $center->locations : collect([]);

        // dd($this->asset);
        // dd($this->locations);

        $this->editing = true;
    }

    public function update()
    {
        $this->validate();

        $this->asset->is_active = $this->asset->is_active ? '1' : '0';

        unset($this->asset->center_id);

        $this->asset->save();

        $this->resetAsset();

        $this->editing = false;

        // $this->assets = ModelsAsset::with('category', 'location')->get();
        $this->assetss();
    }

    public function delete($assetId)
    {
        $location = ModelsAsset::findOrFail($assetId);

        $location->delete();

        $this->dispatchBrowserEvent('close-modal');

        $this->assets = ModelsAsset::with('category', 'location')->get();
    }

    public function import()
    {
        // dd($this);
        // $this->validate([
        //     'file' => 'required',
        // ]);

        $this->validate();

        $path = $this->file->store('temp'); // Store the uploaded file temporarily

        // Read the CSV file using Laravel's built-in CSV reader
        $file = fopen(storage_path('app/' . $path), 'r');
        $header = fgetcsv($file); // Read the header row

        // Process the data in chunks to improve performance
        DB::beginTransaction();
        try {
            while (($data = fgetcsv($file)) !== false) {
                $row = array_combine($header, $data); // Combine the header with the row data

                $asset = ModelsAsset::where('tag', $row['tag'])->first();

                if ($asset) {
                    // Update the existing asset
                    $asset->update([
                        'name' => $row['name'],
                        'weight' => $row['weight'],
                        'height' => $row['height'],
                        'location_id' => $row['location_id'],
                        'category_id' => $row['category_id'],
                        'is_active' => $row['is_active'],
                    ]);
                } else {
                    // Create a new asset
                    ModelsAsset::create([
                        'tag' => $row['tag'],
                        'name' => $row['name'],
                        'weight' => $row['weight'],
                        'height' => $row['height'],
                        'location_id' => $row['location_id'],
                        'category_id' => $row['category_id'],
                        'is_active' => $row['is_active'],
                    ]);
                }
            }

            DB::commit();

            session()->flash('success', 'Data imported successfully.');
        } catch (\Exception $e) {
            DB::rollback();

            session()->flash('error', 'An error occurred while importing the data.');

            throw $e;
        } finally {
            fclose($file); // Close the file handle
            unlink(storage_path('app/' . $path)); // Delete the temporary file
        }
    }

    public function getCategories()
    {
        $this->categories = Category::all();
    }

    public function render()
    {
        return view('livewire.admin.asset.asset');
    }
}
