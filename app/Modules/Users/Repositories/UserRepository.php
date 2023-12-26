<?php

declare(strict_types=1);

namespace App\Modules\Users\Repositories;

use App\Modules\Catalog\Support\Pagination;
use App\Modules\Users\Exceptions\OldPasswordNotValidException;
use App\Modules\Users\Models\Role;
use App\Modules\Users\Models\User;
use App\Modules\Users\Support\Blueprints\CreateUserBlueprint;
use App\Modules\Users\Support\Blueprints\UpdateUserBlueprint;
use App\Packages\Support\PhoneNumber;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

class UserRepository
{
    public function getByUserId(string $userId, bool $fail = false): ?User
    {
        if ($fail) {
            return User::findOrFail($userId);
        }

        return User::find($userId);
    }

    public function getByPhone(PhoneNumber $phone, bool $fail = false): ?User
    {
        $phoneFormat = PhoneNumberUtil::getInstance()->format(
            $phone,
            PhoneNumberFormat::E164
        );
        $query = User::query()->where('phone', '=', $phoneFormat);

        if ($fail) {
            /** @var User $user */
            $user = $query->firstOrFail();
            return $user;
        }

        /** @var User $user */
        $user = $query->first();
        return $user;
    }

    public function getByEmail(string $email, bool $fail = false): ?User
    {
        $query = User::query()->where('email', '=', $email);

        if ($fail) {
            /** @var User $user */
            $user = $query->firstOrFail();
            return $user;
        }

        /** @var User $user */
        $user = $query->first();
        return $user;
    }

    public function getList(Pagination $pagination, bool $fail = false): LengthAwarePaginator
    {
        $query = User::query()->paginate($pagination->perPage, ['*'], 'page', $pagination->page);

        if ($fail && $query->total() === 0) {
            throw new ModelNotFoundException();
        }

        return $query;
    }

    public function create(CreateUserBlueprint $createUserBlueprint, Role $role): User
    {
        $user = new User([
            'phone' => $createUserBlueprint->phone,
            'name' => $createUserBlueprint->name,
            'email' => $createUserBlueprint->email,
            'password' => $createUserBlueprint->password
        ]);

        $user->save();
        $user->roles()->attach($role->id);

        return $user;
    }

    public function update(User $user, UpdateUserBlueprint $updateUserBlueprint, ?Role $role): void
    {
        $user->update([
            'name' => $updateUserBlueprint->name,
            'surname' => $updateUserBlueprint->surname,
            'patronymic' => $updateUserBlueprint->patronymic,
            'sex' => $updateUserBlueprint->sex,
            'birth_date' => $updateUserBlueprint->birth_date,
            'email' => $updateUserBlueprint->email
        ]);

        if ($role instanceof Role) {
            $user->roles()->sync([$role->id]);
        }

        $user->save();
    }

    public function delete(User $user): void
    {
        $user->delete();
    }

    /**
     * @throws OldPasswordNotValidException
     */
    public function updatePassword(
        User $user,
        string $oldPassword,
        string $newPassword
    ): void {
        if (!Hash::check($oldPassword, $user->password)) {
            throw new OldPasswordNotValidException();
        }

        $user->password = Hash::make($newPassword);
        $user->save();
    }
}
