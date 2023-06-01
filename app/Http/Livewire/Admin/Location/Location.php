<?php

namespace App\Http\Livewire\Admin\Location;

use App\Models\Center;
use App\Models\Location as ModelsLocation;
use Livewire\Component;

class Location extends Component
{
    public $centers;
    public $center_id;
    public $locations;
    public $location;
    public $editing = false;

    protected $rules = [
        'location.name' => 'required|string|max:255',
        'location.center_id' => 'required|exists:centers,id',
        // Add other validation rules for location fields
    ];

    public function mount()
    {
        $this->locations = ModelsLocation::with('center')->get();
        // dd($this->locations);
        $this->centers = Center::all();
        $this->resetLocation();
    }

    public function resetLocation()
    {
        $this->location = new ModelsLocation();
        $this->editing = false;
    }

    public function create()
    {
        $this->validate();
        $this->location->save();

        $this->locations = ModelsLocation::with('center')->get();

        $this->resetLocation();
    }

    public function edit($locationId)
    {
        $this->location = ModelsLocation::findOrFail($locationId);
        $this->editing = true;
    }

    public function update()
    {
        $this->validate();

        $this->location->save();

        $this->resetLocation();

        $this->editing = false;

        $this->locations = ModelsLocation::with('center')->get();

        // return redirect()->route('admin.locations');
    }

    public function delete($locationId)
    {
        $location = ModelsLocation::findOrFail($locationId);
        $location->delete();

        $this->dispatchBrowserEvent('close-modal');
        $this->locations = ModelsLocation::with('center')->get();
    }

    public function updatedLocationName()
    {
        $this->validateOnly('location.name');
    }

    public function render()
    {
        return view('livewire.admin.location.location');
    }
}
