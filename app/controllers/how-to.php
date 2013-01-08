<?php

namespace How;

class To extends \Hbnro\App\Base
{

  static $responds_to = ['json'];

  function index()
  {
    assign('tracking', 'on');

    static::$title .= ' / Aprende a usarlo';

    $result = \Section::get('title', 'slug')->published();
    $this->pages = paginate_to(url_for('learn'), $result, params('p'), 33);
  }

  function section()
  {
    $test = params('thing');
    $slug = slugify($test);

    if ( ! is_slug($test)) {
      return redirect(url_for('show_section', $slug));
    } elseif ($found = \Section::first_by_slug($slug)) {
      assign('tracking', $found->indexed);

      static::$title .= " / $found->title";
      static::$head []= tag('meta', 'keywords', join(', ', $found->tags));

      if ( ! $found->indexed) {
        static::$head []= tag('meta', 'googlebot', 'noindex, nofollow, noarchive');
        static::$head []= tag('meta', 'robots', 'noindex, nofollow, noarchive');
      }

      $this->section = $found;
    } else {
      static::$status = 404;
    }
  }

}
