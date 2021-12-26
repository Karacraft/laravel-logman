<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Model Event Logger
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="px-2">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 bg-white border-b border-gray-200">
                        Listing
                    </div>
                    
                    <div class="px-2 py-2">
                        @include('includes.tabulator_search')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="script">
        <script>
            var getDataUrl = @json(route('logman.master'));
            let table;                                              //  The Table
            let searchValue = "";                                   //  The Serach Parameter
            //  Get The Filter Value
            function dataFilter(element)
            {
                searchValue = element.value;
                table.setData(getDataUrl,{search:searchValue});
            }
            //  Startup Function
            document.addEventListener('DOMContentLoaded',function(){
                viewDataTable();                                                        //  Load DataTable
            })
            function viewDataTable()
            {
                //  Populate Tabulator
                table = new Tabulator("#tableData", {
                    // Normal Options
                    autoResize:true,
                    height:"100%",
                    layout:"fitData",                       //fit columns to width of table
                    responsiveLayout:"collapse",            //hide columns that dont fit on the table
                    index:'id',                             //Table Row Id
                    placeholder:"No Data Available",        //Placeholder text for empty table
                    sortOrderReverse:true,
                    // Pagination
                    pagination:"remote",                    //paginate the data
                    paginationSize:10,                      //starting page size
                    paginationSizeSelector:[10,20,50,100],  //  Page Size Selector
                    // Ajax Options
                    ajaxFiltering:true,
                    ajaxSorting:true,
                    ajaxParams:{search:searchValue},
                    ajaxURL: getDataUrl,
                    //  Init Sorting
                    initialSort:[
                        {column:"id", dir:"asc"},          //sort by this first
                    ],
                    // Columns
                    columns:[
                        // Master Data
                        {title:"Id", field:"id" , visible:false ,headerSortStartingDir:"asc" , responsive:0},
                        {title:"Action", field:"action" , visible:true ,headerSortStartingDir:"asc" , responsive:0},
                        {title:"Model", field:"table" , visible:true ,headerSortStartingDir:"asc" , responsive:0},
                        {title:"IP", field:"ipaddress" , visible:true ,headerSortStartingDir:"asc" , responsive:0},
                        {title:"Description", field:"description" , visible:true ,headerSortStartingDir:"asc" , responsive:0},
                        {title:"Original", field:"original" , visible:true ,headerSortStartingDir:"asc" , responsive:0},
                        {title:"Changes", field:"changes" , visible:true ,headerSortStartingDir:"asc" , responsive:0},
                        {title:"User", field:"user_name" , visible:true ,headerSortStartingDir:"asc" , responsive:0},
                     
                    ],
                    // Extra Pagination Data for End Users
                    ajaxResponse:function(getDataUrl, params, response){
                        remaining = response.total;
                        let doc = document.getElementById("example-table-info");
                        doc.classList.add('font-weight-bold');
                        doc.innerText = `Displaying ${response.from} - ${response.to} out of ${remaining} records`;
                        return response;
                    },
                });
                
            }
           
        </script>


    </x-slot>
</x-app-layout>
