<?php

namespace App\Http\Livewire\Admin;

use App\Models\Category;
use Livewire\Component;

class Dashboard extends Component
{

    public $chartData = [];

    public function mount()
    {
        // Retrieve the top 5 categories based on the number of assets
        $categories = Category::withCount('assets')
            ->orderByDesc('assets_count')
            ->limit(5)
            ->get();

        // Prepare the chart data
        $this->chartData = $categories->map(function ($category) {
            return [
                'name' => $category->name,
                'value' => $category->assets_count,
            ];
        })->toArray();
        // dd($this->chartData);
    }


    public function render()
    {
        abort_unless(auth()->user()->can('view_dashboard'), 403);

        return view('livewire.admin.dashboard');
    }
}
