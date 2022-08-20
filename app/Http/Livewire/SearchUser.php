<?php

namespace App\Http\Livewire;

use App\User;
use Livewire\Component;

class SearchUser extends Component
{
    public $query;
    public $users;
    public $highlightIndex;

    public function mount()
    {
        $this->reset();
    }

    public function reset(...$properties)
    {
        $this->query = '';
        $this->users = collect();
        $this->highlightIndex = 0;
    }

    public function incrementHighlight()
    {
        if ($this->highlightIndex === count($this->users) - 1) {
            $this->highlightIndex = 0;
            return;
        }
        $this->highlightIndex++;
    }

    public function decrementHighlight()
    {
        if ($this->highlightIndex === 0) {
            $this->highlightIndex = count($this->users) - 1;
            return;
        }
        $this->highlightIndex--;
    }

    public function selectContact()
    {
        $user = $this->users[$this->highlightIndex] ?? null;
        if ($user) {
            $this->redirect(route('show-user', $user['id']));
        }
    }

    public function updatedQuery()
    {
        if (empty($this->query)) {
            $this->users = collect();
            return;
        }
        $this->users = User::query()
            ->where('name', 'like', '%'.$this->query.'%')
            ->orWhere('family', 'like', '%'.$this->query.'%')
            ->orWhere('phone_number', 'like', '%'.$this->query.'%')
            ->limit(5)
            ->get();
    }

    public function render()
    {
        return view('livewire.search-user');
    }
}
