<?php

function md($text)
{
  static $obj = NULL;

  ($obj === NULL) && $obj = new \dflydev\markdown\MarkdownExtraParser;

  return $obj->transformMarkdown($text);
}

function is_logged()
{
  return !! session('auth_data');
}

function require_login(array $params)
{
  is_logged() OR $params['to'] = redirect_to('login');
  return $params;
}
