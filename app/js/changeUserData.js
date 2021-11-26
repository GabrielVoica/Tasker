$(".change-username").keyup((e) => {
  let value = $(".change-username").val();

  $.ajax({
    url: "/perfil",
    type: "POST",
    data: {
      data: value,
    },
    cache: false,
  });
});

