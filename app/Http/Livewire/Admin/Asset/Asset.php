<?php

namespace App\Http\Livewire\Admin\Asset;

use App\Models\Asset as ModelsAsset;
use App\Models\Category;
use App\Models\Center;
use App\Models\Location;
use Livewire\Component;

class Asset extends Component
{
    public $assets, $asset, $categories, $centers, $locations, $editing = false;

    protected $rules = [
        'asset.tag'         => 'required|string|max:255',
        'asset.name'        => 'required|string|max:255',
        'asset.height'      => 'required',
        'asset.weight'      => 'required',
        'asset.center_id'   => 'present|exists:centers,id',
        'asset.location_id' => 'required|exists:locations,id',
        'asset.category_id' => 'required|exists:categories,id',
        'asset.is_active'   => 'present',
    ];

    public function mount()
    {
        $this->assets = ModelsAsset::with('category', 'location')->get();
        $this->centers = Center::with('locations')->get();

        // dd($this->centers);
        // $this->locations = Location::with('center')->get();
        $this->categories = Category::all();
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

        $this->assets = ModelsAsset::with('category', 'location')->get();

        $this->resetAsset();
    }

    public function edit($assetId)
    {
        // dd($assetId);
        $this->asset = ModelsAsset::findOrFail($assetId);

        $center = Center::where('id', $this->asset->location->center_id)->with('locations')->first();
        $this->asset->center_id = $center->id;
        // dd($this->centers);
        $this->locations = $center ? $center->locations : collect([]);

        // dd($this->locations);

        $this->editing = true;
    }

    public function update()
    {
        // dd($this->asset);

        $this->validate();

        $this->asset->is_active = $this->asset->is_active ? '1' : '0';

        unset($this->asset->center_id);

        $this->asset->save();

        $this->resetAsset();

        $this->editing = false;

        $this->assets = ModelsAsset::with('category', 'location')->get();
    }

    public function delete($assetId)
    {
        $location = ModelsAsset::findOrFail($assetId);

        $location->delete();

        $this->dispatchBrowserEvent('close-modal');

        $this->assets = ModelsAsset::with('category', 'location')->get();
    }

    public function render()
    {
        return view('livewire.admin.asset.asset');
    }
}
