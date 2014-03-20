<?php namespace Agency\Api\Controllers;

use Agency\Cms\Repositories\Contracts\PostRepositoryInterface;
use Agency\Cms\Repositories\Contracts\SectionRepositoryInterface;
use Agency\Cms\Repositories\Contracts\TagRepositoryInterface;

use Input, Response, File, DB;

use Agency\Cms\Post;
use Agency\Cms\Section;

use Agency\Api\Mappers\PostMapper;

class PostsController extends \Controller {

    public function __construct( PostRepositoryInterface $post,
    							 SectionRepositoryInterface $section,
    							 TagRepositoryInterface $tag)
    {
        $this->post = $post;
        $this->section = $section;
        $this->tag = $tag;
        $this->postMapper = new PostMapper();
    }

    public function index()
    {
        $posts = $this->post->allPublished();

        if(Input::has('category'))
        {   
            $section = $this->section->findBy('alias',Input::get('category'));
            $posts = $this->post->fromSection($posts,$section);

            // $posts = $posts->join('cms_sections', 'cms_sections.id','=','posts.section_id')->where('alias','=',Input::get('category'));
            // return dd($posts->first());
        }

        if(Input::has('tag'))
        {
            $posts=$posts->whereHas('tags',function($q){
                return $q->where('slug','=',Input::get('tag'));
            });
        }

    	if (Input::has('limit'))
    	{
            $posts = $posts->paginate((int)Input::get('limit'));
    	}else{

            $posts = $posts->get();
        }



        return dd($this->postMapper->make($posts)->toArray());

    }
}
