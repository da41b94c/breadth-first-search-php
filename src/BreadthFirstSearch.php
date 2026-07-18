<?php

declare(strict_types=1);

namespace NoviKey\Graph;

use InvalidArgumentException;
use SplQueue;

final class BreadthFirstSearch
{
	/** @var array<string, list<string>> */
	private array $Graph;

	/**
	 * @param array<string, list<string>> $Graph
	 */
	public function __construct(array $Graph)
	{
		$this->Graph = $this->ValidateGraph($Graph);
	}

	public function HasPath(string $From, string $To): bool
	{
		return $this->FindPath($From, $To) !== [];
	}

	/**
	 * Returns the shortest path, including the start and destination nodes.
	 *
	 * @return list<string>
	 */
	public function FindPath(string $From, string $To): array
	{
		if (!isset($this->Graph[$From]) || !isset($this->Graph[$To])) {
			return [];
		}

		if ($From === $To) {
			return [$From];
		}

		$Queue = new SplQueue();
		$Queue->enqueue($From);

		$Visited = [$From => true];
		$Previous = [];

		while (!$Queue->isEmpty()) {
			$Current = $Queue->dequeue();

			foreach ($this->Graph[$Current] as $Neighbour) {
				if (isset($Visited[$Neighbour])) {
					continue;
				}

				$Visited[$Neighbour] = true;
				$Previous[$Neighbour] = $Current;

				if ($Neighbour === $To) {
					return $this->BuildPath($Previous, $From, $To);
				}

				$Queue->enqueue($Neighbour);
			}
		}

		return [];
	}

	/**
	 * @param array<string, string> $Previous
	 * @return list<string>
	 */
	private function BuildPath(array $Previous, string $From, string $To): array
	{
		$Path = [$To];
		$Current = $To;

		while ($Current !== $From) {
			$Current = $Previous[$Current];
			$Path[] = $Current;
		}

		return array_reverse($Path);
	}

	/**
	 * @param array<string, list<string>> $Graph
	 * @return array<string, list<string>>
	 */
	private function ValidateGraph(array $Graph): array
	{
		foreach ($Graph as $Node => $Neighbours) {
			if (!is_string($Node) || $Node === '') {
				throw new InvalidArgumentException('Every graph node must have a non-empty string name.');
			}

			if (!is_array($Neighbours)) {
				throw new InvalidArgumentException(sprintf('Neighbours of "%s" must be an array.', $Node));
			}

			foreach ($Neighbours as $Neighbour) {
				if (!is_string($Neighbour) || !array_key_exists($Neighbour, $Graph)) {
					throw new InvalidArgumentException(sprintf(
						'Node "%s" references unknown neighbour "%s".',
						$Node,
						is_scalar($Neighbour) ? (string) $Neighbour : get_debug_type($Neighbour)
					));
				}
			}
		}

		return $Graph;
	}
}
