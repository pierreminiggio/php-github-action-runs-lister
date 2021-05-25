<?php

namespace PierreMiniggio\GithubActionRunsLister;

use PierreMiniggio\GithubActionRun\GithubActionRun;

class RunListerResponse
{

    /**
     * @param GithubActionRun[] $runs
     */
    public function __construct(
        public array $runs,
        public int $totalCount
    )
    {
    }
}
