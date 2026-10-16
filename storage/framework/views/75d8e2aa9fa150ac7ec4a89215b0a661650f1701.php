<?php $__env->startSection('pageCSS'); ?>

    <link href="google.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo e(env('APP_URL')); ?>/themes/metronic/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo e(env('APP_URL')); ?>/themes/metronic/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo e(env('APP_URL')); ?>/themes/metronic/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>

    <link href="<?php echo e(env('APP_URL')); ?>/themes/metronic/assets/global/css/components.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo e(env('APP_URL')); ?>/themes/metronic/assets/global/css/plugins.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo e(env('APP_URL')); ?>/themes/metronic/assets/admin/layout/css/layout.css" rel="stylesheet" type="text/css"/>
    <link id="style_color" href="<?php echo e(env('APP_URL')); ?>/themes/metronic/assets/admin/layout/css/themes/default.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo e(env('APP_URL')); ?>/themes/metronic/assets/admin/layout/css/custom.css" rel="stylesheet" type="text/css"/>
    <style type="text/css">
        table td {
            padding-bottom: 10px; 
        }
    </style>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <div class="row">

        <div class="col-md-12">

            <h3 class="page-title"> Item Inventory </h3>

            <ul class="page-breadcrumb breadcrumb">
                <li> 
                    <?php if($create): ?>
                        <a class="btn blue" data-toggle="modal" data-backdrop="static" href="#modalAdd" onclick="addItem()" style="color:white;">Add New</a>    
                    <?php else: ?>
                        <button disabled class="btn blue" data-toggle="modal" data-backdrop="static" href="#modalAdd" onclick="addItem()" style="color:white;">Add New</button>
                    <?php endif; ?>                
                </li>
            </ul>

            <div class="portlet box blue">

                <div class="portlet-title">
                    <div class="caption">
                        <i class=" icon-list"></i> List of Items
                    </div>
                            
                </div>

                <div class="portlet-body">

                    <form method="get" action="<?php echo e(route('items.index')); ?>">
                        <table width="100%">
                            <tr>
                                <td>Search:<input type="hidden" name="action" value="search"></td>
                                <td><input type="text" name="searchtxt" id="searchtxt" class="form-control input " placeholder="Enter Name"></td>                                 
                                <td align="left"><input type="submit" class="btn purple" value="Search"> </td>                                  
                            </tr>
                        </table>
                    </form>                

                    <div class="table-scrollable">
                        
                        <table class="table table-hover">

                            <thead>
                                <tr>                                    
                                    <th>Code</th>
                                    <th>Name</th>                                   
                                    <th>UOM</th>
                                    <th>Category</th>                                   
                                    <th>Unit Cost</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
										<td>
                                            <?php echo e(($item->code)); ?>

										</td>
                                        <td>
                                            <?php echo e(($item->name)); ?>

                                        </td>
                                        <td>
                                            <?php echo e(($item->uom)); ?>

                                        </td>
                                        <td>
                                            <?php echo e(($item->category)); ?>

                                        </td>
                                        <td>
                                            <?php echo e(($item->unit_cost)); ?>

                                        </td>                                        
                                        

                                        <td>
                                            <?php if($edit): ?>
                                                <a href="#" class="btn btn-success btn-xs edit_item" onclick="update_item('<?php echo e($item->id); ?>','<?php echo e($item->code); ?>','<?php echo e($item->name); ?>','<?php echo e($item->uom); ?>','<?php echo e($item->category); ?>','<?php echo e($item->unit_cost); ?>')"><i class='fa fa-pencil'></i> </a>
                                            <?php else: ?>
                                                <button disabled href="#" class="btn btn-success btn-xs edit_item"><i class='fa fa-pencil'></i> </button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td class="text-center" colspan="4"> No items Found </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>

                        </table>

                    </div>

                </div>
                
                <div class="col-md-6" style="margin-top: 10px; padding-top: 10px;">
                    Items <?php echo e($items->firstItem()); ?> - <?php echo e($items->lastItem()); ?>                        
                </div> 

                <div class="col-md-6 text-right">
                    <?php echo e($items->withQueryString()->links()); ?>                        
                </div>   

             </div>

        </div>

    </div>

    <div class="modal fade" id="modalAdd" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form method="post" action="<?php echo e(route('items.store')); ?>">
                <?php echo csrf_field(); ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="modal_title">Add New Item</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            
                <div class="modal-body">

                    <table width="100%">
                                            
                        <tr>
                            <td width="150"><label>Code <span class="required" aria-required="true"> * </span></label></td>
                            <td><input type="text" class="form-control" id="code" name="code" placeholder="Code" required maxlength="30"></td>                                       
                        </tr>

                        <tr>
                            <td width="150"><label>Name <span class="required" aria-required="true"> * </span></label></td>
                            <td><input type="text" class="form-control" id="name" name="name" placeholder="Name" required maxlength="30"></td>                                       
                        </tr>
                        
                        <tr>
                            <td width="150"><label>UoM <span class="required" aria-required="true"> * </span></label></td>
                            <td><input type="text" class="form-control" id="uom" name="uom" placeholder="UoM" required maxlength="30"></td>                                       
                        </tr>
                        
                        <tr>
                            <td width="150"><label>Category <span class="required" aria-required="true"> * </span></label></td>
                            <td>
                                <select class="form-control select2me" name="category" required id="category" data-placeholder="Select...">
                                    <option></option>
                                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($category->name); ?>"><?php echo e($category->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>                                
                            </td>
                        </tr>                        

                        <tr>
                            <td width="150"><label>Unit Cost <span class="required" aria-required="true"> * </span></label></td>
                            <td>
                                <input type="number" class="form-control" name="unit_cost" id= "unit_cost" step="any" placeholder="Unit Cost" required>
                            </td>
                        </tr>

                    </table>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="modal_action">Save Item</button>
                </div>
            </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form method="post" action="<?php echo e(route('items.update')); ?>">
                <?php echo csrf_field(); ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="modal_title">Update Item</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            
                <div class="modal-body">
                    <input type="hidden" name="nameid" id="nameid">
                    <table width="100%">

                        <tr>
                            <td width="150"><label>Code <span class="required" aria-required="true"> * </span></label></td>
                            <td><input class="form-control" type="text" name="code" id="edit_code" placeholder="Code" required maxlength="30"></td>
                        </tr>
                        
                        <tr>
                            <td width="150"><label>Name <span class="required" aria-required="true"> * </span></label></td>
                            <td><input class="form-control" type="text" name="name" id="edit_name" placeholder="Name" required maxlength="30"></td>
                        </tr>

                        <tr>
                            <td width="150"><label>UoM <span class="required" aria-required="true"> * </span></label></td>
                            <td><input class="form-control" type="text" name="uom" id="edit_uom" placeholder="UoM" required maxlength="30"></td>
                        </tr>                        

                        <tr>
                            <td width="150"><label>Category <span class="required" aria-required="true"> * </span></label></td>
                            <td>
                                <select required name="category" id="edit_category" class="form-control select2me"> 
                                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($category['name']); ?>"><?php echo e($category['name']); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select> 
                            </td>
                        </tr>

                        <tr>
                            <td width="150"><label>Unit Cost <span class="required" aria-required="true"> * </span></label></td>
                            <td>
                                <input type="number" class="form-control" name="unit_cost" id= "edit_unit_cost" step="any" placeholder="Unit Cost" required>
                            </td>
                        </tr>                        

                    </table>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="modal_action">Update Item</button>
                </div>
            </div>
            </form>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('pageJS'); ?>
    
    <script src="<?php echo e(env('APP_URL')); ?>/themes/metronic/assets/global/plugins/jquery-1.11.0.min.js" type="text/javascript"></script>
    <script src="<?php echo e(env('APP_URL')); ?>/themes/metronic/assets/global/plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
    <script src="<?php echo e(env('APP_URL')); ?>/themes/metronic/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>

    <script src="<?php echo e(env('APP_URL')); ?>/themes/metronic/assets/global/scripts/metronic.js" type="text/javascript"></script>
    <script src="<?php echo e(env('APP_URL')); ?>/themes/metronic/assets/admin/layout/scripts/layout.js" type="text/javascript"></script>
    <script src="<?php echo e(env('APP_URL')); ?>/themes/metronic/assets/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script>

    <script type="text/javascript">
            
		function update_item(id,code,name,uom,category,unit_cost)
		{
			$('#nameid').val(id);			
			$('#edit_code').val(code);
			$('#edit_name').val(name);
            $('#edit_uom').val(uom);                                    
            $('#edit_category').val(category);        
            $('#edit_unit_cost').val(unit_cost);            
			$('#modalEdit').modal('show');
		}


		function addItem() 
		{										
			$('#nameid').val('');
			$('#code').val('');
			$('#name').val('');			
            $('#category').val('');	
            $('#unit_cost').val('');	
            $('#uom').val('');	            
		}        

    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\PMC-OMWS\resources\views/pages/items/index.blade.php ENDPATH**/ ?>