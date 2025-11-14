<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Repositories\ComplaintRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Log;

class StoreAttachmentJob implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  protected $filePath;
  protected $extension;
  protected $complaint_id;
  public $tries = 3;

  public function __construct(string $filePath, string $extension, int $complaint_id)
  {
    $this->filePath = $filePath;
    $this->extension = $extension;
    $this->complaint_id = $complaint_id;
  }

  public function handle(): void
  {
    try {
      $complaints = app(ComplaintRepositoryInterface::class);
      $complaints->addAttachment($this->filePath, $this->extension, $this->complaint_id);
    } catch (Exception $e) {
      // إذا فشلت المحاولة، Laravel سيعيد تشغيل الـ Job تلقائيًا حتى 3 مرات
      Log::error("فشل تخزين المرفق للشكوى {$this->complaint_id}: " . $e->getMessage());
      throw $e; // لازم نرمي الاستثناء حتى يعيد Laravel المحاولة
    }
  }

  // إذا فشلت كل المحاولات
  public function failed(Exception $exception): void
  {
    Log::critical("فشل نهائي بعد 3 محاولات لتخزين المرفق للشكوى {$this->complaint_id}: " . $exception->getMessage());
  }
}
