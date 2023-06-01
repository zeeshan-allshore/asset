<?php

namespace App\Http\Livewire\Admin\Category;

use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Livewire\Component;

class CategoryIndex extends Component
{
    public $categories;
    public $category;
    public $editing = false;

    protected $rules = [
        'category.name' => 'required|string|max:255',
        // Add other validation rules for category fields
    ];

    public function mount()
    {
        $this->categories = Category::all();
        $this->resetCategory();
    }

    public function resetCategory()
    {
        $this->category = new Category();
        $this->editing = false;
    }

    public function create()
    {
        $this->validate();

        $this->category->save();

        $this->categories = Category::all();

        $this->resetCategory();
    }

    public function edit($categoryId)
    {
        $this->category = Category::findOrFail($categoryId);
        $this->editing = true;
    }

    public function update()
    {
        $this->validate();

        $this->category->save();

        $this->resetCategory();
        $this->editing = false;
        $this->categories = Category::all();

        // return redirect()->route('admin.categories');
    }

    public function delete($categoryId)
    {
        $category = Category::findOrFail($categoryId);
        $category->delete();

        $this->dispatchBrowserEvent('close-modal');
        $this->categories = Category::all();
    }

    public function updatedCategoryName()
    {
        $this->validateOnly('category.name');
    }

    public function render()
    {
        return view('livewire.admin.category.category-index');
    }
}
