<?php

namespace App\Http\Livewire\Admin;

use App\Models\Color;
use App\Models\ColorSize as TbPivot;
use Livewire\Component;

class ColorSize extends Component
{
    public $size, $colors;
    public $color_id, $quantity;
    public $pivot, $open = false, $pivot_color_id, $pivot_quantity;

    protected $rules = [
        'color_id' => 'required',
        'quantity' => 'required|numeric'
    ];

    protected $listeners = ['delete'];

    public function mount()
    {
        $this->colors = Color::all();
    }

    public function save()
    {
        $this->validate();

        $pivot = TbPivot::where('color_id', $this->color_id)
            ->where('size_id', $this->size->id)
            ->first();

        if ($pivot) {
            $pivot->quantity += $this->quantity;
            $pivot->save();
        } else {
            $this->size->colors()->attach([
                $this->color_id => [
                    'quantity' => $this->quantity,
                ],
            ]);
        }

        $this->reset(['color_id', 'quantity']);

        $this->emit('saved');

        $this->size = $this->size->fresh();
    }

    public function edit(TbPivot $pivot)
    {
        $this->pivot = $pivot;
    }

    public function update()
    {
        $this->pivot->color_id = $this->pivot_color_id;
        $this->pivot->quantity = $this->pivot_quantity;

        $this->pivot->save();

        $this->size = $this->size->fresh();

        $this->open = false;
    }

    public function delete(TbPivot $pivot)
    {
        $pivot->delete();
        $this->size = $this->size->fresh();
    }

    public function render()
    {
        $sizeColors = $this->size->colors;

        return view('livewire.admin.color-size', compact('sizeColors'));
    }
}
