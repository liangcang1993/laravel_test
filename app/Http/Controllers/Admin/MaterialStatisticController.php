<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

use App\Models\MaterialStatistic;


use Validator;
use Input;
use FileTool;
use Storage;


class MaterialStatisticController extends AdminBaseController
{
	protected $modelName = 'materialStatistic';

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$filter = [
			'id' => Input::get('id'),
			'sort' => Input::get('sort'),
		];
		$list   = MaterialStatistic::getPageQuery($filter);

		return $this->render(
			'admin.' . $this->modelName . '_index',
			[
				'list' => $list,
				'filter' => $filter,
				'modelName' => $this->modelName,
			]
		);

	}

	public function countsIndex()
	{
		$filter = [
			'id' => Input::get('id'),
			'sort' => Input::get('sort'),
		];

		$list = MaterialStatistic::getPageQuery($filter);

		return $this->render(
			'admin.MaterialStatistic',
			[
				'list' => $list,
				'filter' => $filter,
				'modelName' => $this->modelName,
			]
		);

	}

	public function manlyPaidIndex()
	{
		$filter = [
			'id' => Input::get('id'),
			'sort' => Input::get('sort'),
		];

		$list = MaterialPaidStatistic::getPageQuery($filter);

		return $this->render(
			'admin.manlyMaterialPaidStatistic',
			[
				'list' => $list,
				'filter' => $filter,
				'modelName' => $this->modelName,
			]
		);

	}

	public function paidIndex()
	{
		$filter = [
			'id' => Input::get('id'),
			'sort' => Input::get('sort'),
		];

		$list = MaterialPaidStatistic::getPageQuery($filter);

		return $this->render(
			'admin.materialPaidStatistic',
			[
				'list' => $list,
				'filter' => $filter,
				'modelName' => $this->modelName,
			]
		);

	}
}
