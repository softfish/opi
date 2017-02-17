<?php

namespace App\Services;

use App\Http\Controllers\Controller;

abstract class BaseModelService extends Controller{

	protected static $baseTable;

	abstract public function create(array $data);
	
	abstract public function view(int $id);
	
	abstract public function update(array $data);

	/**
	 *
	 * Provide a list of item with pagination limit
	 * 
	 * @param array $filters
	 * @param int $paginationLimit
	 * @return \Illuminate\Pagination\Paginator 
	 *
	 */
	protected function list(array $filters=null, int $paginationLimit=20) : \Illuminate\Pagination\Paginator
	{
		$query = \DB::table($this->baseTable);

		$query = $this->applyFilters($query, $filters);

		$orders = $query->paginate($paginationLimit);

		return $orders;
	}

	protected function applyFilter($query, array $filters)
	{
		if (!empty($filters)){
			foreach ($filters as $filter) {
				switch (strtolower($filter['operator'])) {
					case '=':
					case '>':
					case '<':
						$query->where($filter['field_name'], $filter['operator'], $filter['value']);
						break;
					case 'in':
					case 'not in':
						if (is_array($filter['value'])) {
							$query->where($filter['field_name'], $filter['operator'], $filter['value']);
						} else {
							throw new Exception('Invalid match for operator and value... array value is expected.');
						}
					default:
						throw new Exception('Operator is required in a filter');
				}
			}
		}

		return $query;
	}
}