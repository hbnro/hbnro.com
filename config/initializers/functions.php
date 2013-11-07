<?php

function is_logged()
{
  return !! session('auth_data');
}

function require_login(array $params)
{
  is_logged() OR $params['to'] = redirect_to('login');
  return $params;
}
