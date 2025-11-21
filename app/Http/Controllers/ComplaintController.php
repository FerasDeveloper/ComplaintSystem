<?php

namespace App\Http\Controllers;

use App\Http\Requests\ComplaintRequest;
use App\Http\Requests\ComplaintUpdateStatusRequest;
use Illuminate\Container\Attributes\DB;
use Symfony\Component\HttpFoundation\Request;

class ComplaintController extends Controller
{
  protected $complaintSerive;
  public function __construct(\App\Services\ComplaintServices $complaintSerive)
  {
    $this->complaintSerive = $complaintSerive;
  }

  public function addComplaint(ComplaintRequest $request)
  {
    $user = $request->user();
    if (!$user) {
      return response()->json(['message' => 'Unauthorized'], 401);
    }
    $data = $request->only('title', 'description', 'location', 'status', 'type', 'government_id');

    $attachments = $request->file(('attachments'), []);

    $this->complaintSerive->addComplaint($user, $data, $attachments);
    return response()->json(['message' => 'Complaint added successfully'], 201);
  }

  public function showComplaint($id)
  {
    return response()->json($this->complaintSerive->showComplaint($id), 200);
  }

  public function getComplaints()
  {
    return response()->json($this->complaintSerive->getComplaints(), 200);
  }

  public function getComolaintsLog($id){
    return response()->json($this->complaintSerive->getComplaintesLog($id));
  }
  public function getCitizenComplaintStatus($id){
    return response()->json($this->complaintSerive->getCitizenComplaintStatus($id));
  }
}
