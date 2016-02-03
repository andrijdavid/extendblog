<?php namespace Zingabory\ExtendBlog\Components;

use Cms\Classes\ComponentBase;
use Cms\Classes\Page;
use RainLab\Blog\Models\Post;

class RelatedPost extends ComponentBase
{

    public $relatedPost;

    public function componentDetails()
    {
        return [
            'name' => 'RelatedPost',
            'description' => 'Get related post'
        ];
    }

    public function defineProperties()
    {
        return [
            'slug' => [
                'title' => 'rainlab.blog::lang.settings.post_slug',
                'description' => 'rainlab.blog::lang.settings.post_slug_description',
                'default' => '{{ :slug }}',
                'type' => 'string'
            ],
            'filter' => [
                'title'       => 'Filter',
                'type'        => 'dropdown',
                'default'     => 'category',
                'placeholder' => 'Select units',
                'options'     => ['category'=>'By Category', 'tag'=>'By Tag']
            ],
            'numberOfPost' => [
                'title' => 'number of post to retrieve',
                'description' => 'number of post to retrieve',
                'default' => 4,
                'type' => 'string'
            ],
            'categoryPage' => [
                'title' => 'rainlab.blog::lang.settings.posts_category',
                'description' => 'rainlab.blog::lang.settings.posts_category_description',
                'type' => 'dropdown',
                'default' => 'blog/blog-category',
                'group' => 'Links'
            ],
            'postPage' => [
                'title' => 'rainlab.blog::lang.settings.posts_post',
                'description' => 'rainlab.blog::lang.settings.posts_post_description',
                'type' => 'dropdown',
                'default' => 'blog/post',
                'group' => 'Links'
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



    public function onRun()
    {
        $this->relatedPost = $this->page['relatedPost'] = $this->loadRelatedPost($this->property('numberOfPost'));
    }

    private function loadRelatedPost($numberOfPost)
    {
        $post = Post::isPublished()
                    ->where('slug', $this->property('slug'))
                    ->with('categories')
                    ->firstOrFail();

        if($this->property('filter') == 'tag')
        {
            $posts = $post->tags()
                ->first()
                ->posts()
                ->isPublished()
                ->with('categories')
                ->orderBy('published_at', 'desc')
                ->take($numberOfPost)
                ->get();
        }
        else
        {
            $posts = $post
                ->categories()
                ->first()
                ->posts()
                ->isPublished()
                ->with('categories')
                ->orderBy('published_at', 'desc')
                ->take($numberOfPost)
                ->get();
        }

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
}