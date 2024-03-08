<?php

namespace App\Providers;

use App\Repositories\Course\CourseRepository;
use App\Repositories\Equipment\TypeEquipmentRepository;
use App\Repositories\Interfaces\Backend\CourseRepositoryInterface;
use App\Repositories\Equipment\EquipmentRepository;
use App\Repositories\Equipment\UserEquipmentRepository;
use App\Repositories\Interfaces\Backend\EquipmentRepositoryInterface;
use App\Repositories\Interfaces\Backend\TypeEquipmentRepositoryInterface;
use App\Repositories\Interfaces\Backend\UserEquipmentRepositoryInterface;
use App\Repositories\Interfaces\Backend\UserMedalRepositoryInterface;
use App\Repositories\Medal\UserMedalRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\User\UserRepository;
use App\Repositories\Medal\MedalRepository;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\Backend\MedalRepositoryInterface;
use App\Repositories\Backend\BaseCategoryRepository;
use App\Repositories\Backend\BasePostRepository;
use App\Repositories\Backend\Interfaces\BaseCategoryRepositoryInterface;
use App\Repositories\Backend\Interfaces\BasePostRepositoryInterface;
use App\Repositories\Interfaces\QuestRepositoryInterface;
use App\Repositories\Quest\QuestRepository;
class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(MedalRepositoryInterface::class, MedalRepository::class);
        $this->app->bind(BaseCategoryRepositoryInterface::class, BaseCategoryRepository::class);
        $this->app->bind(BasePostRepositoryInterface::class, BasePostRepository::class);
        $this->app->bind(EquipmentRepositoryInterface::class, EquipmentRepository::class);
        $this->app->bind(CourseRepositoryInterface::class, CourseRepository::class);
        $this->app->bind(UserEquipmentRepositoryInterface::class, UserEquipmentRepository::class);
        $this->app->bind(TypeEquipmentRepositoryInterface::class, TypeEquipmentRepository::class);
        $this->app->bind(QuestRepositoryInterface::class, QuestRepository::class);
        $this->app->bind(UserMedalRepositoryInterface::class, UserMedalRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
