<?php namespace Zingabory\Extendblog\Components;

use Cms\Classes\ComponentBase;
use Request;
use RainLab\Blog\Models\Category;

class ByCategory extends ComponentBase
{

    public function componentDetails()
    {
        return [
            'name' => 'byCategory Component',
            'description' => 'get post by category using ajax.'
        ];
    }

    public function defineProperties()
    {
        return [
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

    public function onGetByCategory()
    {
        if (Request::has('category'))
            $category = Request::input('category');
        else
            return;
        if (Request::has('numberOfPost'))
            $numberOfPost = Request::input('numberOfPost');
        else
            $numberOfPost = 4;

        $posts = Category::whereSlug($category)
            ->first()
            ->posts()
            ->isPublished()
            ->with('categories', 'tags')
            ->orderBy('published_at', 'desc')
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

}