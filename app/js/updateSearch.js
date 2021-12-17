$(".search-bar").keyup(function (e) {

  let value = $('.search-bar').val();

  $.ajax({
    url: "",
    type: "POST",
    data: {
      data: value
    },
    cache: false,
    success: function (dataResult) {
      $('.task-list').html(dataResult);
    },
  });
});


