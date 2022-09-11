<?php

namespace App\Http\Livewire;

use App\Models\User;
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
        $searchString = englishString($this->query);
        $this->users = User::query()
            ->where('name', 'like', '%'.$searchString.'%')
            ->orWhere('family', 'like', '%'.$searchString.'%')
            ->orWhere('phone_number', 'like', '%'.formatPhoneNumber($searchString).'%')
            ->limit(5)
            ->get();
    }

    public function render()
    {
        return view('livewire.search-user');
    }
}
