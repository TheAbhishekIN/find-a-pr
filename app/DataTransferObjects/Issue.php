<?php

declare(strict_types=1);

namespace App\DataTransferObjects;

use Carbon\Carbon;
use Carbon\CarbonInterface;

final readonly class Issue
{
    public int $interactionsCount;

    public function __construct(
        public int $id,
        public int $number,
        public string $repoName,
        public string $repoUrl,
        public string $title,
        public string $url,
        public ?string $body,
        public array $labels,
        public array $reactions,
        public int $commentCount,
        public CarbonInterface $createdAt,
        public IssueOwner $createdBy,
        public bool $isPullRequest,
    ) {
        // TODO Add types here
        $this->interactionsCount = collect($this->reactions)->reduce(fn ($carry, $reaction) => $reaction->count + $carry, $this->commentCount);
    }

    public static function fromArray(array $issueDetails): self
    {
        $issueDetails['createdAt'] = Carbon::parse($issueDetails['createdAt']);
        $issueDetails['createdBy'] = IssueOwner::fromArray($issueDetails['createdBy']);
        $issueDetails['labels'] = Label::multipleFromArray($issueDetails['labels']);
        $issueDetails['reactions'] = Reaction::multipleFromArray($issueDetails['reactions']);

        unset($issueDetails['interactionsCount']);

        return new self(...$issueDetails);
    }
}
