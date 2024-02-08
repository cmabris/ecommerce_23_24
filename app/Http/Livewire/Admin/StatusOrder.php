<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;

class StatusOrder extends Component
{
    public $order, $status;

    public function mount()
    {
        $this->status = $this->order->status;
    }

    public function update()
    {
        $this->order->status = $this->status;

        $this->order->save();
    }

    public function render()
    {
        $items = json_decode($this->order->content);

        return view('livewire.admin.status-order', compact('items'));
    }
}