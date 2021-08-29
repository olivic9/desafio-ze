<?php

namespace App\Providers;

use App\Repositories\BaseRepository;
use App\Repositories\Contracts\BaseInterface;
use App\Repositories\Contracts\PartnerInterface;
use App\Repositories\PartnerRepository;
use Illuminate\Support\ServiceProvider;

/**
 * Class RepositoryServiceProvider
 * @package App\Providers
 */
class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $toBind = [
            BaseInterface::class => BaseRepository::class,
            PartnerInterface::class => PartnerRepository::class,
        ];

        foreach ($toBind as $interface => $implementation) {
            $this->app->bind($interface, $implementation);
        }
    }
}
