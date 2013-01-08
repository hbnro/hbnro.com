<?php

class Auth extends \Hbnro\App\Base
{

  function show_form()
  {
    static::$title .= ' / ¡Entrale cabrón!';
  }

  function clear_session()
  {
    session('auth_data', NULL);
    return redirect();
  }

  function verify_password()
  {
    if (params('password') === getenv('PASS')) {
      session('auth_data', ['yes']);
      return redirect_to('sections');
    }
    return redirect_to('login');
  }

}
