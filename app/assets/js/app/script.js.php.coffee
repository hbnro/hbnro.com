$ ->

  section = $('#content .the-section')

  field = (name) ->
    document.getElementsByName name

  $('input.tag').tagedit
    breakKeyCodes: [ 13, 44, 32 ]
    autocompleteOptions:
        disabled: true
    texts:
      removeLinkTitle: 'Quitar de la lista'
      saveEditLinkTitle: 'Guardar cambios'
      breakEditLinkTitle: 'Cancelar'
    allowDelete: false

  $('textarea.tab').each ->
    el = $(@)
    str = if el.data('sp') then (new Array(parseInt(el.data('sp'), 10) + 1)).join(' ') else "\t"
    el.keydown (e) ->
      key = e.which
      if key is 9 and not e.shiftKey and not e.ctrlKey and not e.altKey
        os = el.scrollTop()
        ta = el.get(0)
        if ta.setSelectionRange
          ss = ta.selectionStart
          se = ta.selectionEnd
          el.val el.val().substring(0, ss) + str + el.val().substr(se)
          ta.setSelectionRange ss + str.length, se + str.length
          e.returnValue = false
        else if ta.createTextRange
          document.selection.createRange().text = str
          e.returnValue = false
        else
          return true
        el.scrollTop os
        return false
      true

  $('.edit-mode', section).each ->

    el = $(@)

    content = $(field('content-editor')[0])

    title = section.find('.the-title');
    main = section.find('.the-body');

    toggle = el.find('.check-opt')
    edit = el.find('.edit-action')
    save = el.find('.save-action')

    tools = section.find('.tool')
    url = section.data('url')

    toggle.click ->
      el = $(@)
      $.post url,
        _method: 'put'
        action: 'toggle'
        field: el.attr('name')
        value: if el.is(':checked') then 'on' else ''
      , 'json'

    edit.click ->
      main.hide().empty()
      edit.hide()
      save.show()

      $.getJSON url, (data) ->
        content.html(data.body)
        content.addClass('expand260-1000').TextAreaExpander()
        tools.show()

    save.click ->
      set = []

      for tag in field('tag[]')
        set.push tag.value if tag.value

      $.post url,
        _method: 'put'
        content: content.val()
        action: 'update'
        title: field('title')[0].value
        tags: set or []
      , (data) ->
        if data.error
          console.log data.error
        else
          title.text(data.title) if data.title.length
          main.html(data.body).show()
          content.empty()

          tools.hide()
          save.hide()
          edit.show()
      , 'json'

    el.show()

  $('#admin-menu menuitem').click ->
    document.location.href = "<?php echo url_for('sections'); ?>"

  $('#logout-menu menuitem').click ->
    $.post "<?php echo url_for('logout'); ?>", _method: 'delete', (data) ->
      document.location.href = "<?php echo url_for('root'); ?>"
