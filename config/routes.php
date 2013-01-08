<?php

// get.hbnro.com
root(function () {
  return [200, ['Content-Type' => 'text/plain'], partial('_/install.txt')];
}, [
  'path' => 'get_sh',
  'subdomain' => 'get',
]);


// home
root('how-to#section', [
  'path' => 'root',
  'subdomain' => '',
]);


// auth
delete('/login', 'auth#clear_session', ['path' => 'logout']);
get('/login', 'auth#show_form', ['path' => 'login']);
put('/login', 'auth#verify_password');


// REST
mount(function () {
  post('/*thing', 'sections#create', ['path' => 'create_section']);
  get('/@id.@format', 'sections#fetch', ['path' => 'fetch_section']);
  put('/@id.@format', 'sections#update', ['path' => 'update_section']);
}, [
  'root' => '/',
  'no-cache' => TRUE,
  'before' => 'require_login',
  'constraints' => ['@format' => 'json', '@id' => '[a-f\d]{24}'],
]);


// admin
mount(function () {
  root('sections#index', ['path' => 'sections']);
  delete('/:id', 'sections#delete', ['path' => 'delete_section']);
}, [
  'root' => '/index',
  'no-cache' => TRUE,
  'before' => 'require_login',
]);


// paginas
get('/how-to', 'how-to#index', ['path' => 'learn']);
get('/*thing', 'how-to#section', ['path' => 'show_section']);
