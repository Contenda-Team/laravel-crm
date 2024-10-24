<?php

namespace Webkul\Admin\Http\Controllers\Case;

use Illuminate\Http\Request;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Case\Repositories\CaseRepository;

class CaseController extends Controller
{
    protected $caseRepository;

    public function __construct(CaseRepository $caseRepository)
    {
        $this->caseRepository = $caseRepository;
    }

    public function index()
    {
        $cases = $this->caseRepository->all();

        return view('admin::cases.index', compact('cases'));
    }

    public function create()
    {
        return view('admin::cases.create');
    }

    public function store(Request $request)
    {
        $this->caseRepository->create($request->all());

        return redirect()->route('admin.cases.index');
    }

    public function edit($id)
    {
        $case = $this->caseRepository->find($id);

        return view('admin::cases.edit', compact('case'));
    }

    public function update(Request $request, $id)
    {
        $this->caseRepository->update($request->all(), $id);

        return redirect()->route('admin.cases.index');
    }

    public function destroy($id)
    {
        $this->caseRepository->delete($id);

        return redirect()->route('admin.cases.index');
    }
}

