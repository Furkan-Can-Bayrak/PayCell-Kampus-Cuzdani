<?php

namespace App\Services\Contracts;

use App\Models\Split;
use Illuminate\Support\Collection;

interface SplitServiceInterface
{
    public function createSplitRequest(int $transactionId, int $requesterId, array $friends, string $shareType = 'equal'): Collection;
    public function acceptSplit(int $splitId, int $userId): array;
    public function rejectSplit(int $splitId, int $userId): Split;
    public function getUserPendingSplits(int $userId): Collection;
}
