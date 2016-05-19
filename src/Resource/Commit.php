<?php
declare(strict_types=1);

namespace WyriHaximus\Travis\Resource;

use DateTimeInterface;
use WyriHaximus\ApiClient\Resource\TransportAwareTrait;

abstract class Commit implements CommitInterface
{
    use TransportAwareTrait;

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $sha;

    /**
     * @var string
     */
    protected $branch;

    /**
     * @var string
     */
    protected $message;

    /**
     * @var DateTimeInterface
     */
    protected $committed_at;

    /**
     * @var string
     */
    protected $author_name;

    /**
     * @var string
     */
    protected $author_email;

    /**
     * @var string
     */
    protected $committer_name;

    /**
     * @var string
     */
    protected $committer_email;

    /**
     * @var string
     */
    protected $compare_url;

    /**
     * @return int
     */
    public function id() : int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function sha() : string
    {
        return $this->sha;
    }

    /**
     * @return string
     */
    public function branch() : string
    {
        return $this->branch;
    }

    /**
     * @return string
     */
    public function message() : string
    {
        return $this->message;
    }

    /**
     * @return DateTimeInterface
     */
    public function committedAt() : DateTimeInterface
    {
        return $this->committed_at;
    }

    /**
     * @return string
     */
    public function authorName() : string
    {
        return $this->author_name;
    }

    /**
     * @return string
     */
    public function authorEmail() : string
    {
        return $this->author_email;
    }

    /**
     * @return string
     */
    public function committerName() : string
    {
        return $this->committer_name;
    }

    /**
     * @return string
     */
    public function committerEmail() : string
    {
        return $this->committer_email;
    }

    /**
     * @return string
     */
    public function compareUrl() : string
    {
        return $this->compare_url;
    }
}
