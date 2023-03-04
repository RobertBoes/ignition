<?php

namespace Spatie\Ignition\Solutions\OpenAi;

use Psr\SimpleCache\CacheInterface;
use Spatie\Ignition\Contracts\HasSolutionsForThrowable;
use Throwable;

class OpenAiSolutionProvider implements HasSolutionsForThrowable
{
    public function __construct(
        protected string $openAiKey,
        protected ?CacheInterface $cache = null,
        protected int $cacheTtlInSeconds = 60 * 60,
        protected string|null $applicationType = null,
    ) {
        $this->cache ??= new DummyCache();
    }

    public function applicationType(string $applicationType): self
    {
        $this->applicationType = $applicationType;

        return $this;
    }

    public function useCache(CacheInterface $cache, int $cacheTtlInSeconds = 60 * 60): self
    {
        $this->cache = $cache;

        $this->cacheTtlInSeconds = $cacheTtlInSeconds;

        return $this;
    }

    public function canSolve(Throwable $throwable): bool
    {
        return true;
    }

    public function getSolutions(Throwable $throwable): array
    {
        return [
            new OpenAiSolution(
                $throwable,
                $this->openAiKey,
                $this->cache,
                $this->cacheTtlInSeconds,
            ),
        ];
    }
}
