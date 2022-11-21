<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateModule;
use App\Repositories\CourseRepositoryInterface;
use App\Repositories\ModuleRepositoryInterface;
use Illuminate\Http\Request;

class ModuleController extends Controller
{

    protected $repository;

    public function __construct(CourseRepositoryInterface $repositoryCourse, ModuleRepositoryInterface $repository)
    {
        $this->repositoryCourse = $repositoryCourse;
       $this->repository = $repository;
    }

    public function index($courseId)
    {
        if (!$course = $this->repositoryCourse->findById($courseId))
        return back();

        $data = $this->repository->getAllByCourseId($courseId);
        $modules = covertItemsOfArrayToObject($data);

        return view('admin/courses/modules/index-modules', compact('course', 'modules'));
    }

    public function create($courseId)
    {
        if (!$course = $this->repositoryCourse->findById($courseId))
        return back();

        return view('admin/courses/modules/create-modules', compact('course'));
    }

    public function store(StoreUpdateModule $request, $courseId)
    {
        if (!$this->repositoryCourse->findById($courseId))
        return back();

        $this->repository->createByCourse($courseId, $request->only(['name']));
        return redirect()->route('modules.index', $courseId);
    }

    public function edit($courseId, $id)
    {
        if (!$course = $this->repositoryCourse->findById($courseId))
        return back();

        if (!$module = $this->repository->findById($id))
        return back();

        return view('admin/courses/modules/edit-modules', compact('course', 'module'));
    }

    public function update(StoreUpdateModule $request, $courseId, $id)
    {
        if (!$this->repositoryCourse->findById($courseId))
        return back();

        $this->repository->update($id, $request->only('name'));

        return redirect()->route('modules.index', $courseId);
    }
}
