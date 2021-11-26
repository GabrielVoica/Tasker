$(".add-task-input").click((e) => {
  e.preventDefault();
  let input = e.target;
  let id = input.value;
  console.log("hello");

  $.ajax({
    url: "",
    type: "POST",
    data: {
      task_id: id,
    },
    cache: false,
    success: function (dataResult) {
      $(".task-result").html(dataResult);
    },
  });
});



$(".task-result").on("click", "span", function(event){
    let logs = document.querySelectorAll('.task-log');

    logs.forEach(log =>{
      log.style.display = 'none';
    })
});
