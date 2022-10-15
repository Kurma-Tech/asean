<?php

namespace App\Http\Livewire\Admin\Journals;

use App\Models\Country;
use App\Models\Journal;
use App\Models\JournalCategory;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class JournalsListComponent extends Component
{
    use WithPagination;

    public $perPage = 5;
    public $search = '';
    public $orderBy = 'id';
    public $sortBy = false;

    public $error;

    public
        $countries = [],
        $categories = [];

    public $hiddenId = 0;
    public $title,
           $published_year,
           $category_id,
           $country_id,
           $abstract,
           $author_name,
           $publisher_name,
           $source_title,
           $issn_no,
           $citition_no,
           $eid_no,
           $keywords,
           $link,
           $long,
           $lat;
    public $btnType = 'Create';

    protected $listeners = ['refreshJournalListComponent' => '$refresh'];

    protected function rules()
    {
        return [
            'title'          => 'required|min:3',
            'source_title'   => 'required',
            'country_id'     => 'required|integer',
            'category_id'    => 'required|integer',
            'abstract'       => 'required',
            'author_name'    => 'required',
            'publisher_name' => 'required',
            'published_year' => 'required|date_format:"Y"',
            'issn_no'        => 'required',
            'citition_no'    => 'required',
            'eid_no'         => 'required',
            'keywords'       => 'required',
            'link'           => 'required|url',
            'long'           => 'required',
            'lat'            => 'required',
        ];
    }

    protected $messages = [
        'country_id.required'  => 'Country field is required',
        'country_id.integer'   => 'You must select country from drop down',
        'category_id.required' => 'Patent kind field is required',
        'category_id.integer'  => 'You must select patent kind from drop down',
        'long.required'        => 'Longitude field is required',
        'lat.required'         => 'Latitude field is required',
    ];

    public function mount()
    {
        $this->countries  = Country::select('id', 'name')->get();
        $this->categories = JournalCategory::where('parent_id', '!=', null)->select('id', 'category')->get();
    }

    public function render()
    {
        return view('livewire.admin.journals.journals-list-component', [
            'journals' => Journal::search($this->search)
                ->withTrashed()
                ->orderBy($this->orderBy, $this->sortBy ? 'asc' : 'desc')
                ->paginate($this->perPage),
        ])->layout('layouts.admin');
    }

    // Store
    public function storeJournal()
    {
        $this->validate(); // validate Journals form

        DB::beginTransaction();

        try {
            $updateId = $this->hiddenId;
            if ($updateId > 0) {
                $journal = Journal::find($updateId); // update Journal
            } else {
                $journal = new Journal(); // create Journal
            }

            $journal->title          = $this->title;
            $journal->published_year = $this->published_year;
            $journal->country_id     = $this->country_id;
            $journal->category_id    = $this->category_id;
            $journal->abstract       = $this->abstract;
            $journal->author_name    = $this->author_name;
            $journal->publisher_name = $this->publisher_name;
            $journal->source_title   = $this->source_title;
            $journal->issn_no        = $this->issn_no;
            $journal->citition_no    = $this->citition_no;
            $journal->eid_no         = $this->eid_no;
            $keywordsToArray = explode(',', $this->keywords);
            $keywordsJson = json_encode($keywordsToArray);
            $journal->keywords       = $keywordsJson;
            $journal->link           = $this->link;
            $journal->long           = $this->long;
            $journal->lat            = $this->lat;
            $journal->save();

            DB::commit();

            $this->dispatchBrowserEvent('success-message', ['message' => 'Journal has been ' . $this->btnType . '.']);

            $this->reset(
                'title', 'published_year', 
                'country_id', 'category_id', 
                'abstract', 'author_name', 
                'publisher_name', 'long', 'lat', 
                'source_title', 'issn_no', 
                'citition_no', 'eid_no', 
                'keywords', 'link',
                'hiddenId', 'btnType'
            );
        } catch (\Throwable $th) {
            DB::rollback();
            // $this->error = $th->getMessage();
            $this->error = 'Ops! looks like we had some problem';
            $this->dispatchBrowserEvent('error-message', ['message' => $this->error]);
        }
    }

    // Update Form
    public function editForm($id)
    {
        $singleJournal        = Journal::find($id);
        $this->hiddenId       = $singleJournal->id;
        $this->title          = $singleJournal->title;
        $this->published_year = $singleJournal->published_year;
        $this->country_id     = $singleJournal->country_id;
        $this->abstract       = $singleJournal->abstract;
        $this->author_name    = $singleJournal->author_name;
        $this->publisher_name = $singleJournal->publisher_name;
        $this->source_title   = $singleJournal->source_title;
        $this->issn_no        = $singleJournal->issn_no;
        $this->citition_no    = $singleJournal->citition_no;
        $this->eid_no         = $singleJournal->eid_no;
        $this->keywords       = json_decode($singleJournal->keywords);
        $this->link           = $singleJournal->link;
        $this->long           = $singleJournal->long;
        $this->lat            = $singleJournal->lat;
        $this->btnType        = 'Update';

        $this->emit('countryEvent', $this->country_id);
        $this->emit('categoryEvent', $this->category_id);
    }

    // softDelete
    public function softDelete($id)
    {
        try {
            $data = Journal::find($id);
            if ($data != null) {
                $data->delete();
                $this->dispatchBrowserEvent('success-message', ['message' => 'Journal deleted successfully']);
            } else {
                $this->error = 'Ops! looks like we had some problem';
                $this->dispatchBrowserEvent('error-message', ['message' => $this->error]);
            }
        } catch (\Throwable $th) {
            DB::rollback();
            $this->error = 'Ops! looks like we had some problem';
            // $this->error = $th->getMessage();
            $this->dispatchBrowserEvent('error-message', ['message' => $this->error]);
        }
    }

    // restore
    public function restore($id)
    {
        try {
            $data = Journal::onlyTrashed()->find($id);
            if ($data != null) {
                $data->restore();
                $this->dispatchBrowserEvent('success-message', ['message' => 'Journal restored successfully']);
            } else {
                $this->error = 'Ops! looks like we had some problem';
                $this->dispatchBrowserEvent('error-message', ['message' => $this->error]);
            }
        } catch (\Throwable $th) {
            DB::rollback();
            // $this->error = $th->getMessage();
            $this->error = 'Ops! looks like we had some problem';
            $this->dispatchBrowserEvent('error-message', ['message' => $this->error]);
        }
    }

    // reset fields
    public function resetFields()
    {
        $this->reset(
            'title', 'published_year', 
            'country_id', 'category_id', 
            'abstract', 'author_name', 
            'publisher_name', 'long', 'lat', 
            'source_title', 'issn_no', 
            'citition_no', 'eid_no', 
            'keywords', 'link',
            'hiddenId', 'btnType'
        );
    }
}
