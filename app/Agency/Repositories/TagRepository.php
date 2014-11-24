<?php  namespace Agency\Repositories;

use DB;
use Agency\Tag;
use Agency\Helper;
use Agency\Contracts\Repositories\TagRepositoryInterface;
use Agency\Contracts\HelperInterface;

class TagRepository extends Repository implements TagRepositoryInterface {

	/**
	 * the tag model
	 *
	 * @var Agency\Tag
	 */
	protected $tag;

	public function __construct(Tag $tag,
								HelperInterface $helper)
	{
		$this->tag = $this->model = $tag;
		$this->helper = $helper;
	}

	public function create($text)
	{
		$slug = $this->helper->slugify($text, $this->tag);
		$this->tag = $this->tag->firstOrCreate(compact("text","slug"));
		return $this->tag;
	}

	public function splitFound($tags)
	{
		// generate slugs
		$tags = array_map(function($text) {
			$slug = $this->helper->slugify($text);
			return compact('text', 'slug');
		}, $tags);

		// extract existing tags
		$slugs = array_map(function($tag){
			return $tag['slug'];
		}, $tags);

		$existing = $this->tag->whereIn('slug', $slugs)->get();
		$existing_slugs = $existing->lists('slug');


		$new_tags = array_filter($tags, function($tag) use($existing_slugs) {
			return ! in_array($tag['slug'], $existing_slugs);
		});

		// create tag models out of the new ones
		$new_tags = array_map(function($tag){
			return new Tag($tag);
		}, $new_tags);


		$existing = $existing->lists('id');

		return [
			'new' => $new_tags,
			'existing' => $existing
		];
	}

}
