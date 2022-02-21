<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\WorkOrders;
use Illuminate\Support\Carbon;
use App\Http\Livewire\DataTable\WithSorting;
use App\Http\Livewire\DataTable\WithCachedRows;
use App\Http\Livewire\DataTable\WithBulkActions;
use App\Http\Livewire\DataTable\WithPerPagePagination;

class FWOMainView extends Component
{
    use WithPerPagePagination, WithSorting, WithBulkActions, WithCachedRows;

    public $showDeleteModal = false;
    public $showEditModal = false;
    public $showNewTechModal = false;
    public $showNewFacModal = false;
    public $showFilters = false;
    public $filters = [
        'type' => '',
        'fixed-asset'=>'',
        'wo-number'=>'',
        'room-number'=>'',
        'search' => '',
        'status' => '',
        'date-min' => null,
        'date-max' => null,
    ];
    public WorkOrders $editing;

    protected $queryString = ['sorts'];

    protected $listeners = ['refreshTransactions' => '$refresh'];

    public function rules() { return [
        'editing.Equipment'=> 'sometimes',
        'editing.Location' => 'required|min:3',
        'editing.Problem' => 'required',
    ]; }

    public function mount() { $this->editing = $this->makeBlankTransaction(); }
    public function updatedFilters() { $this->resetPage(); }

    public function exportSelected()
    {
        return response()->streamDownload(function () {
            echo $this->selectedRowsQuery->toCsv();
        }, 'transactions.csv');
    }

    public function deleteSelected()
    {
        $deleteCount = $this->selectedRowsQuery->count();

        $this->selectedRowsQuery->delete();

        $this->showDeleteModal = false;

        $this->notify('You\'ve deleted '.$deleteCount.' transactions');
    }

    public function makeBlankTransaction()
    {
        return WorkOrders::make(['date' => now(), 'status' => 'success']);
    }

    public function toggleShowFilters()
    {
        $this->useCachedRows();

        $this->showFilters = ! $this->showFilters;
    }

    public function create()
    {
        $this->useCachedRows();

        if ($this->editing->getKey()) $this->editing = $this->makeBlankTransaction();

        $this->showEditModal = true;
    }

    public function edit(WorkOrders $transaction)
    {
        //$this->useCachedRows();

        //if ($this->editing->isNot($transaction)) $this->editing = $transaction;

        //$this->showEditModal = true;
    }

    public function showNewTech()
    {
        //$this->showNewTechModal = true;
    }

    public function showNewFac()
    {
        //$this->showNewFacModal = true;
    }

    public function newTech(WorkOrders $wo)
    {
        $this->useCachedRows();
        if($this->techEdit->isNot($wo)) $this->techEdit = $wo;
        $this->showNewTechModal = true;
    }

    public function save()
    {
        $this->validate();

        $this->editing->save();

        $this->showEditModal = false;
    }

    public function resetFilters() { $this->reset('filters'); }

    public function getRowsQueryProperty()
    {
        $query = WorkOrders::query()
            ->when($this->filters['type'], fn($query, $system) => $query->where('_fm_system', $system))
            ->when($this->filters['wo-number'], fn($query, $num) => $query->where('OrderNo', 'like', '%'.$num.'%'))
            ->when($this->filters['fixed-asset'], fn($query, $asset) => $query->where('FixedAsset', 'like', '%'.$asset.'%'))
            ->when($this->filters['room-number'], fn($query, $room) => $query->where('Room', $room))
            ->when($this->filters['status'], fn($query, $status) => $query->where('status', $status))
            ->when($this->filters['date-min'], fn($query, $date) => $query->where('date', '>=', Carbon::parse($date)))
            ->when($this->filters['date-max'], fn($query, $date) => $query->where('date', '<=', Carbon::parse($date)))
            ->when($this->filters['search'], fn($query, $search) => $query->where('Problem', 'like', '%'.$search.'%'));

        return $this->applySorting($query);
    }

    public function getRowsProperty()
    {
        return $this->cache(function () {
            return $this->applyPagination($this->rowsQuery);
        });
    }

    public function render()
    {
        return view('livewire.f-w-o-main-view', [
            'transactions' => $this->rows,
        ]);
    }
}
