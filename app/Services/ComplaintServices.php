<?php

namespace App\Services;

use App\Jobs\StoreAttachmentJob;
use App\Repositories\ComplaintRepositoryInterface;
use App\Models\Complaint;
use App\Models\ComplaintLog;

class ComplaintServices
{
  protected $complaints;

  public function __construct(ComplaintRepositoryInterface $complaints)
  {
    $this->complaints = $complaints;
  }

  public function addComplaint($user, $data, $attachments): void
  {
    $data['user_id'] = $user->id;
    $data['government_id'] = is_numeric($data['government_id'])
      ? (int) $data['government_id']
      : $data['government_id'];

    $complaint = $this->complaints->create($data);

    if (!empty($attachments)) {
      foreach ($attachments as $file) {
        $path = $file->store('attachments', 'public');
        $extension = $file->getClientOriginalExtension();
        StoreAttachmentJob::dispatch($path, $extension, $complaint->id);
      }
    } else {
      $complaint->update(['status' => 'new']);
    }
  }

  public function showComplaint($id): Complaint
  {
    return $this->complaints->find($id);
  }

  public function getComplaints()
  {
    return $this->complaints->getComplaints();
  }

  public function getComplaintesLog($id){
    $data = $this->complaints->getComplaintsLog($id);
    return $data ;
  }
  public function getCitizenComplaintStatus($id){
    $data = $this->complaints->getCitizenComplaintStatus($id);
    return $data;
  }
}
