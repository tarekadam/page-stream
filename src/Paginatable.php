<?php

namespace TarekAdam\PageStream;

interface Paginatable{

	public function toArray():array;
	public function exchangeData(): void;
	public function getPage(): int;
	public function setPage(int $page): void;
	public function totalPages(): int;

}