<?php

Nn::setDefaultController('publik');
Nn::setDefaultAction('index');

Nn::get('/admin\/nodes/','nodes');
Nn::post('/admin\/nodes/','nodes');
Nn::get('/admin\/roles/','roles');
Nn::post('/admin\/roles/','roles');
Nn::get('/admin\/users/','users');
Nn::post('/admin\/users/','users');
Nn::get('/admin\/files/','files');
Nn::post('/admin\/files/','files');
Nn::get('/admin\/settings/','settings');
Nn::post('/admin\/settings/','settings');
Nn::get('/admin\/nodetypes/','nodetypes');
Nn::post('/admin\/nodetypes/','nodetypes');
Nn::get('/admin\/attributes/','attributes');
Nn::post('/admin\/attributes/','attributes');
Nn::get('/admin\/attributetypes/','attributetypes');
Nn::post('/admin\/attributetypes/','attributetypes');
Nn::get('/admin\/documents/','documents');
Nn::post('/admin\/documents/','documents');
Nn::get('/admin\/images/','images');
Nn::post('/admin\/images/','images');
Nn::get('/admin\/texts/','texts');
Nn::post('/admin\/texts/','texts');
Nn::get('/admin\/partials/','partials');
Nn::post('/admin\/partials/','partials');
Nn::get('/admin\/feeds/','feeds');
Nn::post('/admin\/feeds/','feeds');
Nn::get('/admin\/forms/','forms');
Nn::post('/admin\/forms/','forms');
Nn::get('/admin\/timestamps/','timestamps');
Nn::post('/admin\/timestamps/','timestamps');
Nn::get('/admin/','admin');

# Default routes
Nn::get('/404/','def/notFound');
Nn::get('/500/','def/error');
Nn::get('/assets\/Image\/(\d+)\/(.*)/','def/thumbnail/\1/\2');

# Public routes
Nn::get('/mobile/','publik/mobile');
Nn::get('/(\w+)/','publik/index/$1');


// '/admin\/settings/' => 'settings',
// '/admin\/users/' => 'users',
// '/admin\/nodes/' => 'nodes',
// '/admin\/nodetypes/' => 'nodetypes',
// '/admin\/attributes/' => 'attributes',
// '/admin\/attributetypes/' => 'attributetypes',
// '/admin\/nodes\/make\/in\/(\d+)/' => 'nodes/make/\1',
// '/admin\/nodes\/view\/(\d+)\/(w)\/(\d+)/' => 'nodes/view/\1/\2/\3',
// '/admin\/documents/' => 'documents',
// '/admin\/images/' => 'images',
// '/admin\/texts/' => 'texts',
// '/admin\/timestamps/' => 'timestamps',
// '/admin\/comments/' => 'comments',
// '/zoom\/(\d+)/' => 'publik/_zoom/\1',
// '/(\d+)-(\w+)/' => 'publik/index/\1',
// '/(\d+)/' => 'publik/index/\1',
// '/category\/(\w+)/' => 'publik/index/\1',
// '/feed/' => 'publik/feed',
// '/mobile/' => 'publik/mobile',