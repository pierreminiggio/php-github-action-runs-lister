<?php

namespace PierreMiniggio\GithubActionRunsLister;

use PierreMiniggio\GithubActionRun\GithubActionRun;
use PierreMiniggio\GithubActionRunsLister\Exception\NotFoundException;
use PierreMiniggio\GithubActionRunsLister\Exception\UnknownException;
use PierreMiniggio\GithubUserAgent\GithubUserAgent;
use RuntimeException;

class GithubActionRunsLister
{

    /**
     * @throws NotFoundException
     * @throws RuntimeException
     */
    public function list(
        string $owner,
        string $repo,
        string $workflowIdOrWorkflowFileName
    ): RunListerResponse
    {

        $curl = curl_init("https://api.github.com/repos/$owner/$repo/actions/workflows/$workflowIdOrWorkflowFileName/runs");
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_USERAGENT => GithubUserAgent::USER_AGENT
        ]);

        $response = curl_exec($curl);

        if ($response === false) {
            throw new RuntimeException('Curl error' . curl_error($curl));
        }

        $jsonResponse = json_decode($response, true);

        if ($jsonResponse === null) {
            throw new RuntimeException('Bad Github API return : Bad JSON');
        }

        if (! empty($jsonResponse['message'])) {
            $message = $jsonResponse['message'];

            if ($message === 'Not Found') {
                throw new NotFoundException();
            }

            throw new UnknownException($message);
        }

        if (! isset($jsonResponse['total_count'])) {
            throw new RuntimeException('Bad Github API return : "total_count" missing');
        }

        $totalCount = (int) $jsonResponse['total_count'];

        if ($totalCount === 0) {
            return new RunListerResponse([], 0);
        }

        if (! isset($jsonResponse['workflow_runs'])) {
            throw new RuntimeException('Bad Github API return : "workflow_runs" missing');
        }

        return new RunListerResponse(
            array_map(fn (array $fetchedRun): GithubActionRun => new GithubActionRun(
                (int) $fetchedRun['id'],
                $fetchedRun['status'],
                $fetchedRun['conclusion']
            ), $jsonResponse['workflow_runs']),
            $totalCount
        );
    }
}
