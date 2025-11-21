<?php

namespace App\Repositories;


use App\Models\Complaint;
use App\Models\ComplaintLog;
use Illuminate\Http\UploadedFile;
use Nette\Utils\Json;

interface ComplaintRepositoryInterface
{
  public function create(array $data): Complaint;
  public function addAttachment(string $path,string $extension, int $complaint_id): Complaint;
  public function find(int $id): Complaint;
  public function getComplaints();

  public function getComplaintsLog(int $id);

  public function getCitizenComplaintStatus(int $id);

}
