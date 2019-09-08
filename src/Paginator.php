<?php

namespace TarekAdam\PageStream;

class Paginator implements \Iterator{

	private $client;
	private $pointer = 0;
	private $page = 1;
	private $chunk = [];

	public function __construct(Paginatable $client){
		$this->client       = $client;
		$this->chunk        = $client->toArray();
	}

	public function current(){
		return $this->chunk[$this->pointer];
	}

	public function next(){
		$this->pointer++;
		if(!$this->valid() and $this->page < $this->client->totalPages()){
			$this->paginate();
		}
	}

	public function key(){
		return $this->pointer;
	}

	public function valid(){
		return !empty($this->chunk[$this->pointer]);
	}

	public function rewind(){
			$this->pointer = 0;

			if($this->page != 1){
				$this->paginate(1);
			}
	}


	private function paginate(int $x = null){

		$this->page = (!empty($x))? $x : $this->client->getPage() +1;

		if($this->page > $this->client->totalPages()){
			$this->chunk = [];
			return;
		}

		$this->client->setPage($this->page);
		$this->client->exchangeData();

		$this->chunk = $this->client->toArray();
		$this->pointer = 0;
	}
}