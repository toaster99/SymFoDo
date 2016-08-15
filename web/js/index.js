// setup an "add a tag" link
var $addTaskLink = $('<a href="#" class="add_task_link">Add a task</a>');
var $newLinkLi = $('<li></li>').append($addTaskLink);

//For monitoring keypresses
var lastKeyPress = -1;
//The delay between keypresses for saving (in ms)
var KEYPRESS_DELAY = 500;

//For check all state
var markAllAsCompleted = true;

jQuery(document).ready(function() {
    // Set the todo list variable
    var todoList = $("#todoList");
    //Set the add-task field
    var addTaskField = $("#addTask");
    //Set the form variable
    var todoListForm = $('form[name="todo"]');
    //Get the template for adding new tasks
    var taskTemplate = todoList.data('prototype');

    // Get the ul that holds the collection of tags
   var $collectionHolder = $('ul.tasks');

    //Detect enter key on add-task field
    addTaskField.keypress(function(e) {
        if(e.which == 13) {                                 //13 is code for enter key
            //Grab the task value and clear the field
            var taskName = addTaskField.val();
            addTaskField.val('');

            //Add the task
            addTodoTask(taskName);
        }
    });

    function addTodoTask(taskName) {
        $.ajax({
          type: "POST",
          url: "/todo/add",
          data: {
            "name": taskName
          },
          success: function(response) {
            refreshTasks(false);
          },
        });
    }
});

function filterTodo(caller,filterType='all') {
    $("#todo-menu-list").find("li").removeClass("active");
    $(caller).addClass("active");

    refreshTasks();
}

function refreshTasks(animation = true) {
    var filterMethod = $($("#todo-menu-list").find(".active")).attr("data-filter");

    var url = "/todo/" + filterMethod;

    $.ajax({
        type: "POST",
        url: url,
        success: function(response) {
            if(animation)
            {
                $("#todo-tasks-list").hide(500, function() {
                    $("#todo-tasks-list").html(response);

                    $("#todo-tasks-list").show(500, function() {
                    });
                });
            }
            else {
                $("#todo-tasks-list").html(response);
            }
        },
    }); 
}

function updateTask(taskIndex, ignoreTimeout = false) {
    if((Date.now() - lastKeyPress) >= KEYPRESS_DELAY && !ignoreTimeout)
    {
        setTimeout(function() {
            updateTaskSave(taskIndex);
        }, KEYPRESS_DELAY)
    }
    else if(ignoreTimeout){
        updateTaskSave(taskIndex);
    }
    lastKeyPress = Date.now();
    return;
}

function updateTaskSave(taskIndex) {
    var taskLi = $("#task-item-" + taskIndex);

    var completed = $(taskLi.find(":checkbox")).is(":checked");
    var name = $(taskLi.find(":text")).val();

    var url = "/todo/update/" + taskIndex;

    $.ajax({
        type: "POST",
        url: url,
        data: {
            "name": name,
            "completed": completed,
        },
        success: function(response) {
        },
    }); 
}

function deleteTask(taskIndex) {
    var url = "/todo/delete/" + taskIndex;

    $.ajax({
        type: "POST",
        url: url,
        success: function(resp) {
            refreshTasks();
        },
    });  
}

function toggleAllCompletion() {
    $('#todoList').find('li').each(function() {
        $($(this).find(":checkbox")[0]).prop("checked", (markAllAsCompleted ? "checked" : ""));
        updateTask($(this).attr("data-index"), true);
    });

    markAllAsCompleted = !markAllAsCompleted;
}

function deleteCompleted() {
    $('#todoList').find('li').each(function() {
        var isChecked = $($(this).find(":checkbox")[0]).prop("checked");

        if(isChecked) {
            deleteTask($(this).attr("data-index"))
        }
    });
}
