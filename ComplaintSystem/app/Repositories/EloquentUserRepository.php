<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Cache;


class EloquentUserRepository implements UserRepositoryInterface
{
  // public function create(array $data): User
  // {
  //   return User::create($data);
  // }
  public function create(array $data): User
  {
    $user = User::create($data);

    if ($user->email) {
      Cache::put("user_lookup_" . $user->email, $user, 60);
    }

    if ($user->phone) {
      Cache::put("user_lookup_" . $user->phone, $user, 60);
    }

    return $user;
  }


  // public function findByEmailOrPhone(string $identifier): ?User
  // {
  //   return User::where('email', $identifier)->orWhere('phone', $identifier)->first();
  // }
  public function findByEmailOrPhone(string $identifier): ?User
  {
    return Cache::remember(
      "user_lookup_" . $identifier,        // مفتاح الكاش
      60,                                  // 60 ثانية
      function () use ($identifier) {      // في حال عدم وجود قيمة بالكاش
        return User::where('email', $identifier)
          ->orWhere('phone', $identifier)
          ->first();
      }
    );
  }

  public function findById(int $id): ?User
  {
    return User::find($id);
  }

  // public function update(User $user, array $data): bool
  // {
  //   return $user->update($data);
  // }

  public function update(User $user, array $data): bool
  {
    $updated = $user->update($data);

    if ($updated) {
      // تحديث كاش البريد
      if ($user->email) {
        Cache::put("user_lookup_" . $user->email, $user, 60);
      }

      // تحديث كاش الهاتف
      if ($user->phone) {
        Cache::put("user_lookup_" . $user->phone, $user, 60);
      }
    }

    return $updated;
  }


  public function attachToGovernment(User $user, int $governmentId): void
  {
    $user->governments()->syncWithoutDetaching([$governmentId]);
  }
}
