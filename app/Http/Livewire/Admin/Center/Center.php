<?php

namespace App\Http\Livewire\Admin\Center;

use App\Models\Center as ModelsCenter;
use Livewire\Component;

class Center extends Component
{
    public $centers;
    public $center;
    public $editing = false;

    protected $rules = [
        'center.name' => 'required|string|max:255',
        // Add other validation rules for center fields
    ];

    public function mount()
    {
        $this->centers = ModelsCenter::all();
        $this->resetCenter();
    }

    public function resetCenter()
    {
        $this->center = new ModelsCenter();
        $this->editing = false;
    }

    public function create()
    {
        $this->validate();

        $this->center->save();

        $this->centers = ModelsCenter::all();

        $this->resetCenter();
    }

    public function edit($centerId)
    {
        $this->center = ModelsCenter::findOrFail($centerId);
        $this->editing = true;
    }

    public function update()
    {
        $this->validate();

        $this->center->save();

        $this->resetCenter();
        $this->editing = false;
        $this->centers = ModelsCenter::all();

        // return redirect()->route('admin.centers');
    }

    public function delete($centerId)
    {
        $center = ModelsCenter::findOrFail($centerId);
        $center->delete();

        $this->dispatchBrowserEvent('close-modal');
        $this->centers = ModelsCenter::all();
    }

    public function updatedCenterName()
    {
        $this->validateOnly('center.name');
    }


    public function render()
    {
        return view('livewire.admin.center.center');
    }
}
