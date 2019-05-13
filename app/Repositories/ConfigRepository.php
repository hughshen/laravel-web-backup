<?php

namespace App\Repositories;

use App\Models\Config;
use Bosnadev\Repositories\Eloquent\Repository;

class ConfigRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return Config::class;
    }

    /**
     * Get config by specify key
     *
     * @param string $key
     * @param bool $cache
     * @return mixed
     */
    public static function getByKey($key, $cache = true)
    {
        $all = $cache ? self::getAllByCache() : self::getAll();

        return collect($all)->get($key);
    }

    /**
     * Get configs by keys
     *
     * @param array $keys
     * @param bool $cache
     * @return \Illuminate\Support\Collection
     */
    public static function getByKeys(array $keys, $cache = true)
    {
        $all = $cache ? self::getAllByCache() : self::getAll();

        return collect($all)->only($keys);
    }

    /**
     * Get all configs by cache
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getAllByCache()
    {
        $configs = app('cache')->rememberForever('configs', function () {
            return self::getAll();
        });

        return $configs;
    }

    /**
     * Get all configs
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getAll()
    {
        return Config::query()->pluck('config_value', 'config_name');
    }

    /**
     * Update configs
     *
     * @param array $data
     * @return bool
     */
    public static function updateByData(array $data)
    {
        app('db')->beginTransaction();
        try {
            foreach ($data as $key => $val) {
                Config::updateOrCreate(['config_name' => $key], ['config_value' => (string)$val]);
            }

            app('db')->commit();

            return true;
        } catch (\Exception $e) {
            app('db')->rollBack();
            return false;
        }
    }

    /**
     * Check if yes or no
     *
     * @param string $key
     * @param bool $cache
     * @return bool
     */
    public static function yesOrNoByKey($key, $cache = true)
    {
        return (int)self::getByKey($key, $cache) === 1;
    }
}