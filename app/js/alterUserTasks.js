$(".task-list").on("click", "input.add-task-input", function (event) {
   event.preventDefault();
  let input = event.target;
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

$(".task-result").on("click", "span", function (event) {
  let logs = document.querySelectorAll(".task-log");

  logs.forEach((log) => {
    log.style.display = "none";
  });
});


$(".task-list-wrapper").on("click", "button.delete-task", function (event) {
  let button = event.target;
  let id = button.value;
  console.log("Hello");

  $.ajax({
    url: "/tareas",
    type: "POST",
    data: {
      deleted_task_id: id,
    },
    cache: false,
    success: function (dataResult) {
      $(".task-list-wrapper").html(dataResult);
       window.location.reload();
    },
  });
});


$('.task-container').on('click','button.do-task',function(event){
  let button = event.target;
  let id = button.value;

  $.ajax({
    url: "/tareas",
    type: "POST",
    data: {
      do_task_id: id,
    },
    cache: false,
    success: function (dataResult) {
      window.location.reload();
    },
  });
})