$(".task-list").on("click", "input.add-task-input", function (event) {
  event.preventDefault();
  let input = event.target;
  let id = input.value;

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

$(".task-list").on("click", "button.delete-task", function (event) {
  event.preventDefault();
  let input = event.target;
  let id = input.value;

  $.ajax({
    url: "",
    type: "POST",
    data: {
      delete_task_id: id,
    },
    cache: false,
    success: function (dataResult) {
      $(".task-list").html(dataResult);
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

  let modal = document.createElement("div");

  $.ajax({
    url: "/tareas",
    type: "POST",
    data: {
      deleted_task_id: id,
    },
    cache: false,
    success: function (dataResult) {
      $(".task-list-wrapper").html(dataResult);
    },
  });
});

$(".task-container").on("click", "button.do-task", function (event) {
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
      if (dataResult.includes("No tienes suficientes taskies")) {
        $modalContent = "No tienes suficientes taskies";
        dataResult = dataResult.replace("No tienes suficientes taskies", "");
      } else {
        $modalContent = "Tarea realizada";
      }

      $(".user-tasks-page").html(dataResult);

      let modal = document.createElement("div");
      let modalText = document.createElement("div");
      modalText.innerText = $modalContent;
      modalText.classList.add("modal-text");
      modal.appendChild(modalText);
      modal.classList.add("task-modal");
      document.querySelector(".user-tasks-page").appendChild(modal);

      setTimeout(() => {
        modal.style.display = "none";
      }, 1000);
    },
  });
});
