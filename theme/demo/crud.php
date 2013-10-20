<h1>CRUD UI Demo</h1>
<h4>Users List</h4>
<div id="containerUserList"></div>
<div class="row-fluid">
    <div class="span6">
        <h4>Edit User <a href="#" id="new" class="btn">New</a></h4>
        <div id="containerUserForm"></div>
    </div>
    
    <div class="span3">
        <h4>Groups Associated</h4>
        <div id="containerUserGroups"
             style="border: 1px dashed gray; min-height: 20px"></div>
    </div>
    <div class="span3">
        <h4>Groups Available</h4>
        <div id="containerGroupList" 
             style="border: 1px dashed gray; min-height: 20px"></div>
    </div>
</div>
<script type="text/javascript">
    
    var CRUD = {
        current: 1
    };
    
    CRUD.showUserForm = function() {
        $('#containerUserForm').load(UI.url('/demo/crud/user/'+CRUD.current+'/edit'));
        CRUD.showUserGroupList();
    }
    
    CRUD.showUserList = function() {
        $('#containerUserList').load(UI.url('/demo/crud/user/list'));
    }
    
    CRUD.showGroupList = function() {
        $('#containerGroupList').load(UI.url('/demo/crud/group/list'));
    }
    
    CRUD.showUserGroupList = function() {
        var id = CRUD.current;
        $('#containerUserGroups').load(UI.url('/demo/crud/user/'+id+'/group/list'));
    }
    
    CRUD.addGroup = function(group_id) {
        var data = {id_user: CRUD.current, id_group: group_id};
        $.post(UI.url('/demo/crud/user/add_group'), data, function() {
            CRUD.showUserGroupList();
        });
    }
    
    CRUD.removeGroup = function(group_id) {
        var data = {id_user: CRUD.current, id_group: group_id};
        $.post(UI.url('/demo/crud/user/del_group'), data, function() {
            CRUD.showUserGroupList();
        });
    }
    
    CRUD.saveUser = function(data) {
        $.post(UI.url('/demo/crud/user/save'), data, function(json) {
            CRUD.current = json.id;
            CRUD.showUserForm();
            CRUD.showUserList();
            CRUD.showUserGroupList();
        });
    }
    
    CRUD.deleteUser = function() {
        var id = CRUD.current;
        $.get(UI.url('/demo/crud/user/'+id+'/delete'), function(json) {
            CRUD.current = 0;
            CRUD.showUserForm();
            CRUD.showUserList();
        });
    }

    jQuery(function($)
    {
        CRUD.showGroupList();
        CRUD.showUserList();
        CRUD.showUserGroupList();
        CRUD.showUserForm();
        
        $(document).on('click', '#new', function(e) {
            e.preventDefault();
            e.stopPropagation();
            CRUD.current = 0;
            CRUD.showUserForm();
        });
        
        $(document).on('click', '#delete', function(e) {
            e.preventDefault();
            e.stopPropagation();
            CRUD.deleteUser();
        });


        $(document).on('click', '#containerUserList a', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var params = $(e.target).attr('data-ui').split('/');
            CRUD.current = params[1];
            CRUD.showUserForm();
        });
        
        $(document).on('submit', '#containerUserForm', function(e) {
            e.preventDefault();
            var data = $(e.target).serialize();
            CRUD.saveUser(data);
        });
        
        UI.drag('#containerGroupList a', '#containerUserGroups', function(data)
        {
            CRUD.addGroup(data.split('/')[1]);
        });
        
        UI.drag('#containerUserGroups a', '#containerGroupList', function(data)
        {
            CRUD.removeGroup(data.split('/')[1]);
        });
    });
</script>