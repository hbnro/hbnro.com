<?php

namespace How;

class To extends \Hbnro\App\Base
{

  function tags()
  {
    $name = params('tag');

    assign('tracking', 'on');

    static::$title .= ' / Buscar temas';
    static::$head []= tag('meta', 'description', "Aprende a usar Habanero. Listado de artículos por tema en común: $name");

    $result = \Section::get('title', 'slug')->published()->where(['tags' => $name]);
    $this->pages = paginate_to(url_for('filter', [':tag' => $name]), $result, params('p'), 33);
  }

  function index()
  {
    assign('tracking', 'on');

    static::$title .= ' / Aprende a usarlo';
    static::$head []= tag('meta', 'description', 'Aprende a usar Habanero. En esta sección puedes encontrar ejemplos, recursos y todo lo necesario para comenzar. ¡Recomendado!');

    $result = \Section::get('title', 'slug')->published();
    $this->pages = paginate_to(url_for('/how-to'), $result, params('p'), 33);
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
      static::$head []= tag('meta', 'description', $found->description);

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
