<?php namespace Zingabory\ExtendBlog\Components;

use Cms\Classes\ComponentBase;
use Cms\Classes\Page;
use RainLab\Blog\Models\Post;
use Request;

class MostViewedPost extends ComponentBase
{

    public $mostViewedPost;

    public function componentDetails()
    {
        return [
            'name'        => 'MostViewedPost',
            'description' => 'Get most viewed post'
        ];
    }

    public function defineProperties()
    {
        return [
            'numberOfPost' => [
                'title'       => 'number of post to retrieve',
                'description' => 'number of post to retrieve',
                'default'     => 4,
                'type'        => 'string'
            ],
            'minimalView' => [
                'title'       => 'minimal View Number',
                'description' => 'minimal View Number',
                'default'     => 10,
                'type'        => 'string'
            ],
            'categoryPage' => [
                'title'       => 'rainlab.blog::lang.settings.posts_category',
                'description' => 'rainlab.blog::lang.settings.posts_category_description',
                'type'        => 'dropdown',
                'default'     => 'blog/blog-category',
                'group'       => 'Links'
            ],
            'postPage' => [
                'title'       => 'rainlab.blog::lang.settings.posts_post',
                'description' => 'rainlab.blog::lang.settings.posts_post_description',
                'type'        => 'dropdown',
                'default'     => 'blog/post',
                'group'       => 'Links'
            ]
        ];
    }


    public function getCategoryPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    public function getPostPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }



    public function onRun(){

        $this->mostViewedPost = $this->page['mostViewedPost'] = $this->loadMostViewedPost($this->property('numberOfPost'));

    }

    private function loadMostViewedPost($numberOfPost)
    {
        $posts = Post::isPublished()
                     ->where('view', '>', $this->property('minimalView'))
                     ->orderBy('view', 'desc')
                     ->take($numberOfPost)
                     ->get();

        /*
         * Add a "url" helper attribute for linking to each post and category
         */
        $posts->each(function ($post) {
            $post->setUrl($this->property('postPage'), $this->controller);

            $post->categories->each(function ($category) {
                $category->setUrl($this->property('categoryPage'), $this->controller);
            });
        });

        return $posts;
    }

    public function onGetMostViewedPost(){
        if(Request::has('numberOfPost'))
            $numberOfPost = Request::input('numberOfPost');
        else
            $numberOfPost = 4;
        return $this->loadMostViewedPost($numberOfPost);
    }
}