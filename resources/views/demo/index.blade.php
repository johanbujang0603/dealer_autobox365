@extends('layouts.app')

@section('page_header')
    @include('layouts.common.page_header', ['pageTitle' =>  __('menu.inventories.index') ] )
@endsection


@section('page_content')

<!-- Main charts -->
<div class="row">
    <div class="col-xl-12">

            <!-- Header groups -->
            <div class="card">
                <div class="card-header header-elements-inline">
                    <h5 class="card-title">Header groups</h5>
                    <div class="header-elements">
                        <div class="list-icons">
                            <a class="list-icons-item" data-action="collapse"></a>
                            <a class="list-icons-item" data-action="reload"></a>
                            <a class="list-icons-item" data-action="remove"></a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    Example of a responsive table with <code>header groups</code>. It is possible to have advanced table header setups where your column headers are grouped. The groupings will also be inside the detail rows as a heading. To use this method, add <code>data-group</code> to the table header cells that you want to group and add <code>.footable-group-row</code> to the group row.
                </div>

                <table class="table table-togglable table-hover">
                    <thead>
                        <tr>
                            <th data-toggle="true" data-group="name">First Name</th>
                            <th data-hide="phone" data-group="name">Last Name</th>
                            <th data-hide="phone,tablet" data-group="details">Job Title</th>
                            <th data-hide="phone,tablet" data-group="details">DOB</th>
                            <th data-hide="phone" data-group="other">Status</th>
                            <th class="text-center" data-group="other" style="width: 30px;"><i class="icon-menu-open2"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Marth</td>
                            <td><a href="#">Enright</a></td>
                            <td>Traffic Court Referee</td>
                            <td>22 Jun 1972</td>
                            <td><span class="badge badge-success">Active</span></td>
                            <td class="text-center">
                                <div class="list-icons">
                                    <div class="dropdown">
                                        <a href="#" class="list-icons-item" data-toggle="dropdown">
                                            <i class="icon-menu9"></i>
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a href="#" class="dropdown-item"><i class="icon-file-pdf"></i> Export to .pdf</a>
                                            <a href="#" class="dropdown-item"><i class="icon-file-excel"></i> Export to .csv</a>
                                            <a href="#" class="dropdown-item"><i class="icon-file-word"></i> Export to .doc</a>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Jackelyn</td>
                            <td>Weible</td>
                            <td><a href="#">Airline Transport Pilot</a></td>
                            <td>3 Oct 1981</td>
                            <td><span class="badge badge-secondary">Inactive</span></td>
                            <td class="text-center">
                                <div class="list-icons">
                                    <div class="dropdown">
                                        <a href="#" class="list-icons-item" data-toggle="dropdown">
                                            <i class="icon-menu9"></i>
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a href="#" class="dropdown-item"><i class="icon-file-pdf"></i> Export to .pdf</a>
                                            <a href="#" class="dropdown-item"><i class="icon-file-excel"></i> Export to .csv</a>
                                            <a href="#" class="dropdown-item"><i class="icon-file-word"></i> Export to .doc</a>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Aura</td>
                            <td>Hard</td>
                            <td>Business Services Sales Representative</td>
                            <td>19 Apr 1969</td>
                            <td><span class="badge badge-danger">Suspended</span></td>
                            <td class="text-center">
                                <div class="list-icons">
                                    <div class="dropdown">
                                        <a href="#" class="list-icons-item" data-toggle="dropdown">
                                            <i class="icon-menu9"></i>
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a href="#" class="dropdown-item"><i class="icon-file-pdf"></i> Export to .pdf</a>
                                            <a href="#" class="dropdown-item"><i class="icon-file-excel"></i> Export to .csv</a>
                                            <a href="#" class="dropdown-item"><i class="icon-file-word"></i> Export to .doc</a>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Nathalie</td>
                            <td><a href="#">Pretty</a></td>
                            <td>Drywall Stripper</td>
                            <td>13 Dec 1977</td>
                            <td><span class="badge badge-info">Pending</span></td>
                            <td class="text-center">
                                <div class="list-icons">
                                    <div class="dropdown">
                                        <a href="#" class="list-icons-item" data-toggle="dropdown">
                                            <i class="icon-menu9"></i>
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a href="#" class="dropdown-item"><i class="icon-file-pdf"></i> Export to .pdf</a>
                                            <a href="#" class="dropdown-item"><i class="icon-file-excel"></i> Export to .csv</a>
                                            <a href="#" class="dropdown-item"><i class="icon-file-word"></i> Export to .doc</a>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Sharan</td>
                            <td>Leland</td>
                            <td>Aviation Tactical Readiness Officer</td>
                            <td>30 Dec 1991</td>
                            <td><span class="badge badge-secondary">Inactive</span></td>
                            <td class="text-center">
                                <div class="list-icons">
                                    <div class="dropdown">
                                        <a href="#" class="list-icons-item" data-toggle="dropdown">
                                            <i class="icon-menu9"></i>
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a href="#" class="dropdown-item"><i class="icon-file-pdf"></i> Export to .pdf</a>
                                            <a href="#" class="dropdown-item"><i class="icon-file-excel"></i> Export to .csv</a>
                                            <a href="#" class="dropdown-item"><i class="icon-file-word"></i> Export to .doc</a>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Maxine</td>
                            <td><a href="#">Woldt</a></td>
                            <td><a href="#">Business Services Sales Representative</a></td>
                            <td>17 Oct 1987</td>
                            <td><span class="badge badge-info">Pending</span></td>
                            <td class="text-center">
                                <div class="list-icons">
                                    <div class="dropdown">
                                        <a href="#" class="list-icons-item" data-toggle="dropdown">
                                            <i class="icon-menu9"></i>
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a href="#" class="dropdown-item"><i class="icon-file-pdf"></i> Export to .pdf</a>
                                            <a href="#" class="dropdown-item"><i class="icon-file-excel"></i> Export to .csv</a>
                                            <a href="#" class="dropdown-item"><i class="icon-file-word"></i> Export to .doc</a>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Sylvia</td>
                            <td><a href="#">Mcgaughy</a></td>
                            <td>Hemodialysis Technician</td>
                            <td>11 Nov 1983</td>
                            <td><span class="badge badge-danger">Suspended</span></td>
                            <td class="text-center">
                                <div class="list-icons">
                                    <div class="dropdown">
                                        <a href="#" class="list-icons-item" data-toggle="dropdown">
                                            <i class="icon-menu9"></i>
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a href="#" class="dropdown-item"><i class="icon-file-pdf"></i> Export to .pdf</a>
                                            <a href="#" class="dropdown-item"><i class="icon-file-excel"></i> Export to .csv</a>
                                            <a href="#" class="dropdown-item"><i class="icon-file-word"></i> Export to .doc</a>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Lizzee</td>
                            <td><a href="#">Goodlow</a></td>
                            <td>Technical Services Librarian</td>
                            <td>1 Nov 1961</td>
                            <td><span class="badge badge-danger">Suspended</span></td>
                            <td class="text-center">
                                <div class="list-icons">
                                    <div class="dropdown">
                                        <a href="#" class="list-icons-item" data-toggle="dropdown">
                                            <i class="icon-menu9"></i>
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a href="#" class="dropdown-item"><i class="icon-file-pdf"></i> Export to .pdf</a>
                                            <a href="#" class="dropdown-item"><i class="icon-file-excel"></i> Export to .csv</a>
                                            <a href="#" class="dropdown-item"><i class="icon-file-word"></i> Export to .doc</a>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- /header groups -->


    </div>

</div>
<!-- /main charts -->

@endsection


@section('scripts')
    <script src="{{ asset('global_assets/js/plugins/tables/footable/footable.min.js') }}"></script>
    <script>
        $(document).ready(function(){
            $('.table-togglable').footable();
        })
    </script>
@endsection
