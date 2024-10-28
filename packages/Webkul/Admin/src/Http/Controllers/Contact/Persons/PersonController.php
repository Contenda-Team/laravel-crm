<?php

namespace Webkul\Admin\Http\Controllers\Contact\Persons;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Http\StreamedResponse;
use Illuminate\Support\Facades\Event;
use Illuminate\View\View;
use Prettus\Repository\Criteria\RequestCriteria;
use Webkul\Admin\DataGrids\Contact\PersonDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Admin\Http\Requests\AttributeForm;
use Webkul\Admin\Http\Requests\MassDestroyRequest;
use Webkul\Admin\Http\Resources\PersonResource;
use Webkul\Contact\Repositories\PersonRepository;
use Webkul\Contact\Repositories\PersonStatusRepository;
use Webkul\Core\Traits\PDFHandler;
use Webkul\Admin\Repositories\OrganizationRepository;
use Webkul\Admin\Repositories\UserRepository;
use Webkul\Admin\Enums\DateRangeOptionEnum;

class PersonController extends Controller
{
    use PDFHandler;

    /**
     * Create a new class instance.
     *
     * @return void
     */
    public function __construct(
        protected PersonRepository $personRepository,
        protected PersonStatusRepository $personStatusRepository
    ) {
        request()->request->add(['entity_type' => 'persons']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View|JsonResponse
    {
        $statuses = app(PersonStatusRepository::class)->all();
        $currentStatus = null;

        if (request('status')) {
            $currentStatus = $this->personStatusRepository->find(request('status'));
        }

        return view('admin::contacts.persons.index', [
            'currentStatus' => $currentStatus,
            'statuses' => $statuses,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin::contacts.persons.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AttributeForm $request): RedirectResponse|JsonResponse
    {
        Event::dispatch('contacts.person.create.before');

        $person = $this->personRepository->create($this->sanitizeRequestedPersonData($request->all()));

        Event::dispatch('contacts.person.create.after', $person);

        if (request()->ajax()) {
            return response()->json([
                'data'    => $person,
                'message' => trans('admin::app.contacts.persons.index.create-success'),
            ]);
        }

        session()->flash('success', trans('admin::app.contacts.persons.index.create-success'));

        return redirect()->route('admin.contacts.persons.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): View
    {
        $person = $this->personRepository->findOrFail($id);

        return view('admin::contacts.persons.view', compact('person'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): View
    {
        $person = $this->personRepository->findOrFail($id);

        return view('admin::contacts.persons.edit', compact('person'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AttributeForm $request, int $id): RedirectResponse|JsonResponse
    {
        Event::dispatch('contacts.person.update.before', $id);

        $person = $this->personRepository->update($this->sanitizeRequestedPersonData($request->all()), $id);

        Event::dispatch('contacts.person.update.after', $person);

        if (request()->ajax()) {
            return response()->json([
                'message' => trans('admin::app.contacts.persons.index.update-success'),
            ], 200);
        }

        session()->flash('success', trans('admin::app.contacts.persons.index.update-success'));

        return redirect()->route('admin.contacts.persons.index');
    }

    /**
     * Search person results.
     */
    public function search(): JsonResource
    {
        if ($userIds = bouncer()->getAuthorizedUserIds()) {
            $persons = $this->personRepository
                ->pushCriteria(app(RequestCriteria::class))
                ->findWhereIn('user_id', $userIds);
        } else {
            $persons = $this->personRepository
                ->pushCriteria(app(RequestCriteria::class))
                ->all();
        }

        return PersonResource::collection($persons);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $person = $this->personRepository->findOrFail($id);

        try {
            Event::dispatch('contacts.person.delete.before', $id);

            $person->delete($id);

            Event::dispatch('contacts.person.delete.after', $id);

            return response()->json([
                'message' => trans('admin::app.contacts.persons.index.delete-success'),
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => trans('admin::app.contacts.persons.index.delete-failed'),
            ], 400);
        }
    }

    /**
     * Mass Delete the specified resources.
     */
    public function massDestroy(MassDestroyRequest $massDestroyRequest): JsonResponse
    {
        $persons = $this->personRepository->findWhereIn('id', $massDestroyRequest->input('indices'));

        foreach ($persons as $person) {
            Event::dispatch('contact.person.delete.before', $person);

            $this->personRepository->delete($person->id);

            Event::dispatch('contact.person.delete.after', $person);
        }

        return response()->json([
            'message' => trans('admin::app.contacts.persons.index.delete-success'),
        ]);
    }

    /**
     * Sanitize requested person data and return the clean array.
     */
    private function sanitizeRequestedPersonData(array $data): array
    {
        if (
            array_key_exists('organization_id', $data)
            && empty($data['organization_id'])
        ) {
            $data['organization_id'] = null;
        }

        if (isset($data['contact_numbers'])) {
            $data['contact_numbers'] = collect($data['contact_numbers'])->filter(fn ($number) => ! is_null($number['value']))->toArray();
        }

        return $data;
    }

    /**
     * Print the person information.
     */
    public function print(int $id): Response|StreamedResponse
    {
        $person = $this->personRepository->findOrFail($id);

        return $this->downloadPDF(
            view('admin::contacts.persons.pdf', compact('person'))->render(),
            'Person_' . $person->name . '_' . $person->created_at->format('d-m-Y')
        );
    }

    /**
     * Get persons grouped by status.
     */
    public function get(): JsonResponse
    {
        $data = [];
        
        $statuses = $this->personStatusRepository->all();

        foreach ($statuses as $status) {
            $query = $this->personRepository
                ->pushCriteria(app(RequestCriteria::class))
                ->where('status_id', $status->id);

            if ($userIds = bouncer()->getAuthorizedUserIds()) {
                $query->whereIn('user_id', $userIds);
            }

            $paginator = $query->with([
                'tags',
                'organization',
                'user',
            ])->paginate(10);

            $data[$status->id] = [
                'id'   => $status->id,
                'name' => $status->name,
                'persons' => [
                    'data' => $paginator->items(),
                    'meta' => [
                        'current_page' => $paginator->currentPage(),
                        'from'         => $paginator->firstItem(),
                        'last_page'    => $paginator->lastPage(),
                        'per_page'     => $paginator->perPage(),
                        'to'           => $paginator->lastItem(),
                        'total'        => $paginator->total(),
                    ],
                ]
            ];
        }

        return response()->json($data);
    }

    /**
     * Update person status.
     */
    public function updateStatus(int $id): JsonResponse
    {
        $person = $this->personRepository->findOrFail($id);

        Event::dispatch('contacts.person.update.status.before', $person);

        $person = $this->personRepository->update([
            'status_id' => request('status_id'),
        ], $id);

        Event::dispatch('contacts.person.update.status.after', $person);

        return response()->json([
            'message' => trans('admin::app.contacts.persons.index.status-update-success'),
        ]);
    }

    /**
     * Kanban lookup.
     */
    public function kanbanLookup()
    {
        $params = $this->validate(request(), [
            'column'      => ['required'],
            'search'      => ['required', 'min:2'],
        ]);

        $column = collect($this->getKanbanColumns())->where('index', $params['column'])->firstOrFail();

        return app($column['filterable_options']['repository'])
            ->select([
                $column['filterable_options']['column']['label'].' as label',
                $column['filterable_options']['column']['value'].' as value'
            ])
            ->where($column['filterable_options']['column']['label'], 'LIKE', '%'.$params['search'].'%')
            ->get()
            ->map
            ->only('label', 'value');
    }

    /**
     * Get columns for the kanban view.
     */
    private function getKanbanColumns(): array
    {
        return [
            [
                'index'                 => 'id',
                'label'                 => trans('admin::app.contacts.persons.index.columns.id'),
                'type'                  => 'integer',
                'searchable'            => false,
                'search_field'          => 'in',
                'filterable'            => true,
                'filterable_type'       => null,
                'filterable_options'    => [],
                'allow_multiple_values' => true,
                'sortable'              => true,
                'visibility'            => true,
            ],
            [
                'index'                 => 'name',
                'label'                 => trans('admin::app.contacts.persons.index.columns.name'),
                'type'                  => 'string',
                'searchable'            => true,
                'search_field'          => 'like',
                'filterable'            => true,
                'filterable_type'       => null,
                'filterable_options'    => [],
                'allow_multiple_values' => false,
                'sortable'              => true,
                'visibility'            => true,
            ],
            [
                'index'                 => 'emails',
                'label'                 => trans('admin::app.contacts.persons.index.columns.email'),
                'type'                  => 'string',
                'searchable'            => true,
                'search_field'          => 'like',
                'filterable'            => true,
                'filterable_type'       => null,
                'filterable_options'    => [],
                'allow_multiple_values' => false,
                'sortable'              => true,
                'visibility'            => true,
            ],
            [
                'index'                 => 'organization_id',
                'label'                 => trans('admin::app.contacts.persons.index.columns.organization'),
                'type'                  => 'string',
                'searchable'            => false,
                'search_field'          => 'in',
                'filterable'            => true,
                'filterable_type'       => 'searchable_dropdown',
                'filterable_options'    => [
                    'repository' => OrganizationRepository::class,
                    'column'     => [
                        'label' => 'name',
                        'value' => 'id',
                    ],
                ],
                'allow_multiple_values' => true,
                'sortable'              => true,
                'visibility'            => true,
            ],
            [
                'index'                 => 'user_id',
                'label'                 => trans('admin::app.contacts.persons.index.columns.sales-person'),
                'type'                  => 'string',
                'searchable'            => false,
                'search_field'          => 'in',
                'filterable'            => true,
                'filterable_type'       => 'searchable_dropdown',
                'filterable_options'    => [
                    'repository' => UserRepository::class,
                    'column'     => [
                        'label' => 'name',
                        'value' => 'id',
                    ],
                ],
                'allow_multiple_values' => true,
                'sortable'              => true,
                'visibility'            => true,
            ],
            [
                'index'              => 'created_at',
                'label'              => trans('admin::app.contacts.persons.index.columns.created-at'),
                'type'               => 'date',
                'searchable'         => false,
                'sortable'           => true,
                'filterable'         => true,
                'filterable_type'    => 'date_range',
                'filterable_options' => DateRangeOptionEnum::options(),
            ],
        ];
    }
}
