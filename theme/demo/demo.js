
/**
 * Demo CRUD interface namespace
 */
var CRUD = {
    current: 1,
    userform: null,
    userlist: null,
    grouplist: null,
    usergroupslist: null
};

/**
 * Run initialize when jQuery is ready
 */
CRUD.init = function () {
    
    CRUD.userform   = $('#containerUserForm');
    CRUD.userlist   = $('#containerUserList');
    CRUD.grouplist  = $('#containerGroupList');
    CRUD.usergroupslist  = $('#containerUserGroups');
    
    CRUD.showGroupList();
    CRUD.showUserList();
    CRUD.showUserGroupList();
    CRUD.showUserForm();
}

/**
 * Bind interface events
 */
CRUD.addEvents = function () {
    
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
}

/**
 * Ajax comunications handler
 */
CRUD.ajax = function (url, data, cb) {
    if (data) $.post(url, data, cb);
    else $.get(url, cb);
}

CRUD.getEditUrl = function() {
    return UI.url('/demo/crud/user/'+CRUD.current+'/edit');
}

CRUD.getUserListUrl = function () {
    return UI.url('/demo/crud/user/list');
}

CRUD.getGroupListUrl = function () {
    return UI.url('/demo/crud/group/list');
}

CRUD.getUserGroupsListUrl = function () {
    return UI.url('/demo/crud/user/'+CRUD.current+'/group/list');
}

CRUD.getAddGroupUrl = function () {
    return UI.url('/demo/crud/user/add_group');
}

CRUD.getRemoveGroupUrl = function () {
    return UI.url('/demo/crud/user/del_group');
}

CRUD.getSaveUserUrl = function () {
    return UI.url('/demo/crud/user/save');
}

CRUD.getDeleteUserUrl = function () {
    return UI.url('/demo/crud/user/'+CRUD.current+'/delete');
}

CRUD.showUserForm = function () {
    CRUD.userform.load(CRUD.getEditUrl());
    CRUD.showUserGroupList();
}

CRUD.showUserList = function () {
    CRUD.userlist.load(CRUD.getUserListUrl());
}

CRUD.showGroupList = function () {
    CRUD.grouplist.load(CRUD.getGroupListUrl());
}

CRUD.showUserGroupList = function () {
    CRUD.usergroupslist.load(CRUD.getUserGroupsListUrl());
}

CRUD.addGroup = function (group_id) {
    var data = {id_user: CRUD.current, id_group: group_id};
    CRUD.ajax(CRUD.getAddGroupUrl(), data, function () {
        CRUD.showUserGroupList();
    });
}

CRUD.removeGroup = function (group_id) {
    var data = {id_user: CRUD.current, id_group: group_id};
    CRUD.ajax(CRUD.getRemoveGroupUrl(), data, function () {
        CRUD.showUserGroupList();
    });
}

CRUD.saveUser = function (data) {
    CRUD.ajax(CRUD.getSaveUserUrl (), data, function (json) {
        CRUD.current = json.id;
        CRUD.showUserForm();
        CRUD.showUserList();
        CRUD.showUserGroupList();
    });
}

CRUD.deleteUser = function () {
    CRUD.ajax(CRUD.getDeleteUserUrl (), function () {
        CRUD.current = 0;
        CRUD.showUserForm();
        CRUD.showUserList();
    });
}