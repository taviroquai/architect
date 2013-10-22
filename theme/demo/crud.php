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

    jQuery(function($)
    {
        CRUD.init();
        CRUD.addEvents();
    });
</script>