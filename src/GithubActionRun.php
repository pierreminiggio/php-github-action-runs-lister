<?php

namespace PierreMiniggio\GithubActionRunsLister;

use PierreMiniggio\GithubStatusesEnum\ConclusionsEnum;
use PierreMiniggio\GithubStatusesEnum\GithubStatusesEnum;

class GithubActionRun
{
    /**
     * @param string $status
     * @see GithubStatusesEnum
     * 
     * @param string $conclusion
     * @see ConclusionsEnum
     */
    public function __construct(
        public int $id,
        public string $status,
        public string $conclusion
    )
    {
    }
}
