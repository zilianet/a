$("form.ajaxForm").submit(e => { e.preventDefault(); return false;});

$( ".checkFormBtn" ).click(() => {
  if (!$("form.ajaxForm").get(0).checkValidity()) return;
  $('form.ajaxForm .border-danger').removeClass('border-danger');
  $('form.ajaxForm .errorlist').remove()
  $.post(window.location.pathname, $("form.ajaxForm").serialize(), data=>{
    Object.keys(data).forEach(key => {
        console.log(key);
        if (key == 'is_valid') window.location.replace(data[key]);
        $('input[name=' + key + ']').addClass('border-danger')
          .parent().before($('<div class="errorlist"/>').html(data[key]));
    });
  });
});

$(document).on('click', '.cart_add', e => { let $obj = $(e.currentTarget), id = $obj.data('id');
  $.get('cart_change.php?type=add&id=' + id, {}, data => { $obj.prev().html(data);
    if ($obj.html() == 'В корзину'){ $obj.html('+');
      $obj.prev().before($('<button class="btn btn-success cart_sub"/>').data({id: id}).html('-'), "\n");
    }
  });
});

$(document).on('click', '.cart_sub', e => { let $obj = $(e.currentTarget);
  $.get('cart_change.php?type=sub&id=' + $obj.data('id'), {}, data => { $obj.next().html(data)});
});

$('.tovary img.rounded-start, .tovary span.name_tovar').click(e => { let $obj = $(e.currentTarget);
  window.location.replace('tovar.php?id=' + $obj.data('id'));
});




// let idOrder;
// $('#delOrderModal').on('shown.bs.modal', function (e) { idOrder = $(e.relatedTarget).data('id')})
// $('.delOrderBtn').click(function(e){ window.location.replace('/delete_order/' + idOrder)});

