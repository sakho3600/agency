<?php namespace Agency;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use Illuminate\Support\ServiceProvider;

class AgencyServiceProvider extends ServiceProvider {

    public function register()
    {
        // register Media Service Provider
        $this->app->register('Agency\Media\MediaServiceProvider');

        // register Cms Service Provider
        $this->app->register('Agency\Cms\CmsServiceProvider');

        // register Api Service Provider
        $this->app->register('Agency\Api\ApiServiceProvider');

        // images
        $this->app->bind(
            'Agency\Repositories\Contracts\ImageRepositoryInterface',
            'Agency\Repositories\ImageRepository');
        $this->app->bind('Agency\Contracts\ImageInterface', 'Agency\Image');

        // sections
        $this->app->bind(
            'Agency\Repositories\Contracts\SectionRepositoryInterface',
            'Agency\Repositories\SectionRepository');

        // post tags
        $this->app->bind(
            'Agency\Repositories\Contracts\TagRepositoryInterface',
            'Agency\Repositories\TagRepository');

        // videos
        $this->app->bind(
            'Agency\Repositories\Contracts\VideoRepositoryInterface',
            'Agency\Repositories\VideoRepository');

        // posts
        $this->app->bind(
            'Agency\Repositories\Contracts\PostRepositoryInterface',
            'Agency\Repositories\PostRepository');

        // validators
        $this->app->bind(
            'Agency\Validators\Contracts\ImageValidatorInterface', function() {
                return new \Agency\Validators\ImageValidator($this->app->make('validator'));
            });

        $this->app->bind(
            'Agency\Validators\Contracts\PostValidatorInterface', function() {
                return new \Agency\Validators\PostValidator($this->app->make('validator'));
            });

        $this->app->bind(
            'Agency\Validators\Contracts\TagValidatorInterface', function() {
                return new \Agency\Validators\TagValidator($this->app->make('validator'));
            });

        $this->app->bind('Agency\Validators\SectionValidator', function(){
            return new \Agency\Validators\SectionValidator($this->app->make('validator'));
        });

        $this->app->bind(
           'Agency\Validators\Contracts\VideoValidatorInterface', function() {
               return new \Agency\Validators\VideoValidator($this->app->make('validator'));
           });

    }
}
