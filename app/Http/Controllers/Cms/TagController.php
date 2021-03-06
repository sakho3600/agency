<?php namespace Agency\Http\Controllers\Cms;

use Input, Response, Redirect, Lang;

use Agency\Cms\Repositories\Contracts\RoleRepositoryInterface as Roles;
use Agency\Repositories\Contracts\SectionRepositoryInterface;
use Agency\Contracts\Repositories\TagRepositoryInterface;
use Agency\Cms\Validators\Contracts\RoleValidatorInterface as RoleValidator;

use Agency\Cms\Exceptions\UnauthorizedException;

class TagController extends Controller {

	public function __construct(TagRepositoryInterface $tag)
    {
		$this->tag 				= $tag;
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return Response::json(['tags'=>$this->tag->all()->fetch('text')->toJson()]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{

	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


}
