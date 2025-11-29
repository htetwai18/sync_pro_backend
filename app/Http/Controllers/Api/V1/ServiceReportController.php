<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\ServiceReportResource;
use App\Models\ServiceReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServiceReportController extends BaseApiController
{
    private array $with = ['task','submittedBy','reviewedBy','task.asset','task.customer'];

    public function index()
    {
        $rows = ServiceReport::with($this->with)->get();
        return $this->success(ServiceReportResource::collection($rows));
    }

    public function show(ServiceReport $service_report)
    {
        $service_report->load($this->with);
        return $this->success(new ServiceReportResource($service_report));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'submitted_by_id' => 'required|exists:users,id',
            'reviewed_by_id' => 'nullable|exists:users,id',
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'status' => 'nullable|string|max:255',
            'attachment_url' => 'nullable|string|max:512',
            'submitted_date' => 'nullable|date',
            'approved_date' => 'nullable|date',
        ]);
        $row = ServiceReport::updateOrCreate(['task_id' => $data['task_id']], $data);
        $row->load($this->with);
        return $this->success(new ServiceReportResource($row), 201);
    }

    public function update(Request $request, ServiceReport $service_report)
    {
        $data = $request->validate([
            'submitted_by_id' => 'sometimes|exists:users,id',
            'reviewed_by_id' => 'nullable|exists:users,id',
            'title' => 'sometimes|string|max:255',
            'content' => 'nullable|string',
            'status' => 'nullable|string|max:255',
            'attachment_url' => 'nullable|string|max:512',
            'submitted_date' => 'nullable|date',
            'approved_date' => 'nullable|date',
        ]);
        $service_report->update($data);
        $service_report->load($this->with);
        return $this->success(new ServiceReportResource($service_report));
    }

    public function destroy(ServiceReport $service_report)
    {
        $service_report->delete();
        return $this->success(null, 204);
    }

    public function uploadAttachment(Request $request, ServiceReport $service_report)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png,webp,doc,docx|max:8192',
        ]);

        $path = $request->file('file')->store('service_reports', 'public');
        $url = Storage::disk('public')->url($path);

        $service_report->update(['attachment_url' => $url]);
        $service_report->load($this->with);

        return $this->success(new ServiceReportResource($service_report), 201);
    }
}


