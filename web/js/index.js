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

    //
    // Purpose: Adds a task through AJAX and updates the list
    //
    // Parameters: 
    //      taskName - The name of the task to add
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

//
// Purpose: Called when one of the filter options is selected. 
//          Removes the active class from all options, and then 
//          adds to the selected option. Then refreshes task list
//
// Parameters: 
//      caller - The element that called the function
function filterTodo(caller) {
    $("#todo-menu-list").find("li").removeClass("active");
    $(caller).addClass("active");

    refreshTasks();
}

//
// Purpose: Uses AJAX to refresh the task list. Determines the filter to use,
//          and animates the change if requested.
//
// Parameters: 
//      animation - If true, the task list will use jQuery hide/show to animate in/out
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

//
// Purpose: Called when a task's value has changed (name or completed). Launches a timeout
//          to initiate the update, and ignores any future calls until the timeout is completed.
//          This is intended to reduce server strain if a database was used instead of session variables
//
// Parameters: 
//      taskIndex - The index of the task being updates
//      ignoreTimeout - If true, the task is updated regardless of the last update time
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

//
// Purpose: Uses AJAX to update a task.
//
// Parameters: 
//      taskIndex - The index of the task being updated
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

//
// Purpose: Uses AJAX to delete a task, and then refreshes the task list
//
// Parameters: 
//      taskIndex - The index of the task being deleted
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

//
// Purpose: Either completes or un-completes all tasks
//
function toggleAllCompletion() {
    $('#todoList').find('li').each(function() {
        $($(this).find(":checkbox")[0]).prop("checked", (markAllAsCompleted ? "checked" : ""));
        updateTask($(this).attr("data-index"), true);
    });

    markAllAsCompleted = !markAllAsCompleted;
}

//
// Purpose: Deletes all tasks that are completed
//
function deleteCompleted() {
    $('#todoList').find('li').each(function() {
        var isChecked = $($(this).find(":checkbox")[0]).prop("checked");

        if(isChecked) {
            deleteTask($(this).attr("data-index"))
        }
    });
}
