<nav class="navbar navbar-fixed-top navbar-default" id="menu" role="navigation" style="background: rgb(39, 56, 97); left: 0px;">
    <div class="navbar-header">
        <button class="navbar-toggle collapsed" data-target=".navbar-ex1-collapse" data-toggle="collapse" type="button">
            <span class="sr-only">
                Toggle navigation
            </span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
    </div>
    <div class="navbar-collapse navbar-ex1-collapse collapse" style="height: 339px;">
        <ul class="nav navbar-nav nav_menu">
            <li class="dashboard_menu">
                <a href="<?php echo url::base() ?>dashboard"><i class="fa fa-home" aria-hidden="true"></i></a>
            </li>
            <li class="customers_menu">
                <a href="<?php echo url::base() ?>customers">Customers</a>
            </li>
            <li class="calendar_menu">
                <a href="<?php echo url::base() ?>calendar">Calendar</a>
            </li>
            <li class="sales_menu">
                <a  href="<?php echo url::base() ?>sales">
                    Sales
                </a>
            </li>
            <li class="routes_menu">
                <a  href="<?php echo url::base() ?>routes">
                    Routes & Technicians
                </a>
            </li>
            <!--
            <li class="hidden-xs hidden-md hidden-sm">
                <a href="/campaigns" data-original-title="" title="">Marketing</a>
            </li>
            <li>
                <a href="/reports" data-original-title="" title="">Reports</a>
            </li> -->
        </ul>
        <form action="/search" class="navbar-form navbar-left" method="get" role="search">
            <div class="form-group">
                <input class="form-control search ui-autocomplete-input" name="q" placeholder="Search..." type="text" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">
            </div>
        </form>
        <ul class="nav navbar-nav navbar-right nav_menu">
            <li class="dropdown"></li>
            <li class="dropdown">
                <a class="dropdown-toggle icon" data-toggle="dropdown" href="#" data-original-title="" title="">
                    <i class="fa fa-bars" aria-hidden="true"></i>
                </a>
                <ul class="dropdown-menu">
                   <li>
                      <div class="rcontent-menu" style="width: 100%;">
                         <div class="row">
                            <ul class="col-lg-12 list-unstyled">
                               <li>
                                  <a>
                                     User Management
                                  </a>
                               </li>
                               <li>
                                  <a>
                                     Account Management
                                  </a>
                               </li>
                               <li>
                                  <a>
                                     Report feedback / bugs
                                  </a>
                               </li>
                            </ul>
                         </div>
                      </div>
                   </li>
                </ul>
            </li>
            <li> <!-- class="dropdown notifications__menu" -->
                <a href="<?php echo url::base() ?>options" title=""> <!-- class="dropdown-toggle icon" data-toggle="dropdown" data-original-title="" -->
                    <i class="fa fa-cog" aria-hidden="true"></i>
                </a>
                <!-- <ul class="dropdown-menu dropdown-menu-right notifications__submenu">
                   <li class="hide notifications__more_events">
                      <a href="javascript:void(0)">
                      There are more events not shown here
                      </a>
                   </li>
                   <li class="notifications__no_events">
                      <a href="javascript:void(0)">
                      There are no events yet.
                      </a>
                   </li>
                </ul> -->
            </li>
            <li class="dropdown" style="margin-right: 10px;">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-original-title="" title=""><strong><?php if($this->sess_cus['name']): echo $this->sess_cus['name']; endif; ?></strong>
                    <b class="caret"></b>
                </a>
                <ul class="dropdown-menu">
                   <li>
                      <a href="<?php echo url::base() ?>login/log_out">Log out</a>
                   </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>

<!-- Loading -->
    <div id="loading">
        <div style="position: absolute;top: 50%;left: 50%;">
            <img src="<?php echo url::base() ?>public/images/loading_48.gif" alt="">
        </div>
    </div>
    <div id="opacity_overlay"></div>
<!-- END Loading -->

<!-- overlay -->
    <div id="wrap-overlay" class="overlay"></div>
<!-- end overlay -->