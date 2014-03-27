<?php namespace Agency\Cms\Controllers;

use Agency\Cms\Validators\SectionValidator;
use Agency\Cms\Exceptions\UnauthorizedException;

use Agency\Cms\Validators\Contracts\PostValidatorInterface;
use Agency\Cms\Validators\Contracts\TagValidatorInterface;

use Agency\Repositories\Contracts\SectionRepositoryInterface;
use Agency\Repositories\Contracts\PostRepositoryInterface;
use Agency\Repositories\Contracts\ImageRepositoryInterface;
use Agency\Repositories\Contracts\VideoRepositoryInterface;
use Agency\Repositories\Contracts\TagRepositoryInterface;

use Agency\Media\Photos\UploadedPhoto;
use Agency\Media\Photos\UploadedPhotosCollection;
use Agency\Media\Photos\Contracts\ManagerInterface;
use Agency\Media\Photos\Contracts\StoreInterface;

use Agency\Post;

use Agency\Cms\Tag;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Agency\Helper;


use View,Input,App,Session,Auth,Response,Redirect;

class PostController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	

    public function __construct(SectionRepositoryInterface $sections,
    							PostRepositoryInterface $post,
    							ImageRepositoryInterface $image,
    							ManagerInterface $manager,
    							VideoRepositoryInterface $video,
    							TagRepositoryInterface $tags,
    							TagValidatorInterface $tagValidator,
    							PostValidatorInterface $validator,
    							StoreInterface $store)
    {
        parent::__construct($sections);

		$this->posts             = $post;
		$this->manager          = $manager;
		$this->images           = $image;
		$this->video            = $video;
		$this->validator    	= $validator;
		$this->sections         = $sections;
		$this->tags              = $tags;
		$this->tagValidator     = $tagValidator;
		$this->store            = $store;
    }

	public function index()
	{
		
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{

		if($this->admin_permissions->has("create"))
		{

			$edit_post=null;

			$contents = $this->sections->infertile($this->cms_sections['current']->alias);

			return View::make("cms.pages.post.create",compact("edit_post",'contents'));
		}

		throw new UnauthorizedException;

	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		if($this->admin_permissions->has("create"))
		{
			if($this->validator->validate(Input::all()))
			{
			  	$slug = $this->posts->uniqSlug( Input::get('title') );
				$body = Helper::cleanHtml(Input::get('body'));
				
				$section = $this->sections->findBy('alias',Input::get('section'));


				$post = $this->posts->create(Input::get('title'),$slug,$body,Auth::user()->id,$section->id,Input::get('publish_date'),Input::get('publish_state'));

				$this->save($post->id);

			
				return Response::json($post);

			} else {
				return Response::json(['status'=>400,"message"=>$this->validator->messages()]);
			}

		}

		throw new UnauthorizedException;
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  string  $slug
	 * @return Response
	 */
	public function show($slug)
	{
		if($this->admin_permissions->has("read"))
		{
			try {

				$post = $this->posts->findBy("slug",$slug);
				$section = $post->section()->first();

				//get all parent sections
				$parent_sections = $this->sections->parentSections($section->alias,$this->cms_sections['current']->id);
				$gallery = $post->media()->get();
				
				$media=[];
				foreach ($gallery as $value) {
					array_push($media, $value->media);					
				}


				$tags = $post->tags()->get();

				return View::make('cms.pages.post.show',compact('post','media','parent_sections','tags'));

			} catch (Exception $e) {
				return Response::json(['message'=>$e->getMessage()]);
			}
			
		}

		throw new UnauthorizedException;
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  string  $slug
	 * @return Response
	 */
	public function edit($slug)
	{
		if($this->admin_permissions->has("update"))
		{

			try {

				$post=$this->posts->findBy("slug",$slug);
				$media = $post->media()->get();
				$media_array=[];
				foreach ($media as $value) {
					array_push($media_array, $value->media);
				}

				$tags = $post->tags()->get()->fetch('text')->toArray();

				$contents = $this->sections->infertile($this->cms_sections['current']->alias);
				return View::make("cms.pages.post.edit",["edit_post"=>$post,'contents'=>$contents,'tags'=>$tags,'media'=>$media_array]);

				
			} catch (Exception $e) {
				return Response::json(['message'=>$e->getMessage()]);
			}
			
		}

		throw new UnauthorizedException;		
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		if($this->admin_permissions->has("update"))
		{

			if($this->validator->validate(Input::all()))
			{
				$body = Helper::cleanHtml(Input::get('body'));

				$slug = $this->posts->uniqSlug(Input::get('title'));

				$section = $this->sections->findBy('alias', Input::get('section'));

				if ($section)
				{

					$updated = $this->posts->update($id,
												Input::get("title"),
												$slug,
												$body,
												Auth::user()->id,
												$section->id,
												Input::get('publish_date'),
												Input::get('publish_state'));

					if ($updated)
					{
						$deleted_images = Input::get('deleted_images');

						if( ! empty($deleted_images))
						{
							$deleted_images = explode(',', $deleted_images);

							$this->images->remove($deleted_images);
							$this->posts->detachImages($deleted_images);
						}

						$this->save($id);

						
					}
				}
			
				return Response::json($post);
			} else {
				//display error

			}

		}

		throw new UnauthorizedException;
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($slug)
	{
		if($this->admin_permissions->has("delete"))
		{
			try {

				$post = $this->posts->findByIdOrSlug($slug);

				$section = $post->section()->get()->first();

				if($this->posts->remove($post->id))
				{
					return Redirect::route("cms.content.show",$section->alias);	
				}

			} catch (Exception $e) {
				return Response::json(['message'=>$e->getMessage()]);
			}
			
		}

		throw new UnauthorizedException;
	}

	public function removeImage($post_id, $image_id)
	{
		try {

			$this->posts->detachImage($post_id, $image_id);

			$image = $this->images->delete($image_id);

		} catch (Exception $e) {

			return Response::json(['messages' => $e->getMessages()]);
		}
		
	}

	public function save($post_id)
	{

		$tags = explode(', ', Input::get('tags'));
		// filter empty tags
		$tags = array_filter($tags);
		// get tags ids
		$tags = $this->tags->splitFound($tags);
		
		// add new tags to post
		$new_tags = $this->posts->addTags($post_id, $tags['new'], $tags['existing']);

		


		// upload images
		if(Input::has('croped_images_array'))
		{
			$photos = new UploadedPhotosCollection;

		 	$crop_sizes = json_decode(Input::get('croped_images_array'));

		 	if(Input::has('images'))
		 	{
		 		$images = Input::get('images');

		 		foreach ($images as $key => $image) {

		 			$image = new UploadedFile(public_path()."/tmp/$image", $image);
					$crop_size = get_object_vars($crop_sizes[$key]);

				 	$photo = UploadedPhoto::make($image, $crop_size)->validate();
        			$photos->push($photo);
				}

				$response = $this->manager->upload($photos,'artists/webs');

				$response = $response->toArray();

				$images = $this->images->prepareToStore($response);


				$this->posts->addImages($post_id, $images);

				// foreach ($response as $response) {

				// 	$image = $this->images->create(	$response['original'],
				// 									$response['thumbnail'],
				// 									$response['small'],
				// 									$response['square']);

				// 	$image->media()->create(["post_id"=>$post_id]);


				// }

				// for ($i=0 ; $i < sizeof($crop_sizes) ; $i++ ) { 
				// 	$this->store->remove($crop_sizes[$i]->name);
				// }

		 	}
		}

		$videos = json_decode(Input::get('videos'));

		$this->posts->detachAllVideos($post_id);

		foreach ($videos as $video) {
			if($this->video->validateYoutubeUrl($video->url))
			{
				$v = $this->video->create($video->title,$video->url,$video->desc,$video->src);
				$v->posts()->create(["post_id"=>$post->id]);
			}
		}

	}




}