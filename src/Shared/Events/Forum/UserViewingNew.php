<?php
namespace Shared\Events\Forum;

use Penoaks\Database\Eloquent\Collection;

class UserViewingNew
{
    /**
     * @var Collection
     */
    public $threads;

    /**
     * Create a new event instance.
     *
     * @param  Collection  $threads
     */
    public function __construct(Collection $threads)
    {
        $this->threads = $threads;
    }
}
