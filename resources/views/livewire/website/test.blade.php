<?php

use Livewire\Volt\Component;

new class extends Component {
    public $name = 'test';
    public array $students = [['name' => 'mouayed', 'age' => 19], ['name' => 'ayoub', 'age' => 20], ['name' => 'lmou', 'age' => 21]];
    public function submit()
    {
        dd([
            'name' => $this->name,
            'students' => $this->students,
            'users' => \App\Models\User::all(),
        ]);
    }
}; ?>

<div>
    <h1 x-text="$wire.name"></h1>
    <input wire:model="name" class="input input-bordered" type="text" />
    <button wire:click="submit" class="btn btn-primary">Submit</button>
    @foreach ($students as $student)
        <input wire:model="students.{{ $loop->index }}.name" class="input input-bordered" type="text" />
    @endforeach
</div>
