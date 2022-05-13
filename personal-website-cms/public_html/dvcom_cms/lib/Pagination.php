<?php

class Pagination
{
	protected $model;
	
	protected $criteria;
	
	protected $entryVarName = 'entry';
	
	protected $entryView;
	
	protected $baseUrl = [];
	
	protected $paginationTemplate;
	
	protected $pageSize;
	
	protected $currentPage; // one based
	
	public function __construct(Model $model, Criteria $criteria, $entryView = '', $baseUrl = [], $pageSize = 10, $paginationTemplate = 'pagination')
	{
		$this->model = $model;
		$this->criteria = $criteria;
		$this->entryView = $entryView;
		$this->baseUrl = $baseUrl;
		$this->pageSize = $pageSize;
		$this->paginationTemplate = $paginationTemplate;
	}
	
	
	public function render()
	{
		$this->currentPage = cms()->request->get_int('page', 1);
		
		$html = '';
		$total = $this->model->count($this->criteria);
		
		if ($total <= $this->pageSize)
		{
			$models = $this->model->findAll($this->criteria);
			foreach ($models as $model)
			{
				$html .= cms()->load_view($this->entryView, [$this->entryVarName => $model]) . PHP_EOL;
			}
		}
		else
		{
			$this->criteria->pageSize = $this->pageSize;
			$this->criteria->pageNo = $this->currentPage;
			
			$models = $this->model->findAll($this->criteria);
			foreach ($models as $model)
			{
				$html .= cms()->load_view($this->entryView, [$this->entryVarName => $model]) . PHP_EOL;
			}
			
			$html .= cms()->load_template($this->paginationTemplate, [
				'currentPage' => $this->currentPage,
				'totalPages' => ceil($total / $this->pageSize),
				'baseUrl' => $this->baseUrl,
			]);
		}
		
		return $html;
	}
	
}