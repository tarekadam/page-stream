<?php

namespace TarekAdam\PageStream;

class Paginator implements \Countable, \Iterator{

	private $client;
	private $page = 1;
	private $pages = 1;
	private $max_per_page = 100;
	private $pointer = 0;
	private $size = 0;
	private $chunk = [];

	public function __construct(Paginatable $client, int $max_per_page = 100){
		$this->client = $client;
		$this->max_per_page = $max_per_page;

		if($this->client->getPage() != 1){
			$this->pointer = (($this->client->page - 1) * $this->max_per_page);
		}
	}

	private function pagePointer(){
		return ($this->pointer - ($this->max_per_page * ($this->page - 1)));
	}

	public function current(){
		return $this->chunk[$this->pagePointer()];
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
		return !empty($this->chunk[$this->pagePointer()]);
	}

	public function rewind(){
		$this->pointer = 0;
		if($this->page != 1){
			$this->paginate(1);
		}
	}

	public function count(){
		return $this->size;
	}


	private function paginate(int $x = null){

		if(!empty($x)){
			$this->client->setPage($x);
			return;
		}

		$client_page = $this->client->getPage();
		if(empty($client_page)){
			$this->client->setPage($this->page);
		}

		$this->client->exchangeData();
	}
}