<?php


Breadcrumbs::register('home', function($breadcrumbs)
{
    $breadcrumbs->push('Home', route('/'));
});

Breadcrumbs::register('/', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Jobs', route('jobs'));
});

Breadcrumbs::register('jobs', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Jobs', route('jobs'));
});

Breadcrumbs::register('followups', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Followups', route('followups'));
});
Breadcrumbs::register('meetings', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Meetings', route('meetings'));
});

Breadcrumbs::register('admin', function($breadcrumbs)
{
    $breadcrumbs->push('Admin / Notifications', route('admin'));
});
Breadcrumbs::register('admin/user/list', function($breadcrumbs)
{
    $breadcrumbs->parent('admin');
    $breadcrumbs->push('Users', route('users'));
});

Breadcrumbs::register('admin/meetings', function($breadcrumbs)
{
    $breadcrumbs->parent('admin');
    $breadcrumbs->push('Meetings', route('adminmeetings'));
});


Breadcrumbs::register('profile', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Profile', route('profile'));
});
// Home > Blog > [Category]
Breadcrumbs::register('category', function($breadcrumbs, $category)
{
    $breadcrumbs->parent('blog');
    $breadcrumbs->push($category->title, route('category', $category->id));
});

// Home > Blog > [Category] > [Page]
Breadcrumbs::register('page', function($breadcrumbs, $page)
{
    $breadcrumbs->parent('category', $page->category);
    $breadcrumbs->push($page->title, route('page', $page->id));
});
?>