<?php
declare(strict_types=1);

namespace WyriHaximus\Travis\Resource;

use WyriHaximus\ApiClient\Resource\TransportAwareTrait;

abstract class Settings implements SettingsInterface
{
    use TransportAwareTrait;

    /**
     * @var bool
     */
    protected $builds_only_with_travis_yml;

    /**
     * @var bool
     */
    protected $build_pushes;

    /**
     * @var bool
     */
    protected $build_pull_requests;

    /**
     * @var int
     */
    protected $maximum_number_of_builds;

    /**
     * @return bool
     */
    public function buildsOnlyWithTravisYml() : bool
    {
        return $this->builds_only_with_travis_yml;
    }

    /**
     * @return bool
     */
    public function buildPushes() : bool
    {
        return $this->build_pushes;
    }

    /**
     * @return bool
     */
    public function buildPullRequests() : bool
    {
        return $this->build_pull_requests;
    }

    /**
     * @return int
     */
    public function maximumNumberOfBuilds() : int
    {
        return $this->maximum_number_of_builds;
    }
}
