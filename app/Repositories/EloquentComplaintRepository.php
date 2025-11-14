<?php

namespace App\Repositories;

use App\Models\Complaint;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

use function Symfony\Component\Clock\now;

class EloquentComplaintRepository implements ComplaintRepositoryInterface
{
  public function create(array $data): Complaint
  {
    return Complaint::create($data);
  }

  public function addAttachment(string $path, string $extension, int $complaint_id): void
  {
    $complaint = Complaint::find($complaint_id);
    if ($complaint) {
      $complaint->attachments()->create([
        'file_path' => $path,
        'file_type' => $extension,
      ]);

      $complaint->load('attachments');
      Cache::put("complaint_{$complaint_id}", $complaint, \Carbon\Carbon::now()->addMinutes(1));
    }
  }

  public function find(int $id): Complaint
  {
    return Cache::remember("complaint_{$id}", \Carbon\Carbon::now()->addMinutes(1), function () use ($id) {
      $complaint = Complaint::with('attachments')->findOrFail($id);
      $complaint->attachments->makeHidden(['file_type', 'complaint_id', 'created_at', 'updated_at']);
      return $complaint;
    });
  }

  public function getComplaints()
  {
    $u = Auth::user();
    $user = User::find($u->id);
    return Cache::remember("complaints_user_{$user->id}", \Carbon\Carbon::now()->addMinutes(1), function () use ($user) {
      if ($user->role === 'employee') {
        $governmentId = $user->governments()->first()->id;
        return Complaint::with('attachments')->where('government_id', $governmentId)->get();
      } elseif ($user->role === 'admin') {
        return Complaint::with('attachments')->get();
      } elseif ($user->role === 'citizen') {
        return Complaint::with('attachments')->where('user_id', $user->id)->get();
      }
    });
  }
}
