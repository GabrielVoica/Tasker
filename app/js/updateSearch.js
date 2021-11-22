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


$(".home-content-next").click(function (e) {

  $.ajax({
    url: "",
    type: "POST",
    data: {
      next_page: true
    },
    cache: false,
    success: function (dataResult) {
      $('.task-list').html(dataResult);
    },
  });
});

$(".home-content-prev").click(function (e) {

  $.ajax({
    url: "",
    type: "POST",
    data: {
      prev_page: true
    },
    cache: false,
    success: function (dataResult) {
      $('.task-list').html(dataResult);
    },
  });
});

