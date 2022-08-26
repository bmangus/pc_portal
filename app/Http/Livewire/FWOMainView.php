<?php

namespace App\Http\Livewire;

use Hyyppa\FluentFM\Connection\FluentFMRepository;
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
    public $showOnlyMine = false;
    public $canEdit = false;
    public $canCreate = false;
    public $filters = [
        'type' => '',
        'fixed-asset'=>'',
        'wo-number'=>'',
        'room-number'=>'',
        'search' => '',
        'status' => '',
        'date-min' => null,
        'date-max' => null,
        'submitter-email' => '',
    ];
    public WorkOrders $editing;

    public $access = 'none';

    protected $queryString = ['sorts'];

    protected $listeners = ['refreshTransactions' => '$refresh'];

    public function rules() { return [
        'editing.OrderNo' => 'sometimes',
        'editing.Status' => 'sometimes',
        'editing.Room' => 'sometimes',
        'editing.SubmittedBy' => 'sometimes',
        'editing.Contact' => 'sometimes',
        'editing.RequestType' => 'sometimes',
        'editing.SubRequestType' => 'sometimes',
        'editing.Equipment'=> 'sometimes',
        'editing.Location' => 'required|min:3',
        'editing.Problem' => 'required',
    ]; }

    public function mount() {
        $this->editing = $this->makeBlankTransaction();

        $this->getSystemAccess();

        return $this;
    }

    public function getSystemAccess() {
        $groups = json_decode(auth()->user()->groups);
        if (in_array('DOTPCAdmin', $groups)) {
            $this->access = 'both';
        } elseif (in_array('TechWOSubmit', $groups) && in_array('BGWOSubmit', $groups)) {
            $this->access = 'both';
            $this->canCreate = 'both';
        } elseif (in_array('TechWOSubmit', $groups)) {
            $this->access = 'technology';
            $this->filters['type'] = 'technology';
        } elseif (in_array('BGWOSubmit', $groups)) {
            $this->access = 'facilities';
            $this->filters['type'] = 'facilities';
        }
    }

    public function setFilterType($type) {
        $this->filters['type'] = $type;
    }

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
        $this->useCachedRows();

        if ($this->editing->isNot($transaction)) $this->editing = $transaction;

        $this->canEdit = (strtolower($transaction->SubmitterEmail) === auth()->user()->email && $transaction->status !== 'Completed');

        $this->showEditModal = true;
    }

    public function canCreateWorkOrders()
    {

    }

    public function showNewTech()
    {
        $this->showNewTechModal = true;
    }

    public function showNewFac()
    {
        $this->showNewFacModal = true;
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

    public function saveToFileMaker()
    {
        if($this->editing->_fm_system === 'technology') {
            $connection = new FluentFMRepository([
                'file' => config('app.two_file'),
                'host' => config('app.two_host'),
                'user' => config('app.two_username'),
                'pass' => config('app.two_password'),
                'client' => [
                    'verify'=>false
                ],
            ]);
        } else {
            $connection = new FluentFMRepository([
                'file' => config('app.two_file'),
                'host' => config('app.two_host'),
                'user' => config('app.two_username'),
                'pass' => config('app.two_password'),
                'client' => [
                    'verify'=>false
                ],
            ]);
        }

    }

    public function resetFilters() {
        $this->reset('filters');
        $this->getSystemAccess();
    }

    public function getRowsQueryProperty()
    {
        $query = WorkOrders::query()
            ->when($this->filters['type'], fn($query, $system) => $query->where('_fm_system', $system))
            ->when($this->filters['wo-number'], fn($query, $num) => $query->where('OrderNo', 'like', '%'.$num.'%'))
            ->when($this->filters['fixed-asset'], fn($query, $asset) => $query->where('FixedAsset', 'like', '%'.$asset.'%'))
            ->when($this->filters['room-number'], fn($query, $room) => $query->where('Room', $room))
            ->when($this->filters['status'], fn($query, $status) => $query->where('status', $status))
            ->when($this->filters['date-min'], fn($query, $date) => $query->where('SubmitDate', '>=', Carbon::parse($date)))
            ->when($this->filters['date-max'], fn($query, $date) => $query->where('SubmitDate', '<=', Carbon::parse($date)))
            ->when($this->filters['search'], fn($query, $search) => $query->where('Problem', 'like', '%'.$search.'%'))
            ->when($this->showOnlyMine, fn($query, $search) => $query->where('SubmitterEmail', auth()->user()->email));

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
