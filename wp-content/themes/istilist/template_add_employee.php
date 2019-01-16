<?php /* Template Name: Add Employee */ ?>
<?php get_header(); ?>
<?php if (is_user_logged_in()) {
    ?>
<?php global $user_ID; ?>
<div id="dashboard">
	<div class="maincontent noPadding">
	    <div class="section group">
	        <?php get_sidebar('menu'); ?>
	        <div class="col span_9_of_12 matchheight">
                <div class="dash_content">
                    <h1><?php the_title(); ?></h1>
                    <div class="box">
                        <form id="forms" method="post" action="" enctype="multipart/form-data">
                            <div class="section group">
                                <div class="col span_6_of_12">
                                    <label>Stylist / Employee Name <span>*</span></label>
                                    <input id="employee_name" type="text" name="employee_name" />
                                </div>
                                <div class="col span_6_of_12">
                                    <label>Email Address <span>*</span></label>
                                    <input id="employee_email_address" type="text" name="email_address" />
                                </div>
                            </div>
                            <div class="section group">
                                <div class="col span_6_of_12">
                                     <label>Password <span>*</span></label>
                                     <input id="employee_password" type="password" name="password" />
                                </div>
                                <div class="col span_6_of_12">
                                    <label>Phone Number</label>
                                    <input id="employee_phone_number" type="text" name="phone_number" />
                                </div>
                            </div>
                            <div class="section group">
                                <div class="col span_6_of_12">
                                    <label>Role</label>
                                    <select id="employeerole"  name="employeerole">
                                        <option value="storeemployee">Employee</option>
                                        <option value="storesupervisor">Supervisor</option>
                                    </select>
                                </div>
                            </div>
                            <div class="section group">
                                <div class="col span_12_of_12">
                                    <div class="alignright"><input type="submit" id="add_employee" name="add_employee" value="Add Employee" /></div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <?php get_footer(); ?>                          
	        </div>
	    </div>
	</div>
</div>
<?php } else { header('Location: '.get_bloginfo('url').'/login'); } ?>