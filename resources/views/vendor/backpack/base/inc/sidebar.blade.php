@if (Auth::check())
    <!-- Left side column. contains the sidebar -->
    <aside class="main-sidebar">
      <!-- sidebar: style can be found in sidebar.less -->
      <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
          <div class="pull-left image">
            <img src="http://placehold.it/160x160/00a65a/ffffff/&text={{ Auth::user()->name[0] }}" class="img-circle" alt="User Image">
          </div>
          <div class="pull-left info">
            <p>{{ Auth::user()->name }}</p>
            <a href="#"><i class="fa fa-circle text-success"></i> {{ Auth::user()->company->company_name }}</a>
          </div>
        </div>
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
          <li><a href="{{ url(config('backpack.base.route_prefix', 'admin').'/dashboard') }}"><i class="fa fa-dashboard"></i> <span>{{ trans('backpack::base.dashboard') }}
          </span></a></li>

          <!--MASTER ENTRY-->
          <li class="header">MASTER ENTRY</li>
          <li><a href="{{ url('admin/borrowers') }}"><i class="fa fa-user-plus"></i> <span>Loan Borrowers</span></a></li>

          <!--LOAN MANAGEMENT-->
          <li class="header">LOAN MANAGEMENT</li>
          <li class="treeview">
            <a href="#"><i class="fa fa-certificate"></i> <span>Loan Applications</span> <i class="fa fa-angle-left pull-right"></i></a> 
            <ul class="treeview-menu">
              <li><a href="{{ url('admin/loan_applications') }}"><i class="fa fa-file-text-o"></i> <span>- All Loan Applications</span></a></li>
              <li><a href="{{ url('admin/loan_applications/create') }}"><i class="fa fa-file-text-o"></i> <span>+ New</span></a></li>
              <li><a href="{{ url('admin/loan_applications/active') }}"><i class="fa fa-circle-o"></i> <span>* Approve/Decline</span></a></li>
            </ul>
          </li>
          <li><a href="{{ url('admin/loan_payments') }}"><i class="fa fa-money"></i> <span>Loan Payment</span></a></li>
          <!--
          <li class="treeview">
            <a href="#"><i class="fa fa-certificate"></i> <span>Application Status</span> <i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
              <li><a href="{{ url('admin/loan_applications/pending')}}"> <i class="fa fa-circle-o"></i> <span>Pending</span> </a></li>
              <li><a href="{{ url('admin/loan_applications/declined')}}"> <i class="fa fa-window-close-o"></i> <span>Declined</span></a></li>
            </ul>
          </li>
          -->

          @role('Super_Administrator')
          <li class="header">{{ trans('backpack::base.administration') }}</li>
            <!-- Users, Roles Permissions -->
            <li class="treeview">
              <a href="#"><i class="fa fa-group"></i> <span>Users, Roles, Permissions</span> <i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">
                <li><a href="{{ url('admin/user') }}"><i class="fa fa-user"></i> <span>Users</span></a></li>
                <li><a href="{{ url('admin/role') }}"><i class="fa fa-group"></i> <span>Roles</span></a></li>
                <li><a href="{{ url('admin/permission') }}"><i class="fa fa-key"></i> <span>Permissions</span></a></li>
              </ul>
            </li>
            <li class="treeview">
              <a href="#"><i class="fa fa-group"></i> <span>Maintenance</span> <i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">
                <li><a href="{{ url('admin/companies') }}"><i class="fa fa-building"></i> <span>Companies</span></a></li>
                <li><a href="{{ url('admin/loan_interests') }}"><i class="fa fa-percent"></i> <span>Loan Interests</span></a></li>
                <li><a href="{{ url('admin/loan_payment_terms') }}"><i class="fa fa-industry"></i> <span>Loan Payment Terms</span></a></li>
                <li><a href="{{ url('admin/payment_schedules') }}"><i class="fa fa-calendar"></i> <span>Payment Schedules</span></a></li>
              </ul>
            </li>

          @endrole

          <li class="header">REPORTS</li>
          <li><a href="{{ url('admin/reports/loan_applications') }}"><i class="fa fa-file-o"></i> <span>Approved Loan Applications</span></a></li>
          <li><a href="{{ url('admin/reports/loan_collections') }}"><i class="fa fa-file-o"></i> <span>Loan Collection & Outstanding</span></a></li>
          <li><a href="{{ url('#') }}"><i class="fa fa-file-o"></i> <span>Income Share</span></a></li>

          <!-- ======================================= -->
          <li class="header">{{ trans('backpack::base.user') }}</li>
          <li><a href="{{ url(config('backpack.base.route_prefix', 'admin').'/logout') }}"><i class="fa fa-sign-out"></i> <span>{{ trans('backpack::base.logout') }}</span></a></li>
        </ul>
      </section>
      <!-- /.sidebar -->
    </aside>
@endif
