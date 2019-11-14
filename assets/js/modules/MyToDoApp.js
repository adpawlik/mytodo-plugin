import $ from 'jquery';

class MyToDoApp {
    constructor(){
        this.taskInput = $("#mytodoapp-task-title");
        this.taskList = $('#mytodoapp-alerts');
        this.taskCheckbox = 0;
        this.events();
    }

    events(){
        $(document).on("keyup", this.createTask.bind(this));
        $("#mytodoapp-list").on("keyup", ".mytodoapp-list-item-title", this.updateTask.bind(this));
        $("#mytodoapp-list").on("click", ".mytodoapp-list-item-checkbox", this.taskStatus.bind(this));
        $("#mytodoapp-list").on("click", ".mytodoapp-delete", this.deleteTask);
    }

    createTask(e){
        if($('#mytodoapp-task-done').is(':checked') ){
            this.taskCheckbox = $("#mytodoapp-task-done").val();
        }
        const ourNewPost = {
            'title': $("#mytodoapp-task-title").val(),
            'mytodoapp_checkbox_value': this.taskCheckbox,
            'status': 'publish'
        }
        if (e.keyCode == 13 && $("#mytodoapp-task-title").is(':focus') && $("#mytodoapp-task-title").val() != '') { 
            $.ajax({
                beforeSend: (xhr) => {
                    xhr.setRequestHeader('X-WP-Nonce', mytodoappData.nonce);
                },
                url: mytodoappData.root_url + '/wp-json/wp/v2/mytodoapp/',
                type: 'POST',
                data: ourNewPost,
                success: (response) => {
                    this.taskList.text("");
                    $(`
                    <li data-id="${response.id}" id="post-${response.id}" class="mytodoapp-item post-${response.id}" >
                        <div class="mytodoapp-list-item-checkbox-wrapper">
                            <input class="mytodoapp-list-item-checkbox" type="checkbox" name="${response.id}" value="yes" ${(response.mytodoapp_metabox.mytodoapp_checkbox_value[0] == 'yes') ? 'checked="checked"' : '' }  /> 
                        </div>
                        <div class="mytodoapp-list-item-title-wrapper">
                            <input class="mytodoapp-list-item-title" type="text" value="${response.title.raw}" /> 
                        </div>
                        <div class="mytodoapp-delete">&#10006;</div>
                    </li>
                `).appendTo("#mytodoapp-list").hide().slideDown();
                    this.taskInput.val('');
                    this.taskInput.blur();
                },
                error: (response) => {
                },
            });
        } else if (e.keyCode == 13 && $("#mytodoapp-task-title").is(':focus'))  {
            this.taskList.text("Please enter title");
        }
        this.taskCheckbox = 0;
    }

    updateTask(e){
        const thisTask = $(e.target).parents(".mytodoapp-item");
        const thisTaskInput = $(e.target);
        if(thisTask.find(".mytodoapp-list-item-checkbox").is(':checked') ){
            this.taskCheckbox = "yes";
        }
        const ourUpdatedPost = {
            'title': thisTask.find(".mytodoapp-list-item-title").val(),
            'mytodoapp_checkbox_value': this.taskCheckbox
        }
        if (e.keyCode == 13 && thisTask.find(".mytodoapp-list-item-title").is(':focus') && thisTask.find(".mytodoapp-list-item-title").val() != '') {   
            $.ajax({
                beforeSend: (xhr) => {
                    xhr.setRequestHeader('X-WP-Nonce', mytodoappData.nonce);
                },
                url: mytodoappData.root_url + '/wp-json/wp/v2/mytodoapp/' + thisTask.data('id'),
                type: 'POST',
                data: ourUpdatedPost,
                success: (response) => {
                    this.taskList.text("");
                    thisTaskInput.blur();
                },
                error: (response) => {
                },

            });
        } else if (e.keyCode == 13 && thisTask.find(".mytodoapp-list-item-title").is(':focus'))  {
            this.taskList.text("Please enter title");
        }
    }

    taskStatus(e){
        const thisTask = $(e.target).parents(".mytodoapp-item-js");
        if(thisTask.find(".mytodoapp-list-item-checkbox").is(':checked') ){
            this.taskCheckbox = "yes";
        }
        var ourUpdatedPost = {
            'mytodoapp_checkbox_value': this.taskCheckbox
        } 
       
        if (thisTask.hasClass("mytodoapp-item-js")) {
            $.ajax({
                beforeSend: (xhr) => {
                    xhr.setRequestHeader('X-WP-Nonce', mytodoappData.nonce);
                },
                url: mytodoappData.root_url + '/wp-json/wp/v2/mytodoapp/' + thisTask.data('id'),
                type: 'POST',
                data: ourUpdatedPost,
                success: (response) => {
                    this.taskList.text("");
                },
                error: (response) => {
                },
            });
        }
    }

    deleteTask(e){
        const thisTask = $(e.target).parents(".mytodoapp-item");
        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce', mytodoappData.nonce);
            },
            url: mytodoappData.root_url + '/wp-json/wp/v2/mytodoapp/' + thisTask.data('id'),
            type: 'DELETE',
            success: (response) => {
                thisTask.slideUp();
            },
            error: (response) => {
            },

        });
    }

}
export default MyToDoApp;