// setup an "add a tag" link
var $addTaskLink = $('<a href="#" class="add_task_link">Add a task</a>');
var $newLinkLi = $('<li></li>').append($addTaskLink);

jQuery(document).ready(function() {
    // Set the todo list variable
    var todoList = $("#todoList");
    //Set the add-task field
    var addTaskField = $("#addTask");
    //Set the form variable
    var todoListForm = $('form[name="todo"]');
    //Get the template for adding new tasks
    var taskTemplate = todoList.data('prototype');

    //Send form as AJAX instead of post
    todoListForm.submit(function(e) {
        console.log("A");
        e.preventDefault();
        $.post('/',$(this).serialize(),function(e) {
            console.log("R");
        }, 'JSON');
    });


    // Get the ul that holds the collection of tags
   var $collectionHolder = $('ul.tasks');
    
    // add the "add a tag" anchor and li to the tags ul
    $collectionHolder.append($newLinkLi);
    
    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find(':input').length);
    
    $addTaskLink.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();
        
        // add a new tag form (see code block below)
        addTaskForm($collectionHolder, $newLinkLi);
    });

      // handle the removal, just for this example
    $('.remove-task').click(function(e) {
        e.preventDefault();
        
        $(this).parent().remove();
        
        return false;
    });

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
        //Get the number of tasks added so far
        var taskCount = todoList.find(':input').length;

        //Replace the __name__ string in the template with the index
        var newTaskFormHTML = taskTemplate.replace(/__name__/g, taskCount+1);

        //New id we just created with the regex replacement
        var newTextId = $(newTaskFormHTML).find(':text').attr('id');
        var newCompletedId = $(newTaskFormHTML).find(':checkbox').attr('id');

        //Setup the new task element
        var newTaskHTML = "<li>" + newTaskFormHTML + "<span class='remove-task'></span></li>";

        todoList.prepend(newTaskHTML);

        //Set the value of the new task
        $('#' + newTextId).val(taskName);

        //Wrap checkbox in span
        $('#' + newCompletedId).parent().wrap("<span class='task-completed'></span>")

        todoListForm.submit();
    }

    function addTaskForm($collectionHolder, $newLinkLi) {
        // Get the data-prototype explained earlier
        var prototype = $collectionHolder.data('prototype');
        
        // get the new index
        var index = $collectionHolder.data('index');
        
        // Replace '$$name$$' in the prototype's HTML to
        // instead be a number based on how many items we have
        var newForm = prototype.replace(/__name__/g, index);
        
        // increase the index with one for the next item
        $collectionHolder.data('index', index + 1);
        
        // Display the form in the page in an li, before the "Add a tag" link li
        var $newFormLi = $('<li></li>').append(newForm);
        
        // also add a remove button, just for this example
        $newFormLi.append('<a href="#" class="remove-task">x</a>');
        
        $newLinkLi.before($newFormLi);
        
        // handle the removal, just for this example
        $('.remove-task').click(function(e) {
            e.preventDefault();
            
            $(this).parent().remove();
            
            return false;
        });
    }

});

function filterTodo(caller,filterType='all') {
    $("#todo-menu-list").find("li").removeClass("active");
    $(caller).addClass("active");
}

